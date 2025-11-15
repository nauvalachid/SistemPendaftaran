document.addEventListener("DOMContentLoaded", () => {
    // Pastikan BASE_URL sudah didefinisikan sebagai variabel global 
    // di Blade view/layout Anda (misalnya: <script>const BASE_URL = '{{ url('/') }}';</script>)
    if (typeof BASE_URL === 'undefined') {
        console.error("Kesalahan Konfigurasi: Variabel BASE_URL tidak ditemukan. Mohon cek layout Blade.");
        return;
    }

    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');

    if (!csrfTokenMeta) {
        console.error("CSRF token tidak ditemukan di meta tag.");
        return;
    }

    const csrfToken = csrfTokenMeta.getAttribute("content");

    async function ubahStatus(id, statusBaru) {
        // *** BAGIAN MODIFIKASI: Menggunakan BASE_URL untuk URL absolut ***
        const url = `${BASE_URL}/admin/pendaftaran/${id}/status`;
        
        try {
            const response = await fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    // Tambahkan header ini untuk membantu Laravel mengidentifikasi request AJAX
                    "X-Requested-With": "XMLHttpRequest", 
                },
                body: JSON.stringify({ status: statusBaru }),
            });

            // *** BAGIAN MODIFIKASI: Penanganan Error Autentikasi/CSRF (HTTP 401/419) ***
            if (response.status === 401 || response.status === 419) {
                alert("❌ Sesi login Anda telah berakhir atau token CSRF tidak valid. Silakan refresh halaman atau login kembali.");
                // Jika perlu, arahkan pengguna ke halaman login: 
                // window.location.href = `${BASE_URL}/admin/login`; 
                return;
            }

            if (!response.ok) {
                console.error(`Gagal melakukan request: Status HTTP ${response.status} (${response.statusText})`);
                alert("❌ Gagal mengubah status. Terjadi kesalahan pada server.");
                return;
            }

            // Jika response.ok, maka kita mengharapkan JSON
            const data = await response.json();

            if (data.success) {
                alert(`✅ Status berhasil diubah menjadi: ${data.status}`);
                location.reload();
            } else {
                alert("❌ Gagal mengubah status. Respon server tidak berhasil.");
            }
        } catch (err) {
            console.error("Terjadi kesalahan:", err);
            alert(`❌ Terjadi kesalahan: ${err.message}`);
        }
    }

    const btnSetujui = document.querySelector("#btnSetujui");
    const btnTolak = document.querySelector("#btnTolak");

    if (btnSetujui) {
        btnSetujui.addEventListener("click", () => {
            ubahStatus(btnSetujui.dataset.id, "Diterima");
        });
    }

    if (btnTolak) {
        btnTolak.addEventListener("click", () => {
            ubahStatus(btnTolak.dataset.id, "Ditolak");
        });
    }
});