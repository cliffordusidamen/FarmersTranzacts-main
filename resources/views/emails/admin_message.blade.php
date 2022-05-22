@extends('layouts.emails.default')

@section('content')
<x-email.text-section>
    <x-email.p>
        {{{ prepTextForDisplay($adminMessage->body) }}}
    </x-email.p>
</x-email.text-section>
@endsection