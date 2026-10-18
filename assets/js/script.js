// =========================================
// CABGO - GLOBAL JAVASCRIPT
// =========================================


// =========================================
// AUTO HIDE ALERTS
// =========================================

document.addEventListener("DOMContentLoaded", function () {


    const alerts = document.querySelectorAll(".alert");


    alerts.forEach(function (alert) {


        setTimeout(function () {


            alert.style.transition = "opacity 0.5s";


            alert.style.opacity = "0";


            setTimeout(function () {

                alert.remove();

            }, 500);


        }, 4000);


    });


});


// =========================================
// DELETE CONFIRMATION
// =========================================

function confirmDelete(message = "Are you sure you want to delete this item?") {


    return confirm(message);


}


// =========================================
// FORM SUBMISSION PREVENTION
// Prevent double clicking submit button
// =========================================

document.addEventListener("submit", function (event) {


    const form = event.target;


    const submitButton = form.querySelector(

        'button[type="submit"]'

    );


    if (submitButton) {


        submitButton.disabled = true;


        const originalText = submitButton.innerHTML;


        submitButton.innerHTML =

            '<span class="spinner-border spinner-border-sm"></span> Processing...';


        // Restore button after 5 seconds
        // Useful if server validation fails

        setTimeout(function () {


            submitButton.disabled = false;

            submitButton.innerHTML = originalText;


        }, 5000);


    }


});


// =========================================
// PASSWORD SHOW / HIDE
// =========================================

function togglePassword(inputId, iconId) {


    const passwordInput = document.getElementById(inputId);


    const icon = document.getElementById(iconId);


    if (!passwordInput) return;


    if (passwordInput.type === "password") {


        passwordInput.type = "text";


        if (icon) {

            icon.classList.remove("bi-eye");

            icon.classList.add("bi-eye-slash");

        }


    } else {


        passwordInput.type = "password";


        if (icon) {

            icon.classList.remove("bi-eye-slash");

            icon.classList.add("bi-eye");

        }


    }


}


// =========================================
// ACTIVE NAVIGATION LINK
// =========================================

document.addEventListener("DOMContentLoaded", function () {


    const currentPage = window.location.pathname;


    const navLinks = document.querySelectorAll(

        ".navbar .nav-link"

    );


    navLinks.forEach(function (link) {


        if (

            link.getAttribute("href") &&
            currentPage.includes(link.getAttribute("href"))

        ) {


            link.classList.add("active");


        }


    });


});