<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'privacy' => 'required|accepted',
        ], [
            'name.required' => __('contact.validation.name_required'),
            'email.required' => __('contact.validation.email_required'),
            'email.email' => __('contact.validation.email_email'),
            'subject.required' => __('contact.validation.subject_required'),
            'message.required' => __('contact.validation.message_required'),
            'privacy.required' => __('contact.validation.privacy_required'),
        ]);

        $subjectLabels = [
            'kaufen' => 'Immobilie kaufen',
            'verkaufen' => 'Immobilie verkaufen',
            'besichtigung' => 'Besichtigungstermin',
            'beratung' => 'Allgemeine Beratung',
            'sonstiges' => 'Sonstiges',
        ];

        $subjectLabel = $subjectLabels[$validated['subject']] ?? $validated['subject'];

        Mail::raw(
            "Neue Kontaktanfrage von der Website\n\n" .
            "Name: {$validated['name']}\n" .
            "E-Mail: {$validated['email']}\n" .
            "Telefon: " . ($validated['phone'] ?? 'Nicht angegeben') . "\n" .
            "Betreff: {$subjectLabel}\n\n" .
            "Nachricht:\n{$validated['message']}",
            function ($mail) use ($validated, $subjectLabel) {
                $mail->to('ali@kaymakci-real-estate.de')
                     ->subject("Kontaktanfrage: {$subjectLabel}")
                     ->replyTo($validated['email'], $validated['name']);
            }
        );

        return back()->with('success', __('contact.flash_success'));
    }
}
