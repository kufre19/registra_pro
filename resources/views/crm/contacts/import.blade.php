@extends('layouts.crm')
@section('title', app_trans('default.persons').' | '.app_trans('default.import'))
@section('contents')
    <app-person-import :valid-keys="{{$validKeys}}"/>
@endsection
