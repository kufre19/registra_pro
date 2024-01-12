@extends('layouts.crm')
@section('title', app_trans('default.deals').' | '.app_trans('default.import_pipelines'))
@section('contents')
    <app-import-pipeline/>
@endsection
