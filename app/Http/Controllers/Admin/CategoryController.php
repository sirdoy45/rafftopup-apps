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
            $query = Category::with('type'); // Optional jika ingin menampilkan tipe di datatable

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
                        ? '<img src="' . asset($item->photo) . '" style="max-height: 40px;"/>'
                        : 'Tidak ada gambar';
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

        // Simpan file foto ke storage
        $file = $request->file('photo');
        $fileName = time() . '-' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
        
        // Simpan file ke storage/app/public/assets/category
        $file->storeAs('public/assets/category', $fileName);
        
        // Simpan path yang bisa diakses dengan asset() melalui symbolic link
        $photoPath = 'storage/assets/category/' . $fileName;

        // Simpan data ke database
        Category::create([
            'name' => $request->name,
            'photo' => $photoPath, // Simpan 'storage/assets/category/filename.png'
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
                // Konversi path untuk Storage::delete()
                $oldPath = str_replace('storage/', 'public/', $item->photo);
                Storage::delete($oldPath);
            }

            // Simpan file foto baru
            $file = $request->file('photo');
            $fileName = time() . '-' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            
            // Simpan ke storage
            $file->storeAs('public/assets/category', $fileName);
            
            // Simpan path yang bisa diakses dengan asset()
            $updateData['photo'] = 'storage/assets/category/' . $fileName;
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
