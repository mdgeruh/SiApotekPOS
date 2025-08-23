@extends('layouts.app')

@section('title', 'Manajemen Merk Obat')

@section('content')
@include('components.alerts')

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Merk Obat</h1>
        <a href="{{ route('brands.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus fa-sm"></i> Tambah Merk Baru
        </a>
    </div>

    <!-- Brands Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Merk Obat</h6>
            <span class="badge badge-primary">{{ $brands->total() }} Total Merk</span>
        </div>
        <div class="card-body">
            @if($brands->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="brandsTable">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama Merk</th>
                                <th>Deskripsi</th>
                                <th>Negara</th>
                                <th>Status</th>
                                <th>Jumlah Obat</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brands as $brand)
                                <tr>
                                    <td>
                                        <strong>{{ $brand->name }}</strong>
                                    </td>
                                    <td>
                                        @if($brand->description)
                                            {{ Str::limit($brand->description, 50) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($brand->country)
                                            <span class="badge badge-info">{{ $brand->country }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($brand->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{ $brand->medicines->count() }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ \App\Helpers\DateHelper::formatDDMMYYYY($brand->created_at) }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('brands.edit', $brand) }}"
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('brands.toggle-status', $brand) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-{{ $brand->is_active ? 'warning' : 'success' }} btn-sm"
                                                        title="{{ $brand->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="fas fa-{{ $brand->is_active ? 'ban' : 'check' }}"></i>
                                                </button>
                                            </form>
                                            <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    title="Hapus"
                                                    onclick="showDeleteConfirmation('{{ route('brands.destroy', $brand) }}', '{{ $brand->name }}', 'Yakin ingin menghapus merk "{{ $brand->name }}"?\n\nData merk yang dihapus tidak dapat dikembalikan.')"
                                                    {{ $brand->medicines->count() > 0 ? 'disabled' : '' }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $brands->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada merk obat ditemukan</h5>
                    <p class="text-muted">Mulai dengan menambahkan merk obat baru.</p>
                    <a href="{{ route('brands.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Merk Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#brandsTable').DataTable({
        "pageLength": 25,
        "order": [[0, "asc"]], // Sort by name by default
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});
</script>
@endpush
@endsection
