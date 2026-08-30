<?php

namespace App\Http\Controllers;

use App\Models\FrontendContent;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class FrontendCmsController extends Controller
{
    public function index()
    {
        $contents = FrontendContent::all()->groupBy('section')->map(function ($items) {
            return $items->keyBy('key')->map(function ($item) {
                return $item->parsed_value;
            });
        });

        $pages = Page::all();
        $items = MenuItem::with(['children', 'parent'])->orderBy('sort_order', 'asc')->get();
        $parentItems = MenuItem::whereNull('parent_id')->orderBy('sort_order', 'asc')->get();

        return Inertia::render('Admin/Cms/Index', [
            'cmsData' => $contents,
            'pagesData' => $pages,
            'menuItems' => $items,
            'parentMenuItems' => $parentItems,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        $uploadsDir = public_path('uploads/cms');
        if (! File::exists($uploadsDir)) {
            File::makeDirectory($uploadsDir, 0755, true);
        }

        foreach ($data as $section => $fields) {
            if (! is_array($fields)) {
                continue;
            }

            foreach ($fields as $key => $value) {
                // Handle File Uploads
                if ($request->hasFile("$section.$key")) {
                    $file = $request->file("$section.$key");

                    $extension = strtolower($file->getClientOriginalExtension());

                    if (in_array($extension, ['mp4', 'webm', 'mov'])) {
                        // Video file - move as is
                        $filename = "{$section}_{$key}_".time().".{$extension}";
                        $file->move($uploadsDir, $filename);
                        $filePath = '/uploads/cms/'.$filename;
                        $type = 'video';
                    } else {
                        // Image file - compress and convert to webp (unless it's an ico file for favicon)
                        $isFavicon = str_contains($key, 'favicon') || $extension === 'ico';

                        if ($isFavicon) {
                            $filename = "{$section}_{$key}_".time().".{$extension}";
                            $file->move($uploadsDir, $filename);
                            $filePath = '/uploads/cms/'.$filename;
                            $type = 'image';
                        } else {
                            $filename = "{$section}_{$key}_".time().'.webp';
                            $fullPath = $uploadsDir.'/'.$filename;

                            $manager = new ImageManager(new Driver);
                            $image = $manager->decode($file->getRealPath());

                            // Scale down if width > 1920
                            $image->scaleDown(width: 1920);

                            // Save as WebP with 80% quality
                            $image->save($fullPath, 80);

                            $filePath = '/uploads/cms/'.$filename;
                            $type = 'image';
                        }
                    }

                    FrontendContent::updateOrCreate(
                        ['section' => $section, 'key' => $key],
                        [
                            'type' => $type,
                            'value' => $filePath,
                            'is_file' => true,
                        ]
                    );

                    continue;
                }

                // Handle string/json values
                if ($value !== null && ! $request->hasFile("$section.$key")) {
                    $type = is_array($value) ? 'json' : 'text';
                    $valToSave = is_array($value) ? json_encode($value) : (string) $value;

                    // If the field is marked as a file in the DB, and the string is a URL (e.g., from an existing file), we don't change it unless it's a new file.
                    // But if it's a string from the form (maybe empty to remove, or text), we save it.
                    $content = FrontendContent::where('section', $section)->where('key', $key)->first();
                    if ($content && $content->is_file && is_string($value) && (str_starts_with($value, '/uploads/') || str_starts_with($value, 'http'))) {
                        // Value hasn't changed (it's the old path string sent back), skip
                        continue;
                    }

                    FrontendContent::updateOrCreate(
                        ['section' => $section, 'key' => $key],
                        [
                            'type' => $type,
                            'value' => $valToSave,
                            'is_file' => false,
                        ]
                    );
                }
            }
        }

        return redirect()->back()->with('success', 'CMS Settings updated successfully!');
    }
}
