<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function contact()
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required',
        ]);

        // Process the contact form submission
        // You can add code here to send an email or save to database

        return redirect()->route('contact')->with('success', 'Thank you for your message. We will get back to you soon.');
    }

    public function terms()
    {
        return view('pages.terms');
    }
}
