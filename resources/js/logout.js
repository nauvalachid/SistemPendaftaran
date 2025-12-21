document.addEventListener("DOMContentLoaded", () => {
    const logoutBtn = document.getElementById("logoutButton");

    if (!logoutBtn) return; 

    logoutBtn.addEventListener("click", async (e) => {
        e.preventDefault();
        
        // 1. Ambil CSRF Token dengan cara yang lebih aman
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");
        
        if (!csrfToken) {
            console.error("CSRF token not found.");
            // Jika CSRF hilang, biasanya session expired, arahkan saja ke login
            window.location.href = "/login";
            return; 
        }

        // 2. Cegah double-click (Optimasi UX)
        logoutBtn.disabled = true;
        const originalText = logoutBtn.innerHTML;
        logoutBtn.innerHTML = "Logging out..."; 

        try {
            const response = await fetch("/logout", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken, 
                    "Content-Type": "application/json",
                    "Accept": "application/json", // Penting agar Laravel kirim JSON jika error
                },
            });

            if (response.ok) {
                // Berhasil: Hapus data sensitif di sisi client jika ada (localStorage/sessionStorage)
                window.location.replace("/"); // replace lebih baik agar user tidak bisa 'Back' ke admin
            } else {
                // Jika error 419 (CSRF expired), langsung redirect saja
                if (response.status === 419) {
                    window.location.href = "/";
                } else {
                    throw new Error("Server responded with " + response.status);
                }
            }
        } catch (error) {
            console.error("Logout error:", error);
            // Kembalikan tombol jika gagal agar user bisa coba lagi
            logoutBtn.disabled = false;
            logoutBtn.innerHTML = originalText;
            alert("Gagal logout. Silakan coba lagi atau refresh halaman.");
        }
    });
});