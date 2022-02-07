@extends('layouts.emails.default')

@section('content')
@php
    $grandTotal = 0;
@endphp
<x-email.text-section>
    <x-email.h1>
        New order received!
    </x-email.h1>

    @if ($vendorOrder->pay_on_delivery)
    <x-email.p>
        <span style="text-align: right; padding-right: 10px; color: #F00000;">
            <strong>Payment on delivery!</strong>
        </span>
    </x-email.p>
    @endif

    <x-email.p>
        Hello, {{$user->first_name}}!<br />
        You have a new order for some item(s) on your shop:<br />
        <strong>{{ $vendor->shop_name }}</strong>
    </x-email.p>

    <x-email.p>
        <strong>Reference:</strong> {{ $order->reference_number }}<br />
    </x-email.p>

    <x-email.p>
        @if ($order->agent_id)
            <strong>Agent:</strong> {{ $order->agent->full_name }}<br />
            <strong>On behalf of:</strong> {{ $order->user->full_name }}<br />
        @else
            <strong>Shopper name:</strong> {{ $order->user->full_name }}<br />
        @endif
        <strong>Address:</strong><br />
        {{ $order->address->line_1 }}
        {!! $order->address->line_2 ? "<br />{$order->address->line_2},<br />" : '<br />' !!}
        {{ $order->address->city ? "{$order->address->city}, " : '' }}
        {{ $order->address->state ? $order->address->state->state_name : '' }}
    </x-email.p>

    <x-email.p>
        <h4>Order details:</h4><br />
        <table>
            <thead>
                <tr>
                    <th class="text-sm uppercase">S/N</th>
                    <th class="text-sm uppercase" width="30%">Product</th>
                    <th class="text-sm text-right uppercase">Quantity</th>
                    <th class="text-sm text-right uppercase">Unit Price(&#8358;)</th>
                    <th class="text-sm text-right uppercase">Total Price(&#8358;)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vendorOrder->items as $key => $orderItem)
                    @php
                        $grandTotal += $orderItem->total_amount;
                    @endphp
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <strong>{{ $orderItem->product->name }}</strong><br />
                        </td>
                        <td class="text-right">{{ number_format($orderItem->quantity) }}</td>
                        <td class="text-right">{{ number_format($orderItem->unit_amount) }}</td>
                        <td class="text-right">{{ number_format($orderItem->total_amount) }}</td>
                    </tr>
                @endforeach
                
                <tr>
                    <th colspan="4" class="text-right">Delivery ({{ $vendorOrder->delivery_days }} days)</th>
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
    @if (!empty($order->order_notes))
        <strong>Order notes:</strong><br />
        {{ $order->order_notes }}
    @endif

    <br />

    <x-email.p>
        Regards,<br />
        {{ config('app.name') }} Team.
    </x-email.p>


</x-email.text-section>
@endsection