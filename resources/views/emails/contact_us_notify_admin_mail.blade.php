@extends('layouts.emails.default')

@section('content')
<x-email.text-section>
    <x-email.h1>
        New message from your website
    </x-email.h1>

    <x-email.p>
        <strong>Name:</strong> {{ $data['name'] }}<br />
        <strong>Email:</strong> {{ $data['email'] ?? 'N/A' }}<br />
        <strong>Phone:</strong> {{ $data['phone'] ?? 'N/A' }}<br />
        <strong>Subject:</strong> {{ $data['subject'] ?? 'N/A' }}<br />
        <strong>Message:</strong><br />
        {{ $data['message'] }}
        <br />
        <br />
    </x-email.p>


</x-email.text-section>
@endsection