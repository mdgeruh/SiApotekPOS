@extends('layouts.app')

@section('content')
    <!-- Alert Messages -->
    @include('components.alerts')

    <!-- Page Header -->
    @component('components.page-header')
    @endcomponent

    @livewire('user-management')
@endsection
