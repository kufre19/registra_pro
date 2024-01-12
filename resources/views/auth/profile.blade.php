@extends('layouts.crm')
@section('title', app_trans('default.profile').' | '.auth()->user()->full_name)
@section('contents')
    <app-my-profile @if(env('MARKET_PLACE_VERSION')) :market-place-version="{{env('MARKET_PLACE_VERSION')}}" @endif/>
@endsection
