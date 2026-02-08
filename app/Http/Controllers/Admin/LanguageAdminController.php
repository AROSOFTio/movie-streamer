<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LanguageAdminController extends Controller
{
    public function index()
    {
        $languages = Language::query()->orderBy('name')->paginate(20);

        return view('admin.languages.index', [
            'languages' => $languages,
        ]);
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:languages,slug'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        Language::create($data);

        return redirect()->route('admin.languages.index')->with('status', 'Language created.');
    }

    public function edit(Language $language)
    {
        return view('admin.languages.edit', [
            'language' => $language,
        ]);
    }

    public function update(Request $request, Language $language)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:languages,slug,'.$language->id],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        $language->update($data);

        return redirect()->route('admin.languages.index')->with('status', 'Language updated.');
    }

    public function destroy(Language $language)
    {
        $language->delete();

        return redirect()->route('admin.languages.index')->with('status', 'Language deleted.');
    }
}
