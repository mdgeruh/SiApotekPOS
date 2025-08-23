@extends('layouts.app')

@section('content')
@include('components.alerts')

<!-- Page Header -->
@component('components.page-header')
@endcomponent

@livewire('sales.statistics-report')

@endsection
