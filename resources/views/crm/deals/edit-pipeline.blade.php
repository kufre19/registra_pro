@extends('layouts.crm')
@section('title', app_trans('default.deals').' | '.app_trans('default.edit_pipeline'))
@section('contents')
    <app-edit-pipeline @if(isset($id)) selected-url="{{route('pipelines.show', $id)}}" @endif/>
@endsection
