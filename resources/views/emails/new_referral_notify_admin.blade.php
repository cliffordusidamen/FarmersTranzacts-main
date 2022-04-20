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
        
        @if (!empty($referralRequest->local_government_area_id))
            <strong>Local Govt.:</strong>
            {{ $referralRequest->localGovernmentArea->local_govt_name }}
            <br />
        @endif
        @if (!empty($referralRequest->ward))
            <strong>Ward:</strong>
            {{ $referralRequest->ward }}
            <br />
        @endif

        <strong>Phone:</strong> {{ $referralRequest->phone ?? 'N/A' }}<br />
        <strong>email:</strong> {{ $referralRequest->email ?? 'N/A' }}<br />
    </x-email.p>

    <x-email.p>
        You can <a href="{{ config('app.admin_app_url') }}">login</a> to view more details.
    </x-email.p>

    <br />

    <x-email.p>
        Regards,<br />
        {{ config('app.name') }} Team.
    </x-email.p>


</x-email.text-section>
@endsection