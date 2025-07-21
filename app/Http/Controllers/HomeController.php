<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Type;
use App\Models\Review;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman utama.
     */
    public function index()
    {
        // Ambil semua type beserta relasi kategorinya
        $types = Type::with('categories')->get();

        // Ambil produk terbaru
        $products = Product::with(['galleries'])
            ->latest()
            ->take(8)
            ->get();

        return view('pages.home', [
            'types' => $types,         // yang digunakan untuk tampilkan kategori berdasarkan type
            'products' => $products,   // tetap digunakan untuk bagian "New Items"
        ]);
    }

    /**
     * Tampilkan halaman toko berdasarkan slug.
     */
    public function storeProfile($slugSeller)
    {
        $seller = User::where('store_slug', $slugSeller)->firstOrFail();

        $productIds = Product::where('users_id', $seller->id)->pluck('id');

        $productsSeller = Product::with(['galleries', 'user', 'category'])
            ->where('users_id', $seller->id)
            ->get();

        $reviews = Review::with(['user', 'product'])
            ->whereIn('products_id', $productIds)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRating = $reviews->sum('rate');
        $reviewCount = $reviews->count();
        $averageRating = $reviewCount > 0 ? $totalRating / $reviewCount : 0;
        $roundedRating = round($averageRating, 1);

        return view('pages.store-home', [
            'seller' => $seller,
            'productsSeller' => $productsSeller,
            'reviews' => $reviews,
            'reviewCount' => $reviewCount,
            'roundedRating' => $roundedRating,
        ]);
    }
}
