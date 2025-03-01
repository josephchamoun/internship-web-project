<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function show()
    {
        $contact = Contact::first();
        return view('contact', compact('contact'));
    }
    public function index()
    {
        $contact = Contact::first();
        return response()->json(['message' => 'Success', 'contact' => $contact]);

    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        $contact = Contact::first();
        $contact->update([
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('contact')->with('success', 'Contact information updated successfully.');
    }
}
