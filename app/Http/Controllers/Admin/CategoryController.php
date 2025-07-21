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
                        ? '<img src="' . asset('storage/' . str_replace('public/', '', $item->photo)) . '" style="max-height: 40px;"/>'
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
            'type_id' => 'required|exists:types,id', // validasi foreign key
        ]);

        // Simpan file foto ke storage
        $file = $request->file('photo');
        $fileName = time() . '-' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
        $photoPath = $file->storeAs('assets/category', $fileName, 'public');

        // Simpan data ke database
        Category::create([
            'name' => $request->name,
            'photo' => $photoPath,
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
        $data = $request->validated();
        $item = Category::findOrFail($id);

        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('photo')) {
            if ($item->photo) {
                Storage::delete('public/' . $item->photo);
            }

            $file = $request->file('photo');
            $fileName = time() . '-' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $data['photo'] = $file->storeAs('assets/category', $fileName, 'public');
        }

        $item->update($data);

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
