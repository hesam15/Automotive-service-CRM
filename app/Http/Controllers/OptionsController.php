<?php

namespace App\Http\Controllers;

use App\Models\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OptionsController extends Controller
{
    // Read
    public function index()
    {
        $options = Options::all();
        return view('admin.options.index', compact('options'));
    }

    // Create
    public function create()
    {
        return view('admin.options.createOption');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sub_options' => 'required|array',
            'sub_values' => 'required|array',
        ]);

        $options_array = array_combine(
            $request->sub_options,
            array_map(function ($value) {
                return array_map('trim', explode('،', $value));
            }, $request->sub_values)
        );

        $option = new Options();
        $option->name = $request->name;
        $option->values = json_encode($options_array, JSON_UNESCAPED_UNICODE);
        $option->user_id = Auth::id();
        $option->save();

        return redirect()->back()->with('success', 'خدمت با موفقیت ایجاد شد.');
    }

    // Update
    public function edit($id)
    {
        $option = Options::findOrFail($id);
        $values = json_decode($option->values, true);
        return view('admin.options.edit', compact('option', 'values'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sub_options' => 'required|array',
            'sub_values' => 'required|array',
        ]);

        $options_array = array_combine(
            $request->sub_options,
            array_map(function ($value) {
                return array_map('trim', explode('،', $value));
            }, $request->sub_values)
        );

        $option = Options::findOrFail($id);
        $option->update([
            'name' => $request->name,
            'values' => json_encode($options_array, JSON_UNESCAPED_UNICODE),
            'user_id' => Auth::id()
        ]);

        return back()->with('success', 'خدمت با موفقیت ویرایش شد.');
    }

    // Delete
    public function destroy($id)
    {
        $option = Options::findOrFail($id);
        $option->delete();
        return redirect()->back()->with('success', 'خدمت با موفقیت حذف شد.');
    }
}