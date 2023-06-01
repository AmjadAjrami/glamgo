<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ticket</title>
    <style>
        #invoice-POS {
            box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
            padding: 2mm;
            margin: 0 auto;
            width: 360px;
            background: #FFF;
        }

        ::selection {
            background: #f31544;
            color: #FFF;
        }

        ::moz-selection {
            background: #f31544;
            color: #FFF;
        }

        h1 {
            font-size: 1.5em;
            color: #222;
        }

        h2 {
            font-size: 1.9em;
        }

        h3 {
            font-size: 1.2em;
            font-weight: 300;
            line-height: 2em;
        }

        p {
            font-size: .7em;
            color: #666;
            line-height: 1.2em;
        }

        #top, #mid, #bot { /* Targets all id with 'col-' */
            border-bottom: 1px solid #EEE;
        }

        #top {
            min-height: 100px;
        }

        #mid {
            min-height: 80px;
        }

        #bot {
            min-height: 50px;
        }

        #top .logo {
        / / float: left;
            height: 70px;
            width: 60px;
            background: url('{{ asset('logo.png') }}') no-repeat;
            background-size: 60px 60px;
        }

        .clientlogo {
            float: left;
            height: 60px;
            width: 60px;
            background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
            background-size: 60px 60px;
            border-radius: 50px;
        }

        .info {
            display: block;
        / / float: left;
            margin-left: 0;
        }

        .title {
            float: right;
        }

        .title p {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
        / / padding: 5 px 0 5 px 15 px;
        / / border: 1 px solid #EEE
        }

        .tabletitle {
        / / padding: 5 px;
            font-size: .5em;
            background: #EEE;
        }

        .service {
            border-bottom: 1px solid #EEE;
        }

        .item {
            width: 72mm;
        }

        .itemtext {
            font-size: 20px;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        #legalcopy {
            margin-top: 5mm;
        }

        @page {
            size: auto;   /* auto is the initial value */
            margin: 0;  /* this affects the margin in the printer settings */
        }
    </style>
</head>
<body>

<div id="invoice-POS">
    @php \Carbon\Carbon::setLocale(app()->getLocale()) @endphp
    <center id="top">
        <div><img src="{{ asset('logo.png') }}" style="width: 120px;margin-top: 12px;"></div>
    </center><!--End InvoiceTop-->

    <div id="mid">
        <div class="info">
            @if(app()->getLocale() == 'ar')
                <h4 style="text-align:right">@lang('common.name') : {{ $reservation->user->name }}</h4>
                <h4 style="text-align:right"> {{ $reservation->user->mobile }} : @lang('common.mobile')</h4>
            @else
                <h4 style="text-align:left">@lang('common.user_name') : {{ $reservation->user->name }}</h4>
                <h4 style="text-align:left">@lang('common.mobile') : {{ $reservation->user->mobile }}</h4>
            @endif
            <h2 style="text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}">@lang('common.details')</h2>
        </div>
        <div id="bot">
            <div id="table">
                <table style="direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                    <tr class="service">
                        <td class="tableitem"><p class="itemtext">@lang('common.reservation_number')</p></td>
                        <td class="tableitem"><p class="itemtext" style="font-weight: bold">#{{ $reservation->reservation_number }}</p></td>
                    </tr>
                    <tr class="service">
                        <td class="tableitem"><p class="itemtext">@lang('common.reservation_date')</p></td>
                        <td class="tableitem"><p class="itemtext" style="font-weight: bold">{{ $reservation->date_text }}</p></td>
                    </tr>
                    <tr class="service">
                        <td class="tableitem"><p class="itemtext">@lang('common.reservation_time')</p></td>
                        <td class="tableitem"><p class="itemtext" style="font-weight: bold">{{ $reservation->reservation_time }}</p></td>
                    </tr>
                    <tr class="service">
                        <td class="tableitem"><p class="itemtext">@lang('common.provider_name')</p></td>
                        <td class="tableitem"><p class="itemtext" style="font-weight: bold">{{ $reservation->provider_name }}</p></td>
                    </tr>
                    <tr class="service">
                        <td class="tableitem"><p class="itemtext">@lang('common.services')</p></td>
                        @if($reservation->offer_id != null)
                            <td class="tableitem"><p class="itemtext" style="font-weight: bold">{{ $reservation->items->title }}</p></td>
                        @else
                            @foreach($reservation->items as $item)
                                <td class="tableitem"><p class="itemtext" style="font-weight: bold">{{ $item->service_name }}</p></td>
                            @endforeach
                        @endif
                    </tr>
                </table>
            </div><!--End Table-->

            <div id="legalcopy">
                <p class="legal"
                   style="font-size: 17px !important;{{ app()->getLocale() == 'ar' ? 'text-align: right' : 'text-align: left' }}">
                    <strong>{{ app()->getLocale() == 'ar' ? 'شكرا لك' : 'Thank You' }}</strong>
                </p>
            </div>
        </div>
    </div>
</div>

</body>
<script>
    window.print();
</script>
</html>
