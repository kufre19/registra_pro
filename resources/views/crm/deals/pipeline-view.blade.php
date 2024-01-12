@extends('layouts.crm')
@section('title', app_trans('default.deals').' | '.app_trans('default.pipeline_view'))
@section('contents')
<app-kanban-view @if(isset($_GET['pipeline'])) pipeline="@php echo $_GET['pipeline']; @endphp " @endif @if(isset($_GET['highlights'])) highlights="@php echo $_GET['highlights']; @endphp " @endif>

</app-kanban-view>
@endsection
