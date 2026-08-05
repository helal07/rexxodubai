<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    /**
     * Get all settings as a flat key-value array.
     */
    public function index()
    {
        $settings = Cache::remember('global_site_settings', 3600, function () {
            return Setting::all()->pluck('value', 'key')->toArray();
        });

        return response()->json($settings);
    }

    /**
     * Update multiple settings including image/favicon file uploads.
     */
    public function store(Request $request)
    {
        $uploadsDir = public_path('uploads/settings');
        if (!File::exists($uploadsDir)) {
            File::makeDirectory($uploadsDir, 0755, true);
        }

        $logoUploaded = false;
        $faviconUploaded = false;
        $videoUploaded = false;
        $posterUploaded = false;

        // Handle Site Logo File Upload
        if ($request->hasFile('logo_file') || $request->hasFile('site_logo')) {
            $file = $request->file('logo_file') ?? $request->file('site_logo');
            $request->validate([
                'logo_file' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:5120',
                'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:5120',
            ]);

            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadsDir, $filename);
            $logoPath = '/uploads/settings/' . $filename;

            Setting::updateOrCreate(['key' => 'logo_url'], ['value' => $logoPath]);
            Setting::updateOrCreate(['key' => 'site_logo'], ['value' => $logoPath]);
            $logoUploaded = true;
        }

        // Handle Favicon File Upload
        if ($request->hasFile('favicon_file') || $request->hasFile('site_favicon')) {
            $file = $request->file('favicon_file') ?? $request->file('site_favicon');
            $request->validate([
                'favicon_file' => 'nullable|file|mimes:ico,png,svg,jpg,jpeg,webp|max:2048',
                'site_favicon' => 'nullable|file|mimes:ico,png,svg,jpg,jpeg,webp|max:2048',
            ]);

            $filename = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadsDir, $filename);
            $faviconPath = '/uploads/settings/' . $filename;

            Setting::updateOrCreate(['key' => 'favicon_url'], ['value' => $faviconPath]);
            Setting::updateOrCreate(['key' => 'site_favicon'], ['value' => $faviconPath]);
            $faviconUploaded = true;
        }

        // Handle Hero Background Video File Upload (.mp4, .webm, .mov, etc.)
        $videoUploadsDir = public_path('uploads/videos');
        if (!File::exists($videoUploadsDir)) {
            File::makeDirectory($videoUploadsDir, 0755, true);
        }

        if ($request->hasFile('hero_video_file') || ($request->hasFile('hero_video') && $request->file('hero_video') instanceof \Illuminate\Http\UploadedFile)) {
            $videoFile = $request->file('hero_video_file') ?? $request->file('hero_video');
            $request->validate([
                'hero_video_file' => 'nullable|file|mimes:mp4,webm,ogg,mov,m4v|max:51200',
                'hero_video' => 'nullable|file|mimes:mp4,webm,ogg,mov,m4v|max:51200',
            ]);

            $filename = 'hero_clip_' . time() . '.' . $videoFile->getClientOriginalExtension();
            $videoFile->move($videoUploadsDir, $filename);
            $videoPath = '/uploads/videos/' . $filename;

            Setting::updateOrCreate(['key' => 'hero_video_url'], ['value' => $videoPath]);
            Setting::updateOrCreate(['key' => 'hero_video'], ['value' => $videoPath]);
            $videoUploaded = true;
        }

        // Handle Hero Poster Image Upload
        if ($request->hasFile('hero_poster_file') || $request->hasFile('hero_poster')) {
            $posterFile = $request->file('hero_poster_file') ?? $request->file('hero_poster');
            $request->validate([
                'hero_poster_file' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120',
                'hero_poster' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120',
            ]);

            $filename = 'hero_poster_' . time() . '.' . $posterFile->getClientOriginalExtension();
            $posterFile->move($uploadsDir, $filename);
            $posterPath = '/uploads/settings/' . $filename;

            Setting::updateOrCreate(['key' => 'hero_poster_url'], ['value' => $posterPath]);
            $posterUploaded = true;
        }

        // Handle settings sent as a nested 'settings' array or direct form inputs
        $incomingSettings = $request->input('settings', $request->except([
            '_token', 
            'logo_file', 'site_logo', 
            'favicon_file', 'site_favicon',
            'hero_video_file', 'hero_poster_file', 'hero_poster'
        ]));

        if (is_array($incomingSettings)) {
            foreach ($incomingSettings as $key => $value) {
                // If a file was uploaded for this field, don't overwrite with empty string
                if ($key === 'logo_url' && ($logoUploaded || empty($value))) {
                    if (!empty($value)) {
                        Setting::updateOrCreate(['key' => 'logo_url'], ['value' => (string)$value]);
                        Setting::updateOrCreate(['key' => 'site_logo'], ['value' => (string)$value]);
                    }
                    continue;
                }

                if ($key === 'favicon_url' && ($faviconUploaded || empty($value))) {
                    if (!empty($value)) {
                        Setting::updateOrCreate(['key' => 'favicon_url'], ['value' => (string)$value]);
                        Setting::updateOrCreate(['key' => 'site_favicon'], ['value' => (string)$value]);
                    }
                    continue;
                }

                if ($key === 'hero_video_url' && ($videoUploaded || empty($value))) {
                    if (!empty($value)) {
                        Setting::updateOrCreate(['key' => 'hero_video_url'], ['value' => (string)$value]);
                        Setting::updateOrCreate(['key' => 'hero_video'], ['value' => (string)$value]);
                    }
                    continue;
                }

                if ($key === 'hero_poster_url' && ($posterUploaded || empty($value))) {
                    if (!empty($value)) {
                        Setting::updateOrCreate(['key' => 'hero_poster_url'], ['value' => (string)$value]);
                    }
                    continue;
                }

                if ($value === null) {
                    $value = '';
                }

                // If hero_video was an uploaded file, skip string assignment
                if ($key === 'hero_video' && $request->hasFile('hero_video')) {
                    continue;
                }

                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => is_array($value) ? json_encode($value) : (string)$value]
                );

                // Ensure compatibility aliases
                if ($key === 'siteName') {
                    Setting::updateOrCreate(['key' => 'site_name'], ['value' => (string)$value]);
                } elseif ($key === 'site_name') {
                    Setting::updateOrCreate(['key' => 'siteName'], ['value' => (string)$value]);
                }

                if ($key === 'logo_url' && !empty($value)) {
                    Setting::updateOrCreate(['key' => 'site_logo'], ['value' => (string)$value]);
                }

                if ($key === 'favicon_url' && !empty($value)) {
                    Setting::updateOrCreate(['key' => 'site_favicon'], ['value' => (string)$value]);
                }

                if ($key === 'hero_video_url' && !empty($value)) {
                    Setting::updateOrCreate(['key' => 'hero_video'], ['value' => (string)$value]);
                } elseif ($key === 'hero_video' && !empty($value) && is_string($value)) {
                    Setting::updateOrCreate(['key' => 'hero_video_url'], ['value' => (string)$value]);
                }
            }
        }

        Cache::forget('global_site_settings');
        Cache::flush();

        $allSettings = Setting::all()->pluck('value', 'key')->toArray();

        return response()->json([
            'message' => 'Settings updated successfully',
            'settings' => $allSettings,
        ]);
    }
}
