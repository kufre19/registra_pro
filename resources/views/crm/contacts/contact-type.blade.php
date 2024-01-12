@extends('layouts.crm')
@section('title', app_trans('default.leads').' | '.app_trans('default.lead_groups'))
@section('contents')
    <app-contact-type-list/>
@endsection
