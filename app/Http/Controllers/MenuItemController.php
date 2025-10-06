<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    /**
     * Show the form for creating a new menu item.
     */
    public function create(Menu $menu)
    {
        $pages = Page::published()->select('id', 'title', 'slug')->get();
        $menuItems = $menu->menuItems()->orderBy('sort_order')->get();

        return view('admin.cms.menus.items.create', compact('menu', 'pages', 'menuItems'));
    }

    /**
     * Store a newly created menu item.
     */
    public function store(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'page_id' => 'nullable|exists:pages,id',
            'description' => 'nullable|string',
            'icon_class' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'is_active' => 'boolean',
            'target' => 'nullable|string|in:_self,_blank,_parent,_top',
            'url_type' => 'required|string|in:custom,page',
            'item_type' => 'required|string|in:main,sub',
        ]);

        // Handle URL based on type
        if ($validated['url_type'] === 'page') {
            $validated['url'] = null; // Clear custom URL
            if (empty($validated['page_id'])) {
                return response()->json(['success' => false, 'message' => 'Page selection is required for page links'], 422);
            }
        } else {
            $validated['page_id'] = null; // Clear page selection
            if (empty($validated['url'])) {
                return response()->json(['success' => false, 'message' => 'URL is required for custom links'], 422);
            }
        }

        // Handle parent for sub items
        if ($validated['item_type'] === 'sub') {
            if (empty($validated['parent_id'])) {
                return response()->json(['success' => false, 'message' => 'Parent menu item is required for sub items'], 422);
            }
        } else {
            $validated['parent_id'] = null; // Clear parent for main items
        }

        // Set default values
        $validated['menu_id'] = $menu->id;
        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['sort_order'] = $this->getNextSortOrder($menu->id, $validated['parent_id']);

        // Remove helper fields
        unset($validated['url_type'], $validated['item_type']);

        MenuItem::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Menu item created successfully']);
        }

        return redirect()->route('admin.cms.menus.show', $menu)
                        ->with('success', 'Menu item created successfully.');
    }

    /**
     * Get the next sort order for menu items
     */
    private function getNextSortOrder($menuId, $parentId = null)
    {
        return MenuItem::where('menu_id', $menuId)
                      ->where('parent_id', $parentId)
                      ->max('sort_order') + 1;
    }

    /**
     * Show the form for editing the specified menu item.
     */
    public function edit(MenuItem $item)
    {
        // Check if it's an AJAX request or if JSON is expected
        if (request()->ajax() || request()->expectsJson() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'menuItem' => [
                    'id' => $item->id,
                    'title' => $item->title,
                    'description' => $item->description,
                    'url' => $item->url,
                    'page_id' => $item->page_id,
                    'icon_class' => $item->icon_class,
                    'target' => $item->target,
                    'is_active' => $item->is_active,
                    'parent_id' => $item->parent_id,
                    'sort_order' => $item->sort_order,
                ]
            ]);
        }

        // For non-AJAX requests, redirect to the menu show page
        return redirect()->route('admin.cms.menus.show', $item->menu);
    }

    /**
     * Update the specified menu item.
     */
    public function update(Request $request, MenuItem $item)
    {
        // Debug: Log the incoming request data
        \Illuminate\Support\Facades\Log::info('Update MenuItem Request Data:', $request->all());

        // Simple validation first
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'nullable|string|max:255',
            'page_id' => 'nullable|integer',
            'icon_class' => 'nullable|string|max:255',
            'parent_id' => 'nullable|integer',
            'target' => 'nullable|string',
            'is_active' => 'nullable'
        ]);

        // Handle the data conversion
        $updateData = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? '',
            'url' => $validated['url'] ?? null,
            'page_id' => $validated['page_id'] ?? null,
            'icon_class' => $validated['icon_class'] ?? '',
            'parent_id' => $validated['parent_id'] ?? null,
            'target' => $validated['target'] ?: '_self', // Default to '_self' if empty
            'is_active' => isset($validated['is_active']) ? (bool)$validated['is_active'] : true,
        ];

        // Clear conflicting fields based on URL type if provided
        if ($request->has('url_type')) {
            if ($request->url_type === 'page') {
                $updateData['url'] = null;
            } else {
                $updateData['page_id'] = null;
            }
        }

        // Handle parent for sub items
        if ($request->has('item_type') && $request->item_type === 'main') {
            $updateData['parent_id'] = null;
        }

        // Debug: Log the data being updated
        \Illuminate\Support\Facades\Log::info('MenuItem Update Data:', $updateData);

        $item->update($updateData);

        // Always return JSON for AJAX requests
        if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Menu item updated successfully']);
        }

        return redirect()->route('admin.cms.menus.show', $item->menu)
                        ->with('success', 'Menu item updated successfully.');
    }

    /**
     * Check if a menu item is a descendant of another
     */
    private function isDescendant(MenuItem $item, $potentialAncestorId)
    {
        $current = $item;
        while ($current->parent_id) {
            if ($current->parent_id == $potentialAncestorId) {
                return true;
            }
            $current = $current->parent;
        }
        return false;
    }

    /**
     * Remove the specified menu item.
     */
    public function destroy(Menu $menu, MenuItem $menuItem)
    {
        // Ensure menu item belongs to the menu
        if ($menuItem->menu_id !== $menu->id) {
            abort(404);
        }

        // Move children to parent
        $menuItem->children()->update(['parent_id' => $menuItem->parent_id]);

        $menuItem->delete();

        return redirect()->route('admin.cms.menus.show', $menu)
                        ->with('success', 'Menu item deleted successfully.');
    }

    /**
     * Reorder menu items via AJAX
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.sort_order' => 'required|integer|min:0',
            'items.*.parent_id' => 'nullable|exists:menu_items,id'
        ]);

        foreach ($request->items as $itemData) {
            MenuItem::where('id', $itemData['id'])->update([
                'sort_order' => $itemData['sort_order'],
                'parent_id' => $itemData['parent_id'] ?? null
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Menu items reordered successfully']);
    }
}