@extends('layouts.emails.default')

@section('content')
<x-email.text-section>
    <x-email.h1>
        Seller order canceled! 
    </x-email.h1>

    <x-email.p>
        The folowing seller order has been cancelled<br />
        <strong>Reference:</strong> {{ $vendorOrder->order->reference_number }}<br />
        <strong>Shop name:</strong> {{ $vendorOrder->vendor->shop_name }}<br />
        <strong>Customer name:</strong> {{ $vendorOrder->user->full_name }}<br />
        <strong>Customer Email:</strong> {{ $vendorOrder->user->email }}<br />
        <strong>Customer Phone:</strong> {{ $vendorOrder->user->phone ?? 'N/A' }}<br />
        <br />

        <strong>Reason for cancelling:</strong><br />
        {{ $vendorOrder->cancel_reason }}
    </x-email.p>

    <x-email.p>
        <h4>Order details:</h4>
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


</x-email.text-section>
@endsection