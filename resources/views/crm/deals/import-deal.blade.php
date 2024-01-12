@extends('layouts.crm')
@section('title', app_trans('default.deals').' | '.app_trans('default.import_deals'))
@section('contents')
    <app-deal-import :valid-keys="{{$validKeys}}"></app-deal-import>
@endsection
