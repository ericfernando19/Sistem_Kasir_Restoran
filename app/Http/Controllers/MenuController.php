<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('is_available') && $request->is_available !== '') {
            $query->where('is_available', $request->is_available);
        }

        $menus = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('menus.index', compact('menus', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $lastMenu = Product::latest()->first();
        $nextCode = 'MNU-'.str_pad(($lastMenu ? $lastMenu->id + 1 : 1), 4, '0', STR_PAD_LEFT);

        return view('menus.form', compact('categories', 'nextCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'code' => 'required|string|max:50|unique:products',
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'selling_price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_available' => 'boolean',
        ]);

        $validated['purchase_price'] = $validated['selling_price'];
        $validated['stock'] = 0;
        $validated['unit'] = 'pcs';

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('menus.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Product $menu)
    {
        $categories = Category::all();

        return view('menus.form', compact('menu', 'categories'));
    }

    public function update(Request $request, Product $menu)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'code' => 'required|string|max:50|unique:products,code,'.$menu->id,
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'selling_price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_available' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($menu->photo) {
                Storage::disk('public')->delete($menu->photo);
            }
            $validated['photo'] = $request->file('photo')->store('products', 'public');
        }

        $menu->update($validated);

        $page = $request->page ?? 1;

        return redirect()->route('menus.index', ['page' => $page])
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Product $menu)
    {
        if ($menu->photo) {
            Storage::disk('public')->delete($menu->photo);
        }
        $menu->delete();

        $page = request('page', 1);

        return redirect()->route('menus.index', ['page' => $page])
            ->with('success', 'Menu berhasil dihapus.');
    }

    public function toggleAvailability(Product $menu)
    {
        $menu->update(['is_available' => !$menu->is_available]);

        $status = $menu->fresh()->is_available ? 'tersedia' : 'tidak tersedia';
        $page = request('page', 1);

        return redirect()->route('menus.index', ['page' => $page])
            ->with('success', "Menu {$menu->name} kini {$status}.");
    }
}
