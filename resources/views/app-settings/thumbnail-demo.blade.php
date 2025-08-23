@extends('layouts.app')

@section('title', 'Thumbnail Demo')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/components/app-settings.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Logo Thumbnail</h5>
                </div>
                <div class="card-body">
                    <div class="upload-area">
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="upload-text">Upload Logo</div>
                        <div class="upload-hint">PNG, JPG, GIF, SVG</div>
                    </div>

                    <!-- Demo thumbnail lingkaran -->
                    <div class="thumbnail-container">
                        <img src="{{ asset('images/default-logo.svg') }}"
                             alt="Logo Demo"
                             class="thumbnail-circle">
                        <div class="thumbnail-info">
                            <div class="thumbnail-label">Logo saat ini</div>
                            <div class="thumbnail-actions">
                                <button class="btn btn-sm btn-outline-primary btn-thumbnail">
                                    <i class="fas fa-eye"></i> Lihat
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-thumbnail">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Favicon Thumbnail</h5>
                </div>
                <div class="card-body">
                    <div class="upload-area">
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="upload-text">Upload Favicon</div>
                        <div class="upload-hint">ICO, PNG, JPG, JPEG</div>
                    </div>

                    <!-- Demo thumbnail lingkaran -->
                    <div class="thumbnail-container">
                        <img src="{{ asset('images/default-favicon.svg') }}"
                             alt="Favicon Demo"
                             class="thumbnail-circle">
                        <div class="thumbnail-info">
                            <div class="thumbnail-label">Favicon saat ini</div>
                            <div class="thumbnail-actions">
                                <button class="btn btn-sm btn-outline-primary btn-thumbnail">
                                    <i class="fas fa-eye"></i> Lihat
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-thumbnail">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
