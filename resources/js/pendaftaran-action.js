/**
 * Fungsi global untuk menangani aksi Setujui dan Tolak pendaftaran.
 * Ini menggunakan Fetch API untuk mengirim permintaan AJAX.
 *
 * @param {HTMLElement} btn - Elemen tombol yang diklik.
 * @param {string} url - URL endpoint untuk aksi (approve/reject).
 * @param {string} type - Tipe aksi ('setuju' atau 'tolak').
 */
window.handleAction = function(btn, url, type) {
    if (btn.disabled) return;
    
    // Pastikan meta tag CSRF tersedia
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        alert('Kesalahan konfigurasi: Token CSRF tidak ditemukan.');
        return;
    }

    const label = type === 'setuju' ? 'Menerima' : 'Menolak';
    
    if (!confirm(`Anda yakin ingin ${label} pendaftar ini?`)) return;

    // Tampilkan loading state
    const originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(res => {
        // Cek jika response tidak ok (misal 4xx atau 5xx)
        if (!res.ok) {
            return res.json().then(error => {
                throw new Error(error.message || 'Aksi gagal dilakukan.');
            });
        }
        return res.json();
    })
    .then(data => {
        if (data.success) {
            // Reload halaman setelah sukses
            window.location.reload();
        } else {
            // Tangani kegagalan dari server (jika 'success' false)
            alert('Gagal: ' + (data.message || 'Terjadi kesalahan.'));
            btn.disabled = false;
            btn.innerHTML = originalContent;
        }
    })
    .catch(err => {
        console.error('Fetch error:', err);
        alert('Terjadi kesalahan server: ' + err.message);
        // Kembalikan tombol ke keadaan semula jika ada error
        btn.disabled = false;
        btn.innerHTML = originalContent;
    });
}