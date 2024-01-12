@extends('layouts.crm')
@section('title', app_trans('default.organizations').' | '.app_trans('default.import'))
@section('contents')
    <app-organization-import :valid-keys="{{$validKeys}}"/>
@endsection
