<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Vj;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VjAdminController extends Controller
{
    public function index()
    {
        $vjs = Vj::query()->with('language')->orderBy('name')->paginate(20);

        return view('admin.vjs.index', [
            'vjs' => $vjs,
        ]);
    }

    public function create()
    {
        return view('admin.vjs.create', [
            'languages' => Language::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:150', 'unique:vjs,slug'],
            'language_id' => ['nullable', 'integer', 'exists:languages,id'],
            'bio' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        Vj::create($data);

        return redirect()->route('admin.vjs.index')->with('status', 'VJ created.');
    }

    public function edit(Vj $vj)
    {
        return view('admin.vjs.edit', [
            'vj' => $vj,
            'languages' => Language::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Vj $vj)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:150', 'unique:vjs,slug,'.$vj->id],
            'language_id' => ['nullable', 'integer', 'exists:languages,id'],
            'bio' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        $vj->update($data);

        return redirect()->route('admin.vjs.index')->with('status', 'VJ updated.');
    }

    public function destroy(Vj $vj)
    {
        $vj->delete();

        return redirect()->route('admin.vjs.index')->with('status', 'VJ deleted.');
    }
}
