<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        Page::create($validated);

        return redirect()->back()->with('success', 'Page created successfully');
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,'.$page->id,
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $page->update($validated);

        return redirect()->back()->with('success', 'Page updated successfully');
    }

    public function destroy($id)
    {
        Page::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Page deleted successfully');
    }

    public function show($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return Inertia::render('Page', [
            'page' => $page,
        ]);
    }
}
