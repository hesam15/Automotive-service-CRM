<?php

namespace App\Http\Controllers;

use App\Helpers\OptionsArrayHelper;
use App\Models\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\Uri\Idna\Option;

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
    
            return redirect()->back()->with('success', 'خدمت با موفقیت ایجاد شد.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطا در ایجاد خدمت.');
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

            return redirect()->back()->with('success', 'خدمت با موفقیت بروزرسانی شد.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطا در بروزرسانی خدمت.');
        }
    }

    // Delete
    public function destroy(Options $option)
    {
        $option->delete();
        return redirect()->back()->with('success', 'خدمت با موفقیت حذف شد.');
    }
}