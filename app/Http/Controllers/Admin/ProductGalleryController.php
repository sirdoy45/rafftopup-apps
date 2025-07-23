<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\ProductGallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


use App\Http\Requests\Admin\ProductGalleryRequest;


class ProductGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        if(request()->ajax())
        {
            $query = ProductGallery::with(['product']);

            return Datatables::of($query)
                ->addColumn('action', function($item) {
                    return '
                        <div class="btn-group">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">
                                Aksi
                                </button>
                                <div class="dropdown-menu">
                                    <form action="'. route('product-gallery.destroy', $item->id) .'" method="POST">
                                    '.  method_field('delete') . csrf_field() . '
                                    <button type="submit" class="dropdown-item text-danger">
                                    Hapus
                                    </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    ';
                })
                ->editColumn('photos', function($item){
                    // Update: Gunakan asset() untuk public/assets/product
                    return $item->photos ? '<img src="'. asset('assets/product/' . $item->photos) .'" style="max-height: 80px;" />' : '';
                })
                ->rawColumns(['action', 'photos'])
                ->make();
                ;
        }

        return view('pages.admin.product-gallery.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::all();

        return view('pages.admin.product-gallery.create', [
            'products' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(/* Request */ProductGalleryRequest $request)
    {
        $data = $request->only(['product_id']);

        // Update: Simpan ke public/assets/product seperti category
        if ($request->hasFile('photos')) {
            $file = $request->file('photos');
            $filename = time() . '-' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            
            // Pindahkan file ke public/assets/product
            $file->move(public_path('assets/product'), $filename);
            
            // Simpan hanya nama file ke database (tanpa path)
            $data['photos'] = $filename;
        }

        ProductGallery::create($data);

        return redirect()->route('product-gallery.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(/* Request */ProductGalleryRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = ProductGallery::findOrFail($id);
        
        // Update: Hapus file dari public/assets/product
        if ($item->photos) {
            $file_path = public_path('assets/product/' . $item->photos);
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        $item->delete();

        return redirect()->route('product-gallery.index');
    }
}