@extends('layouts.emails.default')

@section('content')
<x-email.text-section>
    <x-email.h1>
        New review received!
    </x-email.h1>

    <x-email.p>
        <strong>Reference:</strong> {{ $vendorOrder->order->reference_number }}<br />
        <strong>Shopper name:</strong> {{ $vendorOrder->user->full_name }}<br />
        <strong>Email:</strong> {{ $vendorOrder->user->email }}<br />
        <strong>Phone:</strong> {{ $vendorOrder->user->phone ?? 'N/A' }}<br />
        <strong style="color: #555555">SHOP NAME:</strong> {{ $vendorOrder->vendor->shop_name}}<br />
        <strong style="color: #555555">RATING:</strong> {{ $vendorOrder->customer_review_remark }} <br />
        @if (!empty($vendorOrder->customer_review_comment))
            <strong style="color: #555555">COMMENT:</strong> {{ $vendorOrder->customer_review_comment }} <br />
        @endif
    </x-email.p>

    <br />

    <x-email.p>
        Regards,<br />
        {{ config('app.name') }} Team.
    </x-email.p>


</x-email.text-section>
@endsection