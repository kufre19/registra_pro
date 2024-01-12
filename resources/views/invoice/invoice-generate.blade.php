<!Doctype html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>{{ config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ env('APP_URL').config('settings.application.company_icon') }}"/>
    <link rel="apple-touch-icon" href="{{ env('APP_URL').config('settings.application.company_icon') }}"/>
    <link rel="apple-touch-icon-precomposed" href="{{ env('APP_URL').config('settings.application.company_icon') }}"/>
    <style>
        *, *::after, *::before {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }
        .text-left {
            text-align: left;
        }
        .table-strip tr:nth-child(even) {
            background-color: #f8f8f8;
        }
        .invoice_container * {
            box-sizing: content-box;
        }
        .logo {
            width: 96px;
        }
        body {
            padding: 2rem 0;
        }
        /* reusables */
        .wrapper {
            padding: 0 3rem;
        }
        .font-bold {
            font-weight: bold;
        }
        .text-blue {
            color: #4c8cfd;
        }
        .detail {
            font-weight: bold;
        }
        .detail span:last-child {
            color: #767a8a;
        }
        .text-gray {
            color: #444;
        }
        /* custom */
        .header {
            width: 100%;
            background-color: #f8f8fa;
            padding: 3rem 0;
            margin-top: -2rem;
        }
        .header table {
            width: 100%;
        }
        .header-title {
            margin-bottom: 2rem;
        }
        .invoice-number {
            margin-bottom: 1.2rem;
        }
        .col-1 {
            margin-right: 13.65rem;
        }
        .logo {
            width: 16rem;
            height: 6.5rem;
            position: relative;
            margin-bottom: 1.7rem;
        }
        .logo img {
           position: absolute;
           top: 50%;
           left: 50%;
           transform: translate(-50%, -50%);
           width: 210px;
           height: 50px;
        }
        .customer-details {
            width: 100%;
            margin: 2rem 0;
        }
        .customer-details td:first-child {
            font-weight: bold;
            padding-bottom: 0.4rem;
        }
        .customer-details td {
            padding-bottom: 0.4rem;
        }
        .customer-details td:nth-child(odd){
            padding-right: 3.5rem;
        }
        .customer-details td:nth-child(even){
            width: 44%;
        }
        .invoice-data {
            width: 100%;
            position: relative;
        }
        .invoice-data thead {
            background-color: #f8f8fa;
            font-weight: bold;
        }
        .invoice-data thead tr td:first-child,
        .invoice-data tbody tr td:first-child {
            padding-left: 3rem;
        }
        .invoice-data thead tr td:last-child,
        .invoice-data tbody tr td:last-child {
            padding-right: 3rem;
        }
        .invoice-data .deal-title {
            width: 15rem;
        }
        .invoice-data tr td {
            padding: 0.65rem 0;
            text-align: right;
        }
        .invoice-data tr td:nth-child(2) {
            text-align: right;
            width: 6.5rem;
        }
        .invoice-data tr td:first-child {
            text-align: left;
        }
        .invoice-data tr td.text-left {
            text-align: left;
        }
        .invoice-data tr td:first-child {
            padding-right: 4rem;
        }
        .invoice-data .line-container td:last-child { position: relative; }
        .invoice-data .line-container td:last-child::after {
            content: '';
            position: absolute;
            left: 0;
            right: 3rem;
            top: 10px;
            height: .085rem;
            background: #222;
        }
       .invoice-footer {
            margin-top: 5rem;
            font-size: 1rem;
            line-height: 1.5rem;
       }
       .invoice-footer h4 {
            border-bottom: 2px solid #222;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
       }
    </style>
</head>
<body>
{{--This file is used both for instant pdf download and send mail with pdf attachment--}}
<div class="invoice-container">
        <div class="header">
            <table class="wrapper">
                <tr>
                    <td>
                        <div class="col-1">
                            <h1 class="header-title">{{__t('invoice')}}</h1>

                            <div class="invoice-details">
                                <p class="invoice-number detail">
                                    <span>{{__t('invoice_no')}}:</span> <br />
                                    <span>
                                        {{$invoice->invoice_number ?? ''}}
                                    </span>
                                </p>

                                <p class="invoice-date detail">
                                    <span>{{__t('issue_date')}}:</span> <br />
                                    <span>
                                        {{ $invoice->issue_date ? $invoice->issue_date->format('Y-m-d') : ''}}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="col-2">
                            <div class="logo">
                                <img src="{{ env('APP_URL').config('settings.application.company_logo')}}" alt="NF">
                            </div>

                            <p class="invoice-due-date detail">
                                <span>{{__t('due_date')}}: </span> <br />
                                <span>
                                {{$invoice->due_date ? $invoice->due_date->format('Y-m-d') : ''}}
                                </span>
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <table class="customer-details wrapper">
            <tr>
                <td>
                    {{ __t('bill_to') }}
                </td>

                @if ($invoice->createdBy->full_name)
                    <td>
                        {{$invoice->createdBy->full_name}}
                    </td>
                @endif
            </tr>

            <tr>
                @foreach ($invoice->deal->contactPerson as $contact_person)
                    @if ($contact_person->name)
                        <td>
                            {{$contact_person->name}}
                        </td>
                    @endif
                @endforeach

                <td>{{ __t('email') }}:
                    @if ($invoice->createdBy->email)
                        <span class="text-blue">
                            {{$invoice->createdBy->email}}
                        </span>
                    @endif
                </td>
            </tr>

            <tr>
                @foreach ($invoice->deal->contactPerson as $contact_person)
                    <td>
                        {{ $contact_person->email ? __t('email') : ''}}:
                        @foreach ($contact_person->email as $email)
                            @if ($email->value)
                                <span class="text-blue">{{$email->value}}</span>{{!$loop->last ? ',' : ''}}
                            @endif
                        @endforeach
                    </td>
                @endforeach

                @if ($invoice->createdBy->profile)
                    <td>
                       {{__t('contact_no')}}: {{$invoice->createdBy->profile->contact}}
                    </td>
                @endif
            </tr>,
            <tr>
                @foreach ($invoice->deal->contactPerson as $contact_person)
                    <td>
                        @if ($contact_person->phone)
                            @if (count( $contact_person->phone ) >= 1)
                                {{ __t('contact_no') }}:
                            @endif
                            @foreach ($contact_person->phone as $phone)
                                @if ($phone->value)
                                    {{$phone->value}}{{!$loop->last ? ',' : ''}}
                                @endif
                            @endforeach
                        @endif
                    </td>
                @endforeach

                @if ($invoice->createdBy->profile)
                    <td>
                        {{$invoice->createdBy->profile->address}}
                    </td>
                @endif
            </tr>

            @foreach ($invoice->deal->contactPerson as $contact_person)
                @if ($contact_person->organizations)
                    <tr>
                        <td>
                            @if (count($contact_person->organizations) >= 1)
                                {{__t('organization')}}{{count($contact_person->organizations) > 1 ? 's' : ''}}:
                            @endif
                            @foreach ($contact_person->organizations as $org)
                                {{$org->name}}{{!$loop->last ? ',' : '.'}}
                            @endforeach
                        </td>
                    </tr>
                @endif
            @endforeach

            @foreach ($invoice->deal->contactPerson as $contact_person)
                @if ($contact_person->address)
                    <tr>
                        <td>
                            {{$contact_person->address}}
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>

        <!-- INVOICE DATA -->
        <table class="invoice-data">
            <thead>
                <tr>
                    <td>{{__t('deal_name')}}</td>
                    <td>{{__t('quantity')}}</td>
                    <td>{{__t('price')}}</td>
                    <td>{{__t('amount')}}</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="deal-title">
                        {{$invoice->deal->title ?? ''}}
                    </td>
                    <td>
                        {{$invoice->quantity ?? ''}}
                    </td>
                    <td>
                        {{number_with_currency_symbol($invoice->price) ?? 0}}
                    </td>
                    <td>
                        {{number_with_currency_symbol($invoice->quantity * ($invoice->price ?? 0))}}
                    </td>
                </tr>

                @php($tax = 0)
                @if($invoice->tax && $invoice->tax->value)
                @php($tax = ($invoice->quantity * ($invoice->price ?? 0)) * $invoice->tax->value / 100.0)
                <tr>
                    <td>    </td>
                    <td class="text-left">{{__t('tax')}}:</td>
                    <td>    </td>
                    <td>
                        {{number_with_currency_symbol($tax)}}
                    </td>
                </tr>
                @endif

                <tr class="line-container">
                    <td></td>
                    <td class="line-1" colspan="3"></td>
                </tr>

                <tr>
                    <td>    </td>
                    <td class="text-left">{{__t('subtotal')}}:</td>
                    <td>    </td>
                    <td>
                        {{number_with_currency_symbol(($invoice->quantity * ($invoice->price ?? 0))+$tax)}}
                    </td>
                </tr>

                <tr>
                    <td>    </td>
                    <td class="text-left">{{__t('discount')}}:
                        @if ($invoice->discount_type === 'percentage')
                            <span class="text-gray">{{$invoice->discount_amount}}%</span>
                        @endif
                    </td>
                    <td>    </td>
                    <td>
                        @if ($invoice->discount_type === 'percentage')
                            {{
                                number_with_currency_symbol(
                                    (($invoice->discount_amount / 100) * ($invoice->quantity * ($invoice->price ?? 0)))
                                )
                            }}
                        @elseif ($invoice->discount_type === 'fixed')
                            {{number_with_currency_symbol($invoice->discount_amount ?? 0)}}
                        @elseif ($invoice->discount_type === 'none')
                            0.00
                        @endif
                    </td>
                </tr>

                <tr class="line-container">
                    <td></td>
                    <td class="line-2" colspan="3"></td>
                </tr>

                <tr>
                    <td>    </td>
                    <td class="text-left">{{__t('total')}}:</td>
                    <td>    </td>
                    <td class="font-bold">
                        {{number_with_currency_symbol($invoice->total ?? 0)}}
                    </td>
                </tr>
            </tbody>
        </table>

        @if($invoice->note)
            <div class="invoice-footer wrapper">
                <h4>
                    <span>{{__t('note')}}:</span>
                </h4>

                <div>
                    {{ $invoice->note }}
                </div>
            </div>
        @endif
    </div>
</body>
