@extends('layouts.app')

@section('content')
@include('components.alerts')

<!-- Page Header -->
@component('components.page-header')
@endcomponent

@livewire('sales.stock-report')

@endsection
