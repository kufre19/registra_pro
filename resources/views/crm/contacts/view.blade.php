@extends('layouts.crm')
@section('title', app_trans('leads').' | '.app_trans('organizations'))
@section('contents')
    <app-organization-view @if(isset($id)) selected-url="{{route('organizations.show', $id)}}" @endif/>
@endsection
