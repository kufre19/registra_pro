@extends('layouts.crm')
@section('title', app_trans('default.person'))
@section('contents')
    <app-contact-person-details @if(isset($id)) selected-url="{{route('persons.show', $id)}}" @endif/>
@endsection
