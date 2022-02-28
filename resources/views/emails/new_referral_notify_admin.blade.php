@extends('layouts.emails.default')

@section('content')
<x-email.text-section>
    <x-email.h1>
        New farmer request received!
    </x-email.h1>

    <x-email.p>
        <strong>Agent:</strong> {{ $referralRequest->user->full_name }} <br />
        <strong>Farmer name:</strong> {{ $referralRequest->full_name }}<br />
        <strong>Business name:</strong> {{ $referralRequest->business_name ?? 'N/A' }}<br />
        <strong>State:</strong> {{ $referralRequest->state->state_name }}<br />
        <strong>Phone:</strong> {{ $vendorOrder->phone ?? 'N/A' }}<br />
        <strong>email:</strong> {{ $vendorOrder->email ?? 'N/A' }}<br />
    </x-email.p>

    <x-email.p>
        You can <a href="{{ route('login') }}">login</a> to view more details.
    </x-email.p>

    <br />

    <x-email.p>
        Regards,<br />
        {{ config('app.name') }} Team.
    </x-email.p>


</x-email.text-section>
@endsection