@extends('layouts.emails.default')

@section('content')
<x-email.text-section>
    <x-email.h1>
        Hello, {{ $user->first_name }}!
    </x-email.h1>

    <x-email.p>
        @if ($order->pay_on_delivery)
            <h4>
                Your order was successful and will be delivered to you soon.
                You are expected to make means of payment available before the delivery.
            </h4>
            
        @else
            <h4>Here is your receipt:</h4>
        @endif
        <strong>Reference:</strong> {{ $order->reference_number }}<br />
        <strong>Your name:</strong> {{ $order->user->full_name }}<br />
        <strong>Email:</strong> {{ $order->user->email }}<br />
        <strong>Phone:</strong> {{ $order->user->phone ?? 'N/A' }}<br />
        <strong>Address:</strong><br />
        {{ $order->address->line_1 }}
        {!! $order->address->line_2 ? "<br />{$order->address->line_2},<br />" : '<br />' !!}
        {{ $order->address->city ? "{$order->address->city}, " : '' }}
        {{ $order->address->state ? $order->address->state->state_name : '' }}
        <br />

        @if (!empty($order->order_notes))
            <strong>Order notes:</strong><br />
            {{ $order->order_notes }}
        @endif
    </x-email.p>

    <x-email.p>
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
                @foreach ($order->items as $key => $orderItem)
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
                    <th colspan="4" class="text-right">Delivery ({{ $order->delivery_days }} days)</th>
                    <th style="text-align: right;">
                        {{ number_format($order->delivery_amount) }}
                    </th>
                </tr>
                <tr>
                    <th colspan="4" class="text-right">GRAND TOTAL</th>
                    <th style="text-align: right;">
                        {{ number_format($order->total_amount) }}
                    </th>
                </tr>
            </tbody>
        </table>

        @if ($order->pay_on_delivery)
            <p style="text-align: right; padding-right: 10px; color: #F00000;">
                <strong>Payment on delivery!</strong>
            </p>
        @endif
    </x-email.p>

    <br />

    <x-email.p>
        Regards,<br />
        {{ config('app.name') }} Team.
    </x-email.p>


</x-email.text-section>
@endsection