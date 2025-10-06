<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    /**
     * Display a listing of menus.
     */
    public function index()
    {
        $menus = Menu::with('menuItems')->orderBy('sort_order')->get();

        return view('admin.cms.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new menu.
     */
    public function create()
    {
        return view('admin.cms.menus.create');
    }

    /**
     * Store a newly created menu.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|in:header,footer,sidebar',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Ensure unique slug
        $originalSlug = $validated['slug'];
        $count = 1;
        while (Menu::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count;
            $count++;
        }

        Menu::create($validated);

        return redirect()->route('admin.cms.menus.index')
                        ->with('success', 'Menu created successfully.');
    }

    /**
     * Display the specified menu.
     */
    public function show(Menu $menu)
    {
        $menu->load('menuItems.children');

        return view('admin.cms.menus.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified menu.
     */
    public function edit(Menu $menu)
    {
        return view('admin.cms.menus.edit', compact('menu'));
    }

    /**
     * Update the specified menu.
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|in:header,footer,sidebar',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Ensure unique slug (excluding current menu)
        $originalSlug = $validated['slug'];
        $count = 1;
        while (Menu::where('slug', $validated['slug'])->where('id', '!=', $menu->id)->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count;
            $count++;
        }

        $menu->update($validated);

        return redirect()->route('admin.cms.menus.index')
                        ->with('success', 'Menu updated successfully.');
    }

    /**
     * Remove the specified menu.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('admin.cms.menus.index')
                        ->with('success', 'Menu deleted successfully.');
    }

    /**
     * Update menu items order.
     */
    public function updateOrder(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:menu_items,id',
            'items.*.sort_order' => 'required|integer',
            'items.*.parent_id' => 'nullable|integer',
        ]);

        foreach ($validated['items'] as $itemData) {
            $menu->menuItems()
                 ->where('id', $itemData['id'])
                 ->update([
                     'sort_order' => $itemData['sort_order'],
                     'parent_id' => $itemData['parent_id'] ?? null,
                 ]);
        }

        return response()->json(['success' => true]);
    }
}