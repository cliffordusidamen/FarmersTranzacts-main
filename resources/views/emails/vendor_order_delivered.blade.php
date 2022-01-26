@extends('layouts.emails.default')

@section('content')
@php
    $expectedDeliveryDate = carbon_ng($vendorOrder->created_at)->addDays(
        $vendorOrder->delivery_days
    );
    $daysToDeliver = $expectedDeliveryDate->diffInDays(carbon_ng(), true);
@endphp
<x-email.text-section>
    <x-email.h1>
        Hello, {{ $user->first_name }}!
    </x-email.h1>

    <x-email.p>
        <h4>Your order has been delivered:</h4>
        <strong>Reference:</strong> {{ $vendorOrder->reference_number }}<br />

        
        @if ($vendorOrder->pay_on_delivery)
            <strong>Payment method:</strong>
            <span style="text-transform: uppercase; display: block">
                Payment on delivery
                ({{ $vendorOrder->pay_on_delivery_method }})
            </span>
        @endif
        <strong>Address:</strong><br />
        {{ $vendorOrder->order->address->line_1 }}
        {!! $vendorOrder->order->address->line_2 ? "<br />{$vendorOrder->order->address->line_2},<br />" : '<br />' !!}
        {{ $vendorOrder->order->address->city ? "{$vendorOrder->order->address->city}, " : '' }}
        {{ $vendorOrder->order->address->state ? $vendorOrder->order->address->state->state_name : '' }}
    </x-email.p>

    <x-email.p>
        <h5>
            <span style="color: #555555">SHOP NAME</span>
            <br>
            {{ $vendorOrder->vendor->shop_name}}

            @if (!empty($vendorOrder->delivery_note))
                <p>
                    <strong>Delivery Note:</strong>
                    <br />
                    {{ $vendorOrder->delivery_note }}
                </p>
            @endif
        </h5>
        <table>
            <thead>
                <tr>
                    <th class="text-sm uppercase">S/N</th>
                    <th class="text-sm uppercase" width="30%">Product</th>
                    <th class="text-sm uppercase" style="text-align: right;">Quantity</th>
                    <th class="text-sm uppercase" style="text-align: right;">Unit Price(&#8358;)</th>
                    <th class="text-sm uppercase" style="text-align: right;">Total Price(&#8358;)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vendorOrder->items as $key => $orderItem)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <strong>{{ $orderItem->product->name }}</strong><br />
                            <small>Vendor: {{ $orderItem->product->vendor->shop_name }}</small>
                        </td>
                        <td style="text-align: right;">{{ number_format($orderItem->quantity) }}</td>
                        <td style="text-align: right;">{{ number_format($orderItem->unit_amount) }}</td>
                        <td style="text-align: right;">{{ number_format($orderItem->total_amount) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="4" class="text-right">Delivery cost</th>
                    <th style="text-align: right;">
                        {{ number_format($vendorOrder->delivery_amount) }}
                    </th>
                </tr>
                <tr>
                    <th colspan="4" class="text-right">GRAND TOTAL</th>
                    <th style="text-align: right;">
                        {{ number_format($vendorOrder->total_amount) }}
                    </th>
                </tr>
            </tbody>
        </table>
    </x-email.p>

    <br />

    <x-email.p>
        Thanks for your patronage! We hope you call us again soon.
    </x-email.p>

    <x-email.p>
        Regards,<br />
        {{ config('app.name') }} Team.
    </x-email.p>


</x-email.text-section>
@endsection