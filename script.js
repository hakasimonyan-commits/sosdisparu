document.addEventListener("DOMContentLoaded", () => {

    // ===== LOGOUT MODAL =====
    const logoutBtn = document.getElementById("logoutBtn");
    const modal = document.getElementById("logoutModal");
    const cancelBtn = document.getElementById("cancelLogout");

    if (logoutBtn && modal) {
        logoutBtn.addEventListener("click", (e) => {
            e.preventDefault();
            modal.classList.add("show");
        });
    }

    if (cancelBtn && modal) {
        cancelBtn.addEventListener("click", () => {
            modal.classList.remove("show");
        });
    }

    if (modal) {
        modal.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.classList.remove("show");
            }
        });
    }

    // ===== PHONE INPUT =====
    const phoneInput = document.getElementById("phone");
    if (phoneInput) {
        phoneInput.addEventListener("input", () => {
            phoneInput.value = phoneInput.value.replace(/[^0-9]/g, '');
        });
    }

});

// ===== TOGGLE PASSWORD =====
function togglePassword(inputId, icon) {
    const input = document.getElementById(inputId);
    if (!input) return;

    if (input.type === "password") {
        input.type = "text";
        icon.textContent = "👁";
    } else {
        input.type = "password";
        icon.textContent = "🙈";
    }
}


