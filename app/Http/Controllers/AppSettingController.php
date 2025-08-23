<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Helpers\ImageResizeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AppSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display the settings form
     */
    public function index()
    {
        $settings = AppSetting::first();
        return view('app-settings.index', compact('settings'));
    }

    /**
     * Update the settings
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_name' => 'required|string|max:255',
            'pharmacy_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'tax_number' => 'nullable|string|max:50',
            'license_number' => 'nullable|string|max:50',
            'owner_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg|max:1024',
            'currency' => 'required|string|max:10',
            'timezone' => 'required|string|max:50',
            'maintenance_mode' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $settings = AppSetting::firstOrCreate([]);

        $data = $request->except(['logo', 'favicon']);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = ImageResizeHelper::resizeLogo($request->file('logo'), $settings->logo_path);
            if ($logoPath) {
                $data['logo_path'] = $logoPath;
            }
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $faviconPath = ImageResizeHelper::resizeFavicon($request->file('favicon'), $settings->favicon_path);
            if ($faviconPath) {
                $data['favicon_path'] = $faviconPath;
            }
        }

        $settings->update($data);

        // Clear any existing flash messages to prevent duplication
        $request->session()->forget(['success', 'error', 'warning', 'info']);

        return redirect()->route('app-settings.index')
            ->with('success', 'Pengaturan berhasil diperbarui!');
    }

    /**
     * Get settings for API
     */
    public function getSettings()
    {
        $settings = AppSetting::first();
        return response()->json($settings);
    }
}
