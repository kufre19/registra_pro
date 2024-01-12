@extends('layouts.crm')
@section('title', app_trans('default.activities').' | '.app_trans('default.list_view'))
@section('contents')
    <app-activities-list-view></app-activities-list-view>
@endsection
