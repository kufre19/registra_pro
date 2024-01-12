@extends('layouts.crm')
@section('title', app_trans('default.email'))
@section('contents')
    @csrf
    <email-inbox></email-inbox>
@endsection
