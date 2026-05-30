<?php

namespace App\Http\Controllers;

use App\Models\Party;
use Illuminate\Http\Request;

class PartyController extends Controller
{
    public function index()
    {
        $parties = Party::orderBy('code', 'asc')->get();
        return view('parties.index', compact('parties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        // تولید کد خودکار توسط مدلی که قبلا ساختیم
        $validated['code'] = Party::generateNextCode();
        
        // دریافت وضعیت چک‌باکس‌ها
        $validated['is_customer'] = $request->has('is_customer');
        $validated['is_supplier'] = $request->has('is_supplier');
        $validated['is_employee'] = $request->has('is_employee');
        $validated['is_shareholder'] = $request->has('is_shareholder');

        Party::create($validated);

        return redirect()->route('parties.index')->with('status', 'person-created');
    }
}