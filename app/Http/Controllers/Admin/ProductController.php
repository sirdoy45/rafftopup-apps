<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Http\Requests\Admin\ProductRequest;

class ProductController extends Controller
{
    public function index()
    {   
        if (request()->ajax()) {
            $query = Product::with(['user', 'category']);

            return DataTables::of($query)
                ->addColumn('provider', function($item) {
                    return $item->provider ?? '-';
                })
                ->addColumn('action', function($item) {
                    return '
                        <div class="btn-group">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">
                                    Aksi
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="' . route('product.edit', $item->id) . '">
                                        Sunting
                                    </a>
                                    <form action="' . route('product.destroy', $item->id) . '" method="POST">
                                        ' . method_field('delete') . csrf_field() . '
                                        <button type="submit" class="dropdown-item text-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make();
        }

        return view('pages.admin.product.index');
    }

    public function create()
    {
        $categories = Category::all();

        return view('pages.admin.product.create', [
            'categories' => $categories
        ]);
    }

    public function store(ProductRequest $request)
    {
        Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'provider' => $request->provider,
            'users_id' => auth()->user()->id, // ambil ID user yang sedang login
            'categories_id' => $request->categories_id,
            'kode_produk' => $request->kode_produk,
            'price' => $request->price,
            'status' => $request->status,
            'description' => $request->description,
            'input_type' => $request->input_type,
        ]);

        return redirect()->route('product.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $item = Product::findOrFail($id);
        $categories = Category::all();

        return view('pages.admin.product.edit', [
            'item' => $item,
            'categories' => $categories
        ]);
    }

    public function update(ProductRequest $request, $id)
    {
        $item = Product::findOrFail($id);

        $item->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'provider' => $request->provider,
            'users_id' => auth()->user()->id,
            'categories_id' => $request->categories_id,
            'kode_produk' => $request->kode_produk,
            'price' => $request->price,
            'status' => $request->status,
            'description' => $request->description,
            'input_type' => $request->input_type,
        ]);

        return redirect()->route('product.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = Product::findOrFail($id);
        $item->delete();

        return redirect()->route('product.index')->with('success', 'Produk berhasil dihapus.');
    }
}
