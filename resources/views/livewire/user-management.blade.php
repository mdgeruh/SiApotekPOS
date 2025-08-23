<div>
    <!-- Header Section -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen User</h1>
        <a href="{{ route('users.create') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 me-1"></i>
            Tambah User Baru
        </a>
    </div>

    <!-- Search and Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">Filter dan Pencarian</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="search" class="form-label fw-bold text-gray-600">Cari User</label>
                        <input type="text" wire:model.debounce.300ms="search" class="form-control"
                               placeholder="Cari berdasarkan nama, username, atau email...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="statusFilter" class="form-label fw-bold text-gray-600">Status</label>
                        <select wire:model="statusFilter" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="roleFilter" class="form-label fw-bold text-gray-600">Role</label>
                        <select wire:model="roleFilter" class="form-select">
                            <option value="">Semua Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <button wire:click="clearFilters" class="btn btn-secondary w-100">
                            <i class="fas fa-times me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 fw-bold text-primary">Daftar User</h6>
            <div class="dropdown">
                <button class="btn btn-link text-gray-400 p-0" type="button" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow animated--fade-in"
                    aria-labelledby="dropdownMenuButton">
                    <li><h6 class="dropdown-header">Aksi:</h6></li>
                    <li><a class="dropdown-item" href="{{ route('users.create') }}">
                        <i class="fas fa-plus fa-sm fa-fw me-2 text-gray-400"></i>
                        Tambah User
                    </a></li>
                    <li><a class="dropdown-item" href="#" onclick="exportUsers()">
                        <i class="fas fa-download fa-sm fa-fw me-2 text-gray-400"></i>
                        Export Excel
                    </a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="usersTable" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th class="text-center" width="8%">Foto</th>
                                <th width="15%">Nama</th>
                                <th width="12%">Username</th>
                                <th width="20%">Email</th>
                                <th class="text-center" width="10%">Role</th>
                                <th class="text-center" width="10%">Status</th>
                                <th class="text-center" width="12%">Tanggal Dibuat</th>
                                <th class="text-center" width="8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">
                                        @if($user->profile_photo_path)
                                            <img src="{{ Storage::url($user->profile_photo_path) }}"
                                                 alt="Profile Photo" class="rounded-circle border"
                                                 width="40" height="40">
                                        @else
                                            <img src="{{ asset('images/default-avatar.svg') }}"
                                                 alt="Default Avatar" class="rounded-circle border"
                                                 width="40" height="40">
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-gray-800">{{ $user->name }}</div>
                                    </td>
                                    <td>
                                        <code class="text-primary">{{ $user->username }}</code>
                                    </td>
                                    <td>
                                        <div class="text-gray-700">{{ $user->email }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $user->role->name === 'Admin' ? 'danger' : ($user->role->name === 'Kasir' ? 'success' : 'info') }}">
                                            {{ $user->role->name }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ $user->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <small class="text-muted">{{ \App\Helpers\DateHelper::formatDDMMYYYY($user->created_at, true) }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('users.show', $user->id) }}"
                                               class="btn btn-info btn-sm me-1" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('users.edit', $user->id) }}"
                                               class="btn btn-warning btn-sm me-1" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-{{ $user->status === 'active' ? 'secondary' : 'success' }} btn-sm me-1"
                                                        title="{{ $user->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                        onclick="return confirm('Apakah Anda yakin ingin {{ $user->status === 'active' ? 'menonaktifkan' : 'mengaktifkan' }} user ini?')">
                                                    <i class="fas fa-{{ $user->status === 'active' ? 'ban' : 'check' }}"></i>
                                                </button>
                                            </form>
                                            <button onclick="showDeleteConfirmation('{{ route('users.destroy', $user->id) }}', '{{ $user->name }}', 'Apakah Anda yakin ingin menghapus user "{{ $user->name }}"?\n\nTindakan ini tidak dapat dibatalkan.')"
                                                    class="btn btn-danger btn-sm" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-500">Tidak ada user ditemukan</h5>
                    <p class="text-gray-400">Mulai dengan menambahkan user pertama</p>
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah User Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>



    <!-- Export Function -->
    <script>
        function exportUsers() {
            // Implementation for Excel export
            alert('Fitur export akan diimplementasikan');
        }


    </script>
</div>
