<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Modules\Category\Models\Category;
use Modules\Product\Models\ProductDynamicField;

class ProductDynamicFieldController extends Controller
{
    public function index()
    {
        $fields = ProductDynamicField::query()
            ->with('category:id,name')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(20);

        return view('product::dynamic-fields.index', compact('fields'));
    }

    public function create()
    {
        $categories = Category::query()->orderBy('name')->get(['id', 'name']);
        $inputTypes = array_values(array_filter(
            ProductDynamicField::INPUT_TYPES,
            static fn (string $type) => $type !== 'select'
        ));

        return view('product::dynamic-fields.create', compact('categories', 'inputTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'field_key' => $this->generateFieldKey(
                (string) $request->input('label', ''),
                $request->input('category_id')
            ),
        ]);

        $validated = $this->validateRequest($request);

        ProductDynamicField::create($validated);

        return redirect()
            ->route('product.dynamic-fields.index')
            ->with('success', __('product.dynamic_field_created'));
    }

    public function edit(int $id)
    {
        $field = ProductDynamicField::findOrFail($id);
        $categories = Category::query()->orderBy('name')->get(['id', 'name']);
        $inputTypes = ProductDynamicField::INPUT_TYPES;
        $optionsText = implode("\n", $field->options ?? []);

        return view('product::dynamic-fields.edit', compact('field', 'categories', 'inputTypes', 'optionsText'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $field = ProductDynamicField::findOrFail($id);
        $validated = $this->validateRequest($request, $field);

        $field->update($validated);

        return redirect()
            ->route('product.dynamic-fields.index')
            ->with('success', __('product.dynamic_field_updated'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $field = ProductDynamicField::findOrFail($id);
        $field->delete();

        return redirect()
            ->route('product.dynamic-fields.index')
            ->with('success', __('product.dynamic_field_deleted'));
    }

    /**
     * @throws ValidationException
     */
    private function validateRequest(Request $request, ?ProductDynamicField $field = null): array
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'label' => 'required|string|max:120',
            'field_key' => [
                'required',
                'string',
                'max:80',
                'alpha_dash',
                Rule::unique('product_dynamic_fields', 'field_key')
                    ->where(fn ($query) => $query->where('category_id', $request->input('category_id')))
                    ->whereNull('deleted_at')
                    ->ignore($field?->id),
            ],
            'input_type' => ['required', Rule::in(ProductDynamicField::INPUT_TYPES)],
            'placeholder' => 'nullable|string|max:255',
            'help_text' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_required' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'options_text' => 'nullable|string',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_required'] = $request->boolean('is_required');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['options'] = null;

        if ($validated['input_type'] === 'select') {
            $validated['options'] = $this->parseOptions($validated['options_text'] ?? '');

            if (empty($validated['options'])) {
                throw ValidationException::withMessages([
                    'options_text' => __('product.dynamic_options_required'),
                ]);
            }
        }

        unset($validated['options_text']);

        return $validated;
    }

    private function parseOptions(string $optionsText): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $optionsText) ?: [])
            ->map(static fn ($item) => trim($item))
            ->filter(static fn ($item) => $item !== '')
            ->values()
            ->all();
    }

    private function generateFieldKey(string $label, mixed $categoryId = null): string
    {
        $baseKey = Str::slug($label, '_');
        $baseKey = $baseKey !== '' ? $baseKey : 'field';

        $fieldKey = $baseKey;
        $suffix = 2;

        while (ProductDynamicField::query()
            ->whereNull('deleted_at')
            ->where('category_id', $categoryId)
            ->where('field_key', $fieldKey)
            ->exists()) {
            $fieldKey = $baseKey . '_' . $suffix;
            $suffix++;
        }

        return $fieldKey;
    }
}
