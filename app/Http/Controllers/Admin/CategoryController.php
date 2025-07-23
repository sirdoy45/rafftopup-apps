<?php

namespace App\Http\Controllers\Admin;

use App\Models\Type;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\CategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {   
        if (request()->ajax()) {
            $query = Category::with('type');

            return DataTables::of($query)
                ->addColumn('action', function ($item) {
                    return '
                        <div class="btn-group">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">
                                    Aksi
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="' . route('category.edit', $item->id) . '">Sunting</a>
                                    <form action="' . route('category.destroy', $item->id) . '" method="POST" onsubmit="return confirm(\'Yakin ingin menghapus?\');">
                                        ' . method_field('delete') . csrf_field() . '
                                        <button class="dropdown-item text-danger" type="submit">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    ';
                })
                ->editColumn('photo', function ($item) {
                    return $item->photo 
                        ? '<img src="' . url('public/assets/category/' . $item->photo) . '" style="max-height: 40px; max-width: 60px; object-fit: cover;" class="rounded"/>'
                        : '<span class="text-muted">Tidak ada gambar</span>';
                })
                ->addColumn('type', function ($item) {
                    return $item->type->name ?? '-';
                })
                ->rawColumns(['action', 'photo'])
                ->make();
        }

        return view('pages.admin.category.index');
    }

    public function create()
    {
        $types = \App\Models\Type::all(); // ambil semua type

        return view('pages.admin.category.create', [
            'types' => $types,
        ]);
    }

    public function store(CategoryRequest $request)
    {
        $request->validate([
            'name' => 'required|string',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'type_id' => 'required|exists:types,id',
        ]);

        // Simpan file foto langsung ke folder public/assets/category
        $file = $request->file('photo');
        $fileName = time() . '-' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
        
        // PERBAIKAN: Simpan ke public/assets/category (BUKAN public/public/assets/category)
        $publicPath = public_path('assets/category');
        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0755, true);
        }
        
        // Pindahkan file ke public/assets/category
        $file->move($publicPath, $fileName);
        
        // Simpan hanya nama file saja
        Category::create([
            'name' => $request->name,
            'photo' => $fileName, // Hanya nama file: 'filename.png'
            'slug' => Str::slug($request->name),
            'type_id' => $request->type_id,
        ]);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $item = Category::findOrFail($id);
        $types = \App\Models\Type::all(); // ambil semua type

        return view('pages.admin.category.edit', [
            'item' => $item,
            'types' => $types,
        ]);
    }

    public function update(CategoryRequest $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'type_id' => 'required|exists:types,id',
        ]);

        $item = Category::findOrFail($id);

        // Data dasar yang akan diupdate
        $updateData = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type_id' => $request->type_id,
        ];

        // Jika ada file foto yang diupload
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($item->photo) {
                $oldPhotoPath = public_path('assets/category/' . $item->photo);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }

            // Simpan file foto baru
            $file = $request->file('photo');
            $fileName = time() . '-' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            
            // PERBAIKAN: Simpan ke public/assets/category (BUKAN public/public/assets/category)
            $publicPath = public_path('assets/category');
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            
            // Pindahkan file ke public/assets/category
            $file->move($publicPath, $fileName);
            
            // Simpan hanya nama file
            $updateData['photo'] = $fileName;
        }

        $item->update($updateData);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $item = Category::findOrFail($id);

        if ($item->photo) {
            Storage::delete('public/' . $item->photo);
        }

        $item->delete();

        return redirect()->route('category.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
