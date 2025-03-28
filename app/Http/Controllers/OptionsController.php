<?php

namespace App\Http\Controllers;

use App\Helpers\OptionsArrayHelper;
use App\Models\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OptionsController extends Controller
{
    // Read
    public function index()
    {
        $options = Options::all();
        foreach ($options as $option) {
            $option->values = json_decode($option->values);
        }

        return view('admin.options.index', compact('options'));
    }

    // Create
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'options' => 'required|array',
            'values' => 'required|array',
        ]);

        $options_array = OptionsArrayHelper::generateOptionsArray($request->options, $request->values);

        try {
            Options::create([
                'name' => $request->name,
                'values' => json_encode($options_array, JSON_UNESCAPED_UNICODE),
                'user_id' => Auth::id(),
            ]);
    
            return redirect()->back()->with('alert', ['خدمت با موفقیت ایجاد شد.', "success"]);
        }
        catch (\Exception $e) {
            return redirect()->back()->with('alert', ['خطا در ایجاد خدمت.', "danger"]);
        }
    }

    // Update
    public function edit(Options $option)
    {
        $values = json_decode($option->values, true);
        return view('admin.options.edit', compact('option', 'values'));
    }

    public function update(Request $request, Options $option)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'options' => 'required|array',
            'values' => 'required|array',
        ]);

        $options_array = OptionsArrayHelper::generateOptionsArray($request->sub_options, $request->sub_values);

        try {
            $option->update([
                'name' => $request->name,
                'values' => json_encode($options_array, JSON_UNESCAPED_UNICODE),
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()->with('alert', ['خدمت با موفقیت بروزرسانی شد.', "success"]);
        }
        catch (\Exception $e) {
            return redirect()->back()->with('alert', ['خطا در بروزرسانی خدمت.', "danger"]);
        }
    }

    // Delete
    public function destroy(Options $option)
    {
        $option->delete();
        return redirect()->back()->with('alert', ['خدمت با موفقیت حذف شد.', "success"]);
    }
}