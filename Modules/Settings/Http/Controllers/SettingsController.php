<?php

namespace Modules\Settings\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Settings\Models\Setting;

class SettingsController extends Controller
{
    private const GROUPS = ['general', 'system'];

    /**
     * Display the settings form
     */
    public function index()
    {
        return redirect()->route('settings.general');
    }

    public function general()
    {
        return $this->showByGroup('general');
    }

    public function business()
    {
        return redirect()->route('settings.general');
    }

    public function system()
    {
        return $this->showByGroup('system');
    }

    private function showByGroup(string $group)
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        $activeGroup = $this->normalizeGroup($group);

        return view('settings::index', compact('settings', 'activeGroup'));
    }

    /**
     * Update settings
     */
    public function update(Request $request, ?string $group = null)
    {
        $activeGroup = $this->normalizeGroup($group);

        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string|max:500',
        ]);

        try {
            foreach ($validated['settings'] as $key => $value) {
                $setting = Setting::where('key', $key)->first();

                if ($setting) {
                    $setting->update(['value' => $value ?? '']);
                }
            }

            // Clear cache after updating
            Setting::clearCache();

            return redirect()->route('settings.' . $activeGroup)
                ->with('success', __('settings.updated'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => __('settings.update_failed')]);
        }
    }

    /**
     * Clear settings cache
     */
    public function clearCache(?string $group = null)
    {
        $activeGroup = $this->normalizeGroup($group);

        try {
            Setting::clearCache();

            return redirect()->route('settings.' . $activeGroup)
                ->with('success', __('settings.cache_cleared'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => __('settings.cache_clear_failed')]);
        }
    }

    private function normalizeGroup(?string $group): string
    {
        if ($group === 'business') {
            return 'general';
        }

        return in_array($group, self::GROUPS, true) ? $group : 'general';
    }
}
