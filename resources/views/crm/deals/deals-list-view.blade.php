@extends('layouts.crm')
@section('title', app_trans('default.deals').' | '.app_trans('default.all_deals'))
@section('contents')
    <app-deals-list-view></app-deals-list-view>
@endsection
