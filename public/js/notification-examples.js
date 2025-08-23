/**
 * Contoh Penggunaan Sistem Notifikasi Global SiApotekPOS
 * File ini berisi contoh-contoh implementasi untuk berbagai skenario
 */

// Contoh 1: Notifikasi Dasar
function contohNotifikasiDasar() {
    // Success notification
    window.showSuccess('Data berhasil disimpan!');

    // Error notification
    window.showError('Terjadi kesalahan sistem');

    // Warning notification
    window.showWarning('Data akan dihapus permanen');

    // Info notification
    window.showInfo('Fitur akan segera tersedia');
}

// Contoh 2: Notifikasi dengan Title
function contohNotifikasiDenganTitle() {
    window.showSuccess('Data berhasil disimpan!', 'Berhasil');
    window.showError('Terjadi kesalahan', 'Error');
    window.showWarning('Perhatian', 'Peringatan');
    window.showInfo('Informasi penting', 'Info');
}

// Contoh 3: Notifikasi dengan Durasi Kustom
function contohNotifikasiDenganDurasi() {
    // Success notification dengan durasi 3 detik
    window.showSuccess('Data berhasil disimpan!', null, 3000);

    // Error notification dengan durasi 10 detik
    window.showError('Error penting', null, 10000);

    // Info notification dengan durasi 2 detik
    window.showInfo('Pesan singkat', null, 2000);
}

// Contoh 4: Dialog Konfirmasi
function contohDialogKonfirmasi() {
    window.showConfirm(
        'Apakah Anda yakin ingin menghapus data ini?',
        function() {
            // Callback ketika user klik "Ya, Lanjutkan"
            console.log('User mengkonfirmasi');
            window.showSuccess('Data berhasil dihapus!');
            // Lakukan proses hapus data
            deleteData();
        },
        function() {
            // Callback ketika user klik "Batal"
            console.log('User membatalkan');
            window.showInfo('Operasi dibatalkan');
        },
        'Konfirmasi Hapus'
    );
}

// Contoh 5: Dialog Input
function contohDialogInput() {
    window.showPrompt(
        'Masukkan nama kategori baru:',
        'Kategori Default',
        function(value) {
            // Callback ketika user input dan klik OK
            console.log('User input:', value);
            if (value.trim()) {
                window.showSuccess(`Kategori "${value}" berhasil dibuat!`);
                createCategory(value);
            } else {
                window.showError('Nama kategori tidak boleh kosong!');
            }
        },
        function() {
            // Callback ketika user klik Batal
            console.log('User membatalkan input');
            window.showInfo('Input dibatalkan');
        },
        'Input Kategori'
    );
}

// Contoh 6: Handling AJAX Response
function contohAjaxResponse() {
    // Simulasi AJAX request
    $.ajax({
        url: '/api/categories',
        method: 'POST',
        data: { name: 'Kategori Baru' },
        success: function(response) {
            // Gunakan fungsi helper untuk response AJAX
            window.notificationSystem.showAjaxResponse(response, 'Kategori berhasil dibuat!');

            // Atau gunakan fungsi individual
            if (response.success) {
                window.showSuccess(response.message);
                // Refresh halaman atau update UI
                location.reload();
            } else {
                window.showError(response.message);
            }
        },
        error: function(xhr) {
            // Handle error dengan exception handler
            window.notificationSystem.showException(xhr, 'Gagal membuat kategori');
        }
    });
}

// Contoh 7: Handling Exception
function contohExceptionHandler() {
    try {
        // Simulasi operasi yang bisa error
        throw new Error('Data tidak ditemukan');
    } catch (error) {
        // Handle exception dengan exception handler
        window.notificationSystem.showException(error, 'Terjadi kesalahan sistem');

        // Atau gunakan fungsi global
        window.showError('Terjadi kesalahan: ' + error.message);
    }
}

// Contoh 8: Notifikasi dari Livewire Event
function contohLivewireEvents() {
    // Listen untuk event dari Livewire
    Livewire.on('show-success', (message) => {
        window.showSuccess(message);
    });

    Livewire.on('show-error', (message) => {
        window.showError(message);
    });

    Livewire.on('show-warning', (message) => {
        window.showWarning(message);
    });

    Livewire.on('show-info', (message) => {
        window.showInfo(message);
    });
}

// Contoh 9: Notifikasi dengan Auto-refresh
function contohNotifikasiAutoRefresh() {
    // Notifikasi yang akan refresh halaman setelah 2 detik
    window.showSuccess('Data berhasil disimpan! Halaman akan di-refresh...', 'Berhasil', 2000);

    setTimeout(() => {
        location.reload();
    }, 2000);
}

// Contoh 10: Notifikasi dengan Callback
function contohNotifikasiDenganCallback() {
    // Notifikasi success dengan callback setelah ditutup
    const notification = window.showSuccess('Data berhasil disimpan!', 'Berhasil');

    // Simulasi callback setelah notifikasi ditutup
    setTimeout(() => {
        console.log('Notifikasi ditutup, lakukan aksi tambahan');
        // Lakukan aksi tambahan setelah notifikasi ditutup
        updateUI();
    }, 5000);
}

