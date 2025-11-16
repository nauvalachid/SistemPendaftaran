document.addEventListener("DOMContentLoaded", () => {
    const logoutBtn = document.getElementById("logoutButton");

    // Jika tombol logout tidak ada, hentikan eksekusi script. (Sudah benar)
    if (!logoutBtn) return; 

    logoutBtn.addEventListener("click", async (e) => {
        e.preventDefault();
        
        // --- START: Perbaikan ---
        // 1. Ambil elemen meta CSRF
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        
        // 2. Cek apakah elemen meta CSRF ditemukan
        if (!csrfMeta) {
            console.error("Meta tag CSRF tidak ditemukan. Tidak dapat melanjutkan logout.");
            // Optional: Redirect langsung ke halaman utama jika CSRF hilang
            // window.location.href = "/"; 
            return; 
        }

        // 3. Ambil nilai tokennya
        const csrfToken = csrfMeta.getAttribute("content");
        // --- END: Perbaikan ---

        try {
            const response = await fetch("/logout", {
                method: "POST",
                headers: {
                    // Gunakan variabel csrfToken yang sudah dijamin ada
                    "X-CSRF-TOKEN": csrfToken, 
                    "Content-Type": "application/json",
                },
            });

            if (response.ok) {
                window.location.href = "/"; // Redirect ke welcome
            } else {
                console.error("Logout gagal:", response.statusText);
            }
        } catch (error) {
            console.error("Terjadi kesalahan:", error);
        }
    });
});