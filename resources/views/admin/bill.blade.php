<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('common.invoice')</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Almarai', sans-serif;
        }

        .invoice-title h2, .invoice-title h3 {
            display: inline-block;
        }

        .table > tbody > tr > .no-line {
            border-top: none;
        }

        .table > thead > tr > .no-line {
            border-bottom: none;
        }

        .table > tbody > tr > .thick-line {
            border-top: 2px solid;
        }
    </style>
    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0;  /* this affects the margin in the printer settings */
        }
    </style>
</head>
<body>
<!------ Include the above in your HEAD tag ---------->

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="invoice-title">
                @if(app()->getLocale() == 'ar')
                    <h2 class="pull-right">@lang('common.invoice')</h2>
                    <h3>@lang('common.order') # {{ $order->id }}</h3>
                    <img src="{{ asset('logo.png') }}" id="logo" style="display: block;margin-left: auto;margin-right: auto;width: 15%;">
                @else
                    <h2>@lang('common.invoice')</h2>
                    <h3 class="pull-right">@lang('common.order') # {{ $order->id }}</h3>
                    <img src="{{ asset('logo.png') }}" id="logo" style="display: block;margin-left: auto;margin-right: auto;width: 15%;">
                @endif
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-6">
                    <address>
                        <strong>@lang('common.billed_to')</strong><br>
                        {{ @$order->user->name }}<br>
                        {{ @$order->user->mobile }}<br>
                    </address>
                </div>
                <div class="col-xs-6 text-right">
                    <address>
                        <strong>@lang('common.shipped_to')</strong><br>
                        {{ @$order->user->name }}<br>
                        {{ @$order->address->address_name }}<br>
                        {{ @$order->address->detailed_address }}<br>
                        @lang('common.postal_number') : {{ @$order->address->postal_number }}<br>
                    </address>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <address>
                        <strong>@lang('common.payment_method')</strong><br>
                        {{ @$order->payment_method->title }}<br>
                        {{ @$order->user->email }}
                    </address>
                </div>
                <div class="col-xs-6 text-right">
                    <address>
                        <strong>@lang('common.order_date')</strong><br>
                        {{ __('common.' . \Carbon\Carbon::parse($order->created_at)->format('M')) . ' ' . \Carbon\Carbon::parse($order->created_at)->format('d, Y') }}
                        <br><br>
                    </address>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" style="{{ app()->getLocale() == 'ar' ? 'direction: rtl' : 'direction: ltr' }}">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>@lang('common.order_summary')</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                <td><strong>@lang('common.the_product')</strong></td>
                                <td class="text-center"><strong>@lang('common.price')</strong></td>
                                <td class="text-center"><strong>@lang('common.quantity')</strong></td>
                                <td class="text-right"><strong>@lang('common.total_price')</strong></td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->cart->items as $item)
                                <tr>
                                    <td>{{ $item->product->title }}</td>
                                    <td class="text-center">{{ @$item->discount_price != null ? $item->discount_price : $item->price }} @lang('common.rial')</td>
                                    <td class="text-center">{{ @$item->quantity }}</td>
                                    <td class="text-right">{{ @$item->total_price }} @lang('common.rial')</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="no-line"></td>
                                <td class="no-line"></td>
                                <td class="no-line text-center"><strong>@lang('common.delivery_price')</strong></td>
                                <td class="no-line text-right" style="font-weight: bold">{{ $order->total_price - $order->cart->items->sum('item_price') }} @lang('common.rial')</td>
                            </tr>
                            <tr>
                                <td class="no-line"></td>
                                <td class="no-line"></td>
                                <td class="no-line text-center"><strong>@lang('common.total')</strong></td>
                                <td class="no-line text-right" style="font-weight: bold">{{ $order->total_price }} @lang('common.rial')</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    window.print();
</script>
</html>
