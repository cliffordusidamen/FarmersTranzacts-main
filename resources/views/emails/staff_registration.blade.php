@extends('layouts.emails.default')

@section('content')
<x-email.text-section>
    <x-email.h1>
        Hello, {{ucwords(strtolower($user->first_name))}}!
    </x-email.h1>

    <x-email.p>
        You have been registered on the online admin platform of {{ config('app.name') }}
    </x-email.p>

    <x-email.p>
        You can login to your account with the following details:<br /><br />
        <strong>Username: </strong> <strong>{{ $user->email }}</strong> or <strong>{{ $user->phone }}</strong><br />
        <strong>Temporary password: </strong> {{ $password }}
    </x-email.p>

    <br />

    <x-email.p>
        Regards,<br />
        {{ config('app.name') }} Team.
    </x-email.p>


</x-email.text-section>
@endsection