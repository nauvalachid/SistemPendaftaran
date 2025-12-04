document.addEventListener('DOMContentLoaded', () => {
    // --- LOGIKA UNTUK CUSTOM DROPDOWN ---
    const dropdowns = document.querySelectorAll('.custom-select-container');
    const filterForm = document.getElementById('filterForm');

    dropdowns.forEach(dropdown => {
        const trigger = dropdown.querySelector('.custom-select-trigger');
        const hiddenInput = dropdown.querySelector('input[type="hidden"]');
        const options = dropdown.querySelectorAll('.custom-select-option');

        trigger.addEventListener('click', (e) => {
            // Tutup dropdown lain
            dropdowns.forEach(other => { if (other !== dropdown) other.classList.remove('open'); });
            dropdown.classList.toggle('open');
            e.stopPropagation();
        });

        options.forEach(option => {
            option.addEventListener('click', (e) => {
                const value = option.getAttribute('data-value');
                const text = option.innerText;
                
                // Update tampilan dan nilai input tersembunyi
                trigger.querySelector('span').innerText = text;
                hiddenInput.value = value;
                
                // Tutup dropdown dan set kelas selected
                dropdown.classList.remove('open');
                options.forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');
                
                // Submit form filter
                if (filterForm) {
                    filterForm.submit();
                }
                
                e.stopPropagation();
            });
        });
    });

    // Menutup dropdown saat klik di luar
    window.addEventListener('click', () => {
        dropdowns.forEach(dropdown => dropdown.classList.remove('open'));
    });


    // --- LOGIKA SORTING ---
    const hiddenSortBy = document.getElementById('hiddenSortBy');
    const toggleSortNama = document.getElementById('toggleSortNama');
    const toggleSortTanggal = document.getElementById('toggleSortTanggal');

    if (toggleSortNama && hiddenSortBy && filterForm) {
        toggleSortNama.addEventListener('click', () => {
            const current = hiddenSortBy.value;
            // Toggle antara 'nama_asc' dan 'nama_desc'
            hiddenSortBy.value = (current === 'nama_asc') ? 'nama_desc' : 'nama_asc';
            filterForm.submit();
        });
    }

    if (toggleSortTanggal && hiddenSortBy && filterForm) {
        toggleSortTanggal.addEventListener('click', () => {
            const current = hiddenSortBy.value;
            // Toggle antara 'tanggal_desc' (default) dan 'tanggal_asc'
            // Di sini saya menganggap 'tanggal_desc' adalah default/prioritas pertama
            hiddenSortBy.value = (current === 'tanggal_desc') ? 'tanggal_asc' : 'tanggal_desc';
            filterForm.submit();
        });
    }
});