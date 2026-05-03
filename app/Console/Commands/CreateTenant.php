<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create {name} {--domain=} {--email=} {--description=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new tenant';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $slug = Str::slug($name);
        $domain = $this->option('domain');
        $email = $this->option('email');
        $description = $this->option('description');

        // Check if tenant already exists
        if (Tenant::where('slug', $slug)->exists()) {
            $this->error("Tenant with slug '{$slug}' already exists.");
            return 1;
        }

        // Create the tenant
        $tenant = Tenant::create([
            'name' => $name,
            'slug' => $slug,
            'domain' => $domain,
            'email' => $email,
            'description' => $description,
            'is_active' => true,
        ]);

        $this->info("Tenant '{$name}' created successfully!");
        $this->line("Tenant ID: {$tenant->id}");
        $this->line("Tenant Slug: {$tenant->slug}");

        if ($domain) {
            $this->line("Domain: {$domain}");
        }

        return 0;
    }
}
