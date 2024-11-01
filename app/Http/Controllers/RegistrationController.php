<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'ticket_quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($event) {
                    if ($event->capacity && $value > $event->availableSpots()) {
                        $fail('Not enough tickets available.');
                    }
                },
            ],
            'special_requests' => 'nullable|string|max:500',
        ]);

        // Check if user is already registered
        if ($event->isRegisteredByUser(auth()->id())) {
            return back()->with('error', 'You are already registered for this event.');
        }

        // Calculate total amount
        $totalAmount = $event->price * $validated['ticket_quantity'];

        // Create registration
        $registration = $event->registrations()->create([
            'user_id' => auth()->id(),
            'ticket_quantity' => $validated['ticket_quantity'],
            'total_amount' => $totalAmount,
            'special_requests' => $validated['special_requests'],
            'status' => 'confirmed'
        ]);

        return redirect()->route('registrations.show', $registration)
            ->with('success', 'Successfully registered for the event!');
    }

    public function show(Registration $registration)
    {
        $this->authorize('view', $registration);
        return view('registrations.show', compact('registration'));
    }

    public function cancel(Registration $registration)
    {
        $this->authorize('update', $registration);

        $registration->update(['status' => 'cancelled']);

        return redirect()->route('events.show', $registration->event)
            ->with('success', 'Registration cancelled successfully.');
    }
}