// Contoh 11: Notifikasi untuk Form Validation
function contohFormValidation() {
    // Validasi form
    const name = document.getElementById('categoryName').value.trim();
    const description = document.getElementById('categoryDescription').value.trim();

    if (!name) {
        window.showError('Nama kategori harus diisi!', 'Validasi Error');
        return false;
    }

    if (!description) {
        window.showWarning('Deskripsi kategori sebaiknya diisi', 'Peringatan');
        // Bisa lanjut atau tidak tergantung business logic
    }

    // Jika semua valid, tampilkan success
    window.showSuccess('Form valid, data akan disimpan...', 'Validasi Berhasil');
    return true;
}

// Contoh 12: Notifikasi untuk File Upload
function contohFileUpload() {
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];

    if (!file) {
        window.showError('Pilih file terlebih dahulu!', 'File Error');
        return;
    }

    // Check file size
    if (file.size > 5 * 1024 * 1024) { // 5MB
        window.showError('Ukuran file terlalu besar! Maksimal 5MB', 'File Error');
        return;
    }

    // Check file type
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!allowedTypes.includes(file.type)) {
        window.showError('Tipe file tidak didukung! Gunakan JPG, PNG, atau GIF', 'File Error');
        return;
    }

    window.showSuccess('File valid, akan diupload...', 'File Valid');
    // Lakukan upload file
}

// Contoh 13: Notifikasi untuk Search
function contohSearchNotification() {
    const query = document.getElementById('searchInput').value.trim();

    if (!query) {
        window.showWarning('Masukkan kata kunci pencarian', 'Peringatan');
        return;
    }

    // Show loading notification
    window.showInfo(`Mencari: "${query}"...`, 'Pencarian');

    // Simulasi search
    setTimeout(() => {
        const results = Math.floor(Math.random() * 100);
        if (results > 0) {
            window.showSuccess(`Ditemukan ${results} hasil untuk "${query}"`, 'Hasil Pencarian');
        } else {
            window.showWarning(`Tidak ada hasil untuk "${query}"`, 'Hasil Pencarian');
        }
    }, 2000);
}

// Contoh 14: Notifikasi untuk Bulk Operations
function contohBulkOperations() {
    const selectedItems = document.querySelectorAll('input[name="selected[]"]:checked');

    if (selectedItems.length === 0) {
        window.showWarning('Pilih item yang akan dihapus terlebih dahulu', 'Peringatan');
        return;
    }

    window.showConfirm(
        `Apakah Anda yakin ingin menghapus ${selectedItems.length} item yang dipilih?`,
        function() {
            // Proses bulk delete
            window.showInfo(`Menghapus ${selectedItems.length} item...`, 'Proses');

            setTimeout(() => {
                window.showSuccess(`${selectedItems.length} item berhasil dihapus!`, 'Berhasil');
                // Refresh halaman atau update UI
                location.reload();
            }, 1500);
        },
        function() {
            window.showInfo('Operasi bulk delete dibatalkan', 'Dibatalkan');
        },
        'Konfirmasi Bulk Delete'
    );
}

// Contoh 15: Notifikasi untuk Real-time Updates
function contohRealTimeUpdates() {
    // Simulasi real-time updates
    setInterval(() => {
        const randomEvent = Math.floor(Math.random() * 4);
        const messages = [
            'Ada pembaruan data baru',
            'Sistem akan maintenance dalam 5 menit',
            'Backup database berhasil',
            'Ada user baru login'
        ];

        const types = ['info', 'warning', 'success', 'info'];
        const titles = ['Update', 'Maintenance', 'Backup', 'User Activity'];

        window.showNotification(messages[randomEvent], types[randomEvent], titles[randomEvent]);
    }, 30000); // Setiap 30 detik
}

// Fungsi helper untuk simulasi
function deleteData() {
    console.log('Data dihapus...');
}

function createCategory(name) {
    console.log('Kategori dibuat:', name);
}

function updateUI() {
    console.log('UI diupdate...');
}

// Export fungsi-fungsi contoh
window.notificationExamples = {
    dasar: contohNotifikasiDasar,
    denganTitle: contohNotifikasiDenganTitle,
    denganDurasi: contohNotifikasiDenganDurasi,
    konfirmasi: contohDialogKonfirmasi,
    input: contohDialogInput,
    ajax: contohAjaxResponse,
    exception: contohExceptionHandler,
    livewire: contohLivewireEvents,
    autoRefresh: contohNotifikasiAutoRefresh,
    denganCallback: contohNotifikasiDenganCallback,
    formValidation: contohFormValidation,
    fileUpload: contohFileUpload,
    search: contohSearchNotification,
    bulkOperations: contohBulkOperations,
    realTime: contohRealTimeUpdates
};

// Auto-run beberapa contoh saat halaman dimuat (untuk demo)
document.addEventListener('DOMContentLoaded', function() {
    // Uncomment baris di bawah untuk menjalankan contoh secara otomatis
    // window.notificationExamples.dasar();

    console.log('Sistem notifikasi global siap digunakan!');
    console.log('Gunakan window.notificationExamples untuk melihat contoh-contoh');
});
