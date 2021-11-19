@extends('layouts.emails.default')

@section('content')
<x-email.text-section>
    <x-email.h1>
        You message has been received!
    </x-email.h1>

    <x-email.p>
        We will contact you as soon as possible, if neccessary.
        Here are the details of your message:<br />
        <strong>Name:</strong> {{ $data['name'] }}<br />
        <strong>Email:</strong> {{ $data['email'] ?? 'N/A' }}<br />
        <strong>Phone:</strong> {{ $data['phone'] ?? 'N/A' }}<br />
        <strong>Subject:</strong> {{ $data['subject'] ?? 'N/A' }}<br />
        <strong>Message:</strong><br />
        {{ $data['message'] }}
        <br />
        <br />
    </x-email.p>


    <x-email.p>
        Thanks for reaching out.<br />
        Regards,<br />
        {{ config('app.name') }} Team.
    </x-email.p>
</x-email.text-section>
@endsection