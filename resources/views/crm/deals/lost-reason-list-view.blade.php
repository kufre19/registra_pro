@extends('layouts.crm')
@section('title', app_trans('default.deals').' | '.app_trans('default.lost_reasons'))
@section('contents')
    <app-lost-reason-list/>
@endsection
