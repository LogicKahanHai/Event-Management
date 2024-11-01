@extends('layouts.app')

@section('title', 'Registration Details')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h1 class="text-2xl font-bold text-gray-900">Registration Details</h1>
        </div>

        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            Registration Confirmed
                        </p>
                    </div>
                </div>
            </div>

            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Registration Number</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $registration->registration_number }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Event</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $registration->event->title }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Date & Time</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $registration->event->start_date->format('M d, Y h:i A') }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Venue</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $registration->event->venue }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Ticket Quantity</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $registration->ticket_quantity }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                    <dd class="mt-1 text-sm text-gray-900">${{ number_format($registration->total_amount, 2) }}</dd>
                </div>

                @if ($registration->special_requests)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Special Requests</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $registration->special_requests }}</dd>
                    </div>
                @endif
            </dl>

            @if ($registration->status !== 'cancelled')
                <div class="mt-6 flex justify-end">
                    <form action="{{ route('registrations.cancel', $registration) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
                            onclick="return confirm('Are you sure you want to cancel this registration?')">
                            Cancel Registration
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
    @auth
        @if (!$event->isRegisteredByUser(auth()->id()))
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <form action="{{ route('registrations.store', $event) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="ticket_quantity" class="block text-sm font-medium text-gray-700">Number of
                                Tickets</label>
                            <input type="number" name="ticket_quantity" id="ticket_quantity" min="1"
                                @if ($event->capacity) max="{{ $event->availableSpots() }}" @endif value="1"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="special_requests" class="block text-sm font-medium text-gray-700">Special
                                Requests</label>
                            <textarea name="special_requests" id="special_requests" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                @if ($event->capacity)
                                    {{ $event->availableSpots() }} spots remaining
                                @endif
                            </div>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Register Now
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <p class="text-sm text-gray-500">You are already registered for this event.</p>
            </div>
        @endif
    @else
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <p class="text-sm text-gray-500">Please <a href="{{ route('login') }}" class="text-indigo-600">login</a> to
                register for this event.</p>
        </div>
    @endauth
@endsection
