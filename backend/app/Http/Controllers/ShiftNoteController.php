<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShiftNoteController extends Controller
{
    public function index()
    {
        $notes = \App\Models\ShiftNote::orderBy('created_at', 'desc')->get();
        return view('shift-notes', compact('notes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'note' => 'required|string'
        ]);

        // Default cashier name if not using proper auth yet
        $cashierName = 'Aries Marolina';

        \App\Models\ShiftNote::create([
            'note' => $request->note,
            'cashier_name' => $cashierName,
            'is_done' => false
        ]);

        return redirect('/shift-notes');
    }

    public function markDone($id)
    {
        $note = \App\Models\ShiftNote::findOrFail($id);
        $note->is_done = true;
        $note->save();

        return redirect('/shift-notes');
    }
}
