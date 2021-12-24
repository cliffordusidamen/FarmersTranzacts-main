@extends('layouts.emails.default')

@section('content')

<x-email.text-section>
    <x-email.h1>
        Hello, {{ $user->first_name }}!
    </x-email.h1>

    <x-email.p>
        <h4>You have a new review for the order, <strong>{{ $vendorOrder->reference_number }}</strong></h4>
        <strong style="color: #555555">SHOP NAME:</strong> {{ $vendorOrder->vendor->shop_name}} <br />
        <strong>Customer name:</strong> {{ $vendorOrder->user->full_name }}<br />
        <strong style="color: #555555">RATING:</strong> {{ $vendorOrder->customer_review_remark }} <br />
        @if (!empty($vendorOrder->customer_review_comment))
            <strong style="color: #555555">COMMENT:</strong> {{ $vendorOrder->customer_review_comment }} <br />
        @endif
    </x-email.p>

    <br />

    <x-email.p>
        You may login to your account to view the order details.
    </x-email.p>

    <x-email.p>
        Regards,<br />
        {{ config('app.name') }} Team.
    </x-email.p>


</x-email.text-section>
@endsection