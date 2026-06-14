<?php

use App\Models\User;
use Modules\Shop\Models\Shop;
use Modules\Reconciliation\Models\CashRegister;
use Modules\Reconciliation\Models\LedgerTransaction;
use Modules\Reconciliation\Models\AccountsReceivable;
use Modules\Sale\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutMiddleware([
        \App\Http\Middleware\EnsureSubscriptionActive::class,
        \App\Http\Middleware\CheckModuleAccess::class,
    ]);
});

test('user can open a cash register session with an opening balance', function () {
    $owner = User::factory()->create(['role' => 'owner']);
    $shop = Shop::create([
        'name' => 'Test Shop 1',
        'user_id' => $owner->id
    ]);

    $response = $this->actingAs($owner)
        ->post(route('reconciliation.open'), [
            'shop_id' => $shop->id,
            'opening_balance' => 500.00
        ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('cash_registers', [
        'shop_id' => $shop->id,
        'opening_balance' => 500.00,
        'status' => 'open'
    ]);
});

test('user cannot open concurrent register sessions for the same shop', function () {
    $owner = User::factory()->create(['role' => 'owner']);
    $shop = Shop::create([
        'name' => 'Test Shop 2',
        'user_id' => $owner->id
    ]);

    CashRegister::create([
        'shop_id' => $shop->id,
        'user_id' => $owner->id,
        'opening_balance' => 100.00,
        'status' => 'open',
        'opened_at' => now(),
    ]);

    $response = $this->actingAs($owner)
        ->post(route('reconciliation.open'), [
            'shop_id' => $shop->id,
            'opening_balance' => 200.00
        ]);

    $response->assertSessionHas('error');
    $this->assertDatabaseCount('cash_registers', 1);
});

test('user can log manual income and expense to active register', function () {
    $owner = User::factory()->create(['role' => 'owner']);
    $shop = Shop::create([
        'name' => 'Test Shop 3',
        'user_id' => $owner->id
    ]);

    $register = CashRegister::create([
        'shop_id' => $shop->id,
        'user_id' => $owner->id,
        'opening_balance' => 100.00,
        'status' => 'open',
        'opened_at' => now(),
    ]);

    // Log income
    $response = $this->actingAs($owner)
        ->post(route('ledger.transaction'), [
            'register_id' => $register->id,
            'type' => 'income',
            'category' => 'Other Income',
            'amount' => 50.00,
            'notes' => 'Sold old newspapers'
        ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('ledger_transactions', [
        'register_id' => $register->id,
        'type' => 'income',
        'amount' => 50.00
    ]);

    // Log expense
    $response = $this->actingAs($owner)
        ->post(route('ledger.transaction'), [
            'register_id' => $register->id,
            'type' => 'expense',
            'category' => 'Tea/Snacks',
            'amount' => 20.00,
            'notes' => 'Tea for guest'
        ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('ledger_transactions', [
        'register_id' => $register->id,
        'type' => 'expense',
        'amount' => 20.00
    ]);
});

test('user can log accounts receivable and mark it as repaid', function () {
    $owner = User::factory()->create(['role' => 'owner']);
    $shop = Shop::create([
        'name' => 'Test Shop 4',
        'user_id' => $owner->id
    ]);

    $register = CashRegister::create([
        'shop_id' => $shop->id,
        'user_id' => $owner->id,
        'opening_balance' => 100.00,
        'status' => 'open',
        'opened_at' => now(),
    ]);

    // Log receivable
    $response = $this->actingAs($owner)
        ->post(route('ledger.receivable'), [
            'register_id' => $register->id,
            'customer_name' => 'John Doe',
            'customer_phone' => '1234567890',
            'amount' => 80.00
        ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('accounts_receivable', [
        'register_id' => $register->id,
        'customer_name' => 'John Doe',
        'amount' => 80.00,
        'status' => 'pending'
    ]);

    $receivable = AccountsReceivable::first();

    // Mark as repaid
    $response = $this->actingAs($owner)
        ->post(route('ledger.repay', $receivable->id));

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('accounts_receivable', [
        'id' => $receivable->id,
        'status' => 'repaid'
    ]);

    // Repayment should generate a manual income in the active register
    $this->assertDatabaseHas('ledger_transactions', [
        'register_id' => $register->id,
        'type' => 'income',
        'category' => 'Receivable Repayment',
        'amount' => 80.00
    ]);
});

test('reconciliation calculations on closing a register', function () {
    $owner = User::factory()->create(['role' => 'owner']);
    $shop = Shop::create([
        'name' => 'Test Shop 5',
        'user_id' => $owner->id
    ]);

    $register = CashRegister::create([
        'shop_id' => $shop->id,
        'user_id' => $owner->id,
        'opening_balance' => 500.00, // Opening
        'status' => 'open',
        'opened_at' => now()->subHours(2),
    ]);

    // Manual Income
    LedgerTransaction::create([
        'register_id' => $register->id,
        'type' => 'income',
        'category' => 'Other Income',
        'amount' => 150.00
    ]);

    // Manual Expense
    LedgerTransaction::create([
        'register_id' => $register->id,
        'type' => 'expense',
        'category' => 'Supplies',
        'amount' => 50.00
    ]);

    // Customer Due Credit (Receivable) - Pending
    AccountsReceivable::create([
        'register_id' => $register->id,
        'customer_name' => 'Jane Smith',
        'amount' => 100.00,
        'status' => 'pending'
    ]);

    // expected balance calculation:
    // Expected = Opening (500) + POS Sales (0) + Manual Income (150) - Expense (50) = 600.00
    // If Cash in Hand = 480.00
    // Actual Balance = Cash in Hand (480) + Accounts Receivable (100) = 580.00
    // Discrepancy = Expected (600) - Actual (580) = 20.00

    $response = $this->actingAs($owner)
        ->post(route('reconciliation.close', $register->id), [
            'cash_in_hand' => 480.00
        ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('cash_registers', [
        'id' => $register->id,
        'status' => 'closed',
        'expected_balance' => 600.00,
        'actual_balance' => 580.00,
        'cash_in_hand' => 480.00,
        'discrepancy' => 20.00
    ]);
});

test('closed registers are frozen and cannot receive new entries', function () {
    $owner = User::factory()->create(['role' => 'owner']);
    $shop = Shop::create([
        'name' => 'Test Shop 6',
        'user_id' => $owner->id
    ]);

    $register = CashRegister::create([
        'shop_id' => $shop->id,
        'user_id' => $owner->id,
        'opening_balance' => 500.00,
        'expected_balance' => 500.00,
        'actual_balance' => 500.00,
        'cash_in_hand' => 500.00,
        'status' => 'closed',
        'opened_at' => now()->subHours(2),
        'closed_at' => now(),
    ]);

    // Try to log manual transaction
    $response = $this->actingAs($owner)
        ->post(route('ledger.transaction'), [
            'register_id' => $register->id,
            'type' => 'income',
            'category' => 'Other Income',
            'amount' => 50.00
        ]);

    $response->assertSessionHas('error');
    $this->assertDatabaseCount('ledger_transactions', 0);
});

test('creating a sale automatically creates a ledger transaction if register is open', function () {
    $owner = User::factory()->create(['role' => 'owner']);
    $shop = Shop::create([
        'name' => 'Test Shop 7',
        'user_id' => $owner->id
    ]);

    $register = CashRegister::create([
        'shop_id' => $shop->id,
        'user_id' => $owner->id,
        'opening_balance' => 100.00,
        'status' => 'open',
        'opened_at' => now(),
    ]);

    // Mock plan service because SaleController checks plan limits
    $this->mock(\App\Services\PlanService::class, function ($mock) {
        $mock->shouldReceive('canCreate')->andReturn(true);
    });

    $product = \Modules\Product\Models\Product::create([
        'name' => 'Test Product',
        'shop_id' => $shop->id,
        'purchase_price' => 10.00,
        'sale_price' => 15.00,
        'stock_quantity' => 10,
    ]);

    $batch = \Modules\Product\Models\ProductBatch::create([
        'product_id' => $product->id,
        'shop_id' => $shop->id,
        'batch_code' => 'B001',
        'purchase_price' => 10.00,
        'initial_quantity' => 10,
        'remaining_quantity' => 10,
        'batch_date' => now(),
    ]);

    // Create a sale via controller endpoint
    $response = $this->actingAs($owner)
        ->post(route('sale.quick-sale'), [
            'shop_id' => $shop->id,
            'product_id' => $product->id,
            'product_batch_id' => $batch->id,
            'sale_price' => 15.00,
            'quantity' => 2,
            'customer_name' => 'John Client',
        ]);

    $sale = Sale::first();
    $this->assertNotNull($sale);

    // Verify a corresponding LedgerTransaction was auto-created
    $this->assertDatabaseHas('ledger_transactions', [
        'register_id' => $register->id,
        'type' => 'income',
        'category' => 'POS Sale',
        'amount' => 30.00,
        'sale_id' => $sale->id,
    ]);
});

