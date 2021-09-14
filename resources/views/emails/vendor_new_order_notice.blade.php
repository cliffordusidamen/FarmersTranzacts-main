@extends('layouts.emails.default')

@section('content')
@php
    $grandTotal = 0;
@endphp
<x-email.text-section>
    <x-email.h1>
        New order received!
    </x-email.h1>

    <x-email.p>
        Hello, {{$user->first_name}}!<br />
        You have a new order for some item(s) on your shop:<br />
        <strong>{{ $vendor->shop_name }}</strong>
    </x-email.p>

    <x-email.p>
        <strong>Reference:</strong> {{ $order->reference_number }}<br />
    </x-email.p>

    <x-email.p>
        <h4>Order details:</h4><br />
        <table>
            <thead>
                <tr>
                    <th class="text-sm uppercase">S/N</th>
                    <th class="text-sm uppercase" width="30%">Product</th>
                    <th class="text-sm text-right uppercase">Quantity</th>
                    <th class="text-sm text-right uppercase">Unit Price</th>
                    <th class="text-sm text-right uppercase">Total Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $key => $orderItem)
                    @php
                        $grandTotal += $orderItem->total_amount;
                    @endphp
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <strong>{{ $orderItem->product->name }}</strong><br />
                        </td>
                        <td class="text-right">{{ number_format($orderItem->quantity) }}</td>
                        <td class="text-right">&#8358;{{ number_format($orderItem->unit_amount) }}</td>
                        <td class="text-right">&#8358;{{ number_format($orderItem->total_amount) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="4" class="text-right">GRAND TOTAL</th>
                    <th style="text-align: right;">
                        &#8358;{{ number_format($grandTotal) }}
                    </th>
                </tr>
            </tbody>
        </table>
    </x-email.p>

    <br />

    <x-email.p>
        Regards,<br />
        {{ config('app.name') }} Team.
    </x-email.p>


</x-email.text-section>
@endsection