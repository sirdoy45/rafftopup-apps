<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Category;
use App\Models\Type;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
     /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    public function index()
    {
        $title = 'All Items';
        $selectedCategory = null;
        $typeName = null;

        if (request('category')) {
            $selectedCategory = Category::with('type')->where('slug', request('category'))->firstOrFail();
            $title = 'Item in ' . $selectedCategory->name;
            $typeName = $selectedCategory->type->name;
        }

        $categories = []; // kosongkan list karena kita tidak mau tampilkan kategori lain

        $products = Product::with(['galleries','user'])
            ->filter(request(['search','category']))
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('pages.category', [
            'categories' => $categories, // list kosong
            'products' => $products,
            'title' => $title,
            'typeName' => $typeName, // untuk ditampilkan di sidebar
        ]);
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function detail(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $categories = []; // Kosongkan, karena sidebar tidak menampilkan semua kategori

        $products = Product::with(['galleries'])
            ->where('categories_id', $category->id)
            ->latest()
            ->paginate(8);

        return view('pages.category', [
            'categories' => $categories,
            'products' => $products,
            'title' => 'Item in ' . $category->name,
            'typeName' => $category->type->name ?? null
        ]);
    }

    public function type()
    {
        $types = Type::all(); // Ambil semua tipe: Game & Pulsa
        return view('pages.type', compact('types'));
    }

    public function byType($slug)
    {
        $type = Type::where('slug', $slug)->firstOrFail();
        $categories = $type->categories; // ambil subkategori

        // Ambil ID dari semua kategori di tipe ini
        $categoryIds = $categories->pluck('id');

        // Ambil produk yang termasuk dalam kategori ini
        $products = Product::with(['galleries', 'user'])
            ->whereIn('categories_id', $categoryIds)
            ->latest()
            ->paginate(8);

        $typeName = $type->name;

        return view('pages.category-by-type', compact('type', 'categories', 'products', 'typeName'));
    }

}

