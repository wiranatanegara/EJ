document.addEventListener("DOMContentLoaded", function () {
    // Contoh: Validasi kosong pada textarea form
    const form1 = document.querySelector('form[action="proses1.php"]');
    const form2 = document.querySelector('form[action="proses2.php"]');

    if (form1) {
        form1.addEventListener("submit", function (e) {
            const textarea = form1.querySelector("textarea");
            if (!textarea.value.trim()) {
                alert("Form 1 tidak boleh kosong!");
                e.preventDefault();
            }
        });
    }

    if (form2) {
        form2.addEventListener("submit", function (e) {
            const textarea = form2.querySelector("textarea");
            if (!textarea.value.trim()) {
                alert("Form 2 tidak boleh kosong!");
                e.preventDefault();
            }
        });
    }

    // Contoh tambahan: tombol refresh halaman
    const refreshBtn = document.getElementById("refreshBtn");
    if (refreshBtn) {
        refreshBtn.addEventListener("click", () => {
            window.location.reload();
        });
    }
});
