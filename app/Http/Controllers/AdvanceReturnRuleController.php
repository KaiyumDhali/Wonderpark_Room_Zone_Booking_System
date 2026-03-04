<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class AdvanceReturnRuleController extends Controller
{
    public function index()
    {
        $rules = DB::table('advance_return_rules')
            ->orderBy('max_day', 'asc')
            ->get();

        return view('pages.advance_return_rules.index', compact('rules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'max_day' => 'required|integer|min:0',
            'refund_percent' => 'required|numeric|min:0|max:100',
        ]);

        // 🔒 max_day ascending check (important)
        $lastRule = DB::table('advance_return_rules')
            ->orderBy('max_day', 'desc')
            ->first();

        if ($lastRule && $request->max_day <= $lastRule->max_day) {
            return back()->withErrors([
                'max_day' => 'Max day must be greater than last rule (' . $lastRule->max_day . ')'
            ])->withInput();
        }

        DB::table('advance_return_rules')->insert([
            'max_day' => $request->max_day,
            'refund_percent' => $request->refund_percent,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('advance-return-rules.index')
            ->with('success', 'Advance return rule added successfully');
    }
    public function edit($id)
{
    $rule = DB::table('advance_return_rules')->where('id', $id)->first();
    $rules = DB::table('advance_return_rules')->orderBy('max_day', 'asc')->get();

    return view('pages.advance_return_rules.index', compact('rule', 'rules'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'max_day' => 'required|integer|min:0',
        'refund_percent' => 'required|numeric|min:0|max:100',
    ]);

    $currentRule = DB::table('advance_return_rules')->where('id', $id)->first();

    // 🔑 only check if max_day is changed
    if ($request->max_day != $currentRule->max_day) {

        $lastRule = DB::table('advance_return_rules')
            ->where('id', '<>', $id)
            ->orderBy('max_day', 'desc')
            ->first();

        if ($lastRule && $request->max_day <= $lastRule->max_day) {
            return back()->withErrors([
                'max_day' => 'Max day must be greater than last rule (' . $lastRule->max_day . ')'
            ])->withInput();
        }
    }

    DB::table('advance_return_rules')
        ->where('id', $id)
        ->update([
            'max_day' => $request->max_day,
            'refund_percent' => $request->refund_percent,
            'updated_at' => now(),
        ]);

    return redirect()
        ->route('advance-return-rules.index')
        ->with('success', 'Rule updated successfully');
}


public function destroy($id)
{
    DB::table('advance_return_rules')->where('id', $id)->delete();

    return redirect()->route('advance-return-rules.index')->with('success', 'Rule deleted successfully');
}

}
