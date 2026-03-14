<?php

namespace Modules\Settings\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Settings\Models\Setting;

class SettingsController extends Controller
{
    /**
     * Display the settings form
     */
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');

        return view('settings::index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
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

            return redirect()->route('settings.index')
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
    public function clearCache()
    {
        try {
            Setting::clearCache();

            return redirect()->route('settings.index')
                ->with('success', __('settings.cache_cleared'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => __('settings.cache_clear_failed')]);
        }
    }
}
