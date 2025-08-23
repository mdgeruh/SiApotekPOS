@extends('layouts.app')

@section('title', 'Transaksi Baru')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Transaksi Baru</h1>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Daftar
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Livewire Component -->
            @livewire('sales.sales-management')
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('css/components/sales-management.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('js/components/sales-management.js') }}"></script>
@endpush
