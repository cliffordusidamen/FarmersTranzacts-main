@extends('layouts.emails.default')

@section('content')
<x-email.text-section>
    <x-email.h1>
        Hello, {{ $user->first_name }}!
    </x-email.h1>

    <x-email.p>
        <h4>
            Your order, {{ $vendorOrder->order->reference_number }}, has been cancelled.
            @if (!$vendorOrder->pay_on_delivery)
                <br />
                <small>Your refund will be processed soon.</small>
            @endif
        </h4>

        
        <strong>Reason for cancelling:</strong><br />
        {{ $vendorOrder->cancel_reason }}
        
    </x-email.p>

    <x-email.p>
        Order details:
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
                @foreach ($vendorOrder->items as $key => $vendorOrderItem)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <strong>{{ $vendorOrderItem->product->name }}</strong><br />
                            <small>Vendor: {{ $vendorOrderItem->product->vendor->shop_name }}</small>
                        </td>
                        <td style="text-align: right;">{{ number_format($vendorOrderItem->quantity) }}</td>
                        <td style="text-align: right;">{{ number_format($vendorOrderItem->unit_amount) }}</td>
                        <td style="text-align: right;">{{ number_format($vendorOrderItem->total_amount) }}</td>
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

    <x-email.p>
        Regards,<br />
        {{ config('app.name') }} Team.
    </x-email.p>


</x-email.text-section>
@endsection