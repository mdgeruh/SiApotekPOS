@extends('layouts.app')

@section('content')
@include('components.alerts')

<div class="container-fluid">
    <!-- Page Header -->
    @component('components.page-header')
           @endcomponent

    <!-- Breadcrumb -->
    @include('components.breadcrumb')

    <!-- Livewire Component -->
    @livewire('medicine.medicine-management')
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize any additional JavaScript if needed
    console.log('Medicine management page loaded');
});
</script>
@endpush

@endsection
