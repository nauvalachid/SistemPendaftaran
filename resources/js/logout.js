document.addEventListener("DOMContentLoaded", () => {
    const logoutBtn = document.getElementById("logoutButton");

    if (!logoutBtn) return;

    logoutBtn.addEventListener("click", async (e) => {
        e.preventDefault();

        try {
            const response = await fetch("/logout", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
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
