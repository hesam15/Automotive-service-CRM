<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Option;
use Illuminate\Http\Request;
use App\Helpers\OptionsArrayHelper;
use Illuminate\Support\Facades\Auth;

class OptionsController extends Controller
{
    // Read
    public function index()
    {
        $user = User::with('serviceCenter.options')->find(auth()->user()->id);

        $options = $user->serviceCenter->options;
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
            Option::create([
                'name' => $request->name,
                'values' => json_encode($options_array, JSON_UNESCAPED_UNICODE),
                'service_center_id' => auth()->user()->serviceCenter->id,
            ]);
    
            return redirect()->back()->with('alert', ['خدمت با موفقیت ایجاد شد.', "success"]);
        }
        catch (\Exception $e) {
            return redirect()->back()->with('alert', ['خطا در ایجاد خدمت.', "danger"]);
        }
    }

    // Update
    public function edit(Option $option)
    {
        $values = json_decode($option->values, true);
        return view('admin.options.edit', compact('option', 'values'));
    }

    public function update(Request $request, Option $option)
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
    public function destroy(Option $option)
    {
        $option->delete();
        return redirect()->back()->with('alert', ['خدمت با موفقیت حذف شد.', "success"]);
    }
}