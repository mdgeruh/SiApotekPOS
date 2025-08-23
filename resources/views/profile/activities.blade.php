@extends('layouts.app')

@section('content')
@include('components.alerts')

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Aktivitas Saya</h1>
        <a href="{{ route('profile') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-fw"></i> Kembali ke Profil
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Aktivitas</h6>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter fa-fw"></i> Filter
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                            <li><a class="dropdown-item" href="#" data-filter="all">Semua Aktivitas</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="login">Login</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="sales">Penjualan</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="profile">Update Profil</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="password">Ganti Password</a></li>
                        </ul>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    @if($activities->count() > 0)
                        <div class="timeline">
                            @foreach($activities as $activity)
                                <div class="timeline-item" data-type="{{ $activity->type ?? 'general' }}">
                                    <div class="timeline-marker {{ $activity->type ?? 'general' }}">
                                        <i class="fas fa-{{ $activity->icon ?? 'circle' }}"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="timeline-header">
                                            <h6 class="timeline-title">{{ $activity->title ?? 'Aktivitas' }}</h6>
                                            <span class="timeline-time">{{ $activity->created_at ?? now() }}</span>
                                        </div>
                                        <div class="timeline-body">
                                            <p>{{ $activity->description ?? 'Deskripsi aktivitas tidak tersedia.' }}</p>
                                            @if(isset($activity->details))
                                                <div class="timeline-details">
                                                    <small class="text-muted">{{ $activity->details }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Sample Activities for Demo -->
                        <div class="timeline">
                            <div class="timeline-item" data-type="login">
                                <div class="timeline-marker login">
                                    <i class="fas fa-sign-in-alt"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <h6 class="timeline-title">Login ke Sistem</h6>
                                        <span class="timeline-time">{{ \App\Helpers\DateHelper::formatDDMMYYYY(now()->subMinutes(5), true) }}</span>
                                    </div>
                                    <div class="timeline-body">
                                        <p>Berhasil login ke sistem POS Apotek</p>
                                        <div class="timeline-details">
                                            <small class="text-muted">IP: 192.168.1.100 | Browser: Chrome 120.0</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="timeline-item" data-type="sales">
                                <div class="timeline-marker sales">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <h6 class="timeline-title">Transaksi Penjualan</h6>
                                        <span class="timeline-time">{{ \App\Helpers\DateHelper::formatDDMMYYYY(now()->subHours(2), true) }}</span>
                                    </div>
                                    <div class="timeline-body">
                                        <p>Membuat transaksi penjualan baru</p>
                                        <div class="timeline-details">
                                            <small class="text-muted">Invoice: INV-001 | Total: Rp 150.000</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="timeline-item" data-type="profile">
                                <div class="timeline-marker profile">
                                    <i class="fas fa-user-edit"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <h6 class="timeline-title">Update Profil</h6>
                                        <span class="timeline-time">{{ \App\Helpers\DateHelper::formatDDMMYYYY(now()->subDays(1), true) }}</span>
                                    </div>
                                    <div class="timeline-body">
                                        <p>Memperbarui informasi profil</p>
                                        <div class="timeline-details">
                                            <small class="text-muted">Field yang diubah: Nama, Nomor Telepon</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="timeline-item" data-type="password">
                                <div class="timeline-marker password">
                                    <i class="fas fa-key"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <h6 class="timeline-title">Ganti Password</h6>
                                        <span class="timeline-time">{{ \App\Helpers\DateHelper::formatDDMMYYYY(now()->subDays(3), true) }}</span>
                                    </div>
                                    <div class="timeline-body">
                                        <p>Berhasil mengubah password akun</p>
                                        <div class="timeline-details">
                                            <small class="text-muted">Password diubah secara aman</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="timeline-item" data-type="general">
                                <div class="timeline-marker general">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <h6 class="timeline-title">Update Pengaturan</h6>
                                        <span class="timeline-time">{{ \App\Helpers\DateHelper::formatDDMMYYYY(now()->subDays(5), true) }}</span>
                                    </div>
                                    <div class="timeline-body">
                                        <p>Mengubah pengaturan notifikasi</p>
                                        <div class="timeline-details">
                                            <small class="text-muted">Notifikasi email: Aktif | Notifikasi SMS: Nonaktif</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterDropdown = document.getElementById('filterDropdown');
    const filterItems = document.querySelectorAll('[data-filter]');
    const timelineItems = document.querySelectorAll('.timeline-item');

    if (filterDropdown) {
        filterItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const filter = this.getAttribute('data-filter');

                // Update dropdown button text
                filterDropdown.innerHTML = `<i class="fas fa-filter fa-fw"></i> ${this.textContent}`;

                // Filter timeline items
                timelineItems.forEach(timelineItem => {
                    const type = timelineItem.getAttribute('data-type');
                    if (filter === 'all' || type === filter) {
                        timelineItem.style.display = 'block';
                        timelineItem.classList.add('fade-in');
                    } else {
                        timelineItem.style.display = 'none';
                        timelineItem.classList.remove('fade-in');
                    }
                });
            });
        });
    }

    // Add animation to timeline items
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, observerOptions);

    timelineItems.forEach(item => {
        observer.observe(item);
    });
});
</script>
@endpush

@push('styles')
<style>
/* Timeline Styles */
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 30px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e3e6f0;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.5s ease;
}

.timeline-item.fade-in {
    opacity: 1;
    transform: translateY(0);
}

.timeline-marker {
    position: absolute;
    left: 20px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 10px;
    z-index: 2;
}

.timeline-marker.login {
    background: #1cc88a;
}

.timeline-marker.sales {
    background: #4e73df;
}

.timeline-marker.profile {
    background: #f6c23e;
}

.timeline-marker.password {
    background: #e74a3b;
}

.timeline-marker.general {
    background: #858796;
}

.timeline-content {
    margin-left: 60px;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-left: 4px solid #4e73df;
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.timeline-title {
    margin: 0;
    color: #5a5c69;
    font-weight: 600;
}

.timeline-time {
    color: #858796;
    font-size: 0.875rem;
}

.timeline-body p {
    margin: 0 0 10px 0;
    color: #5a5c69;
}

.timeline-details {
    padding: 8px 12px;
    background: #f8f9fc;
    border-radius: 4px;
    border-left: 3px solid #4e73df;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .timeline::before {
        left: 20px;
    }

    .timeline-marker {
        left: 10px;
        width: 16px;
        height: 16px;
        font-size: 8px;
    }

    .timeline-content {
        margin-left: 40px;
        padding: 15px;
    }

    .timeline-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .timeline-time {
        margin-top: 5px;
    }
}
</style>
@endpush
