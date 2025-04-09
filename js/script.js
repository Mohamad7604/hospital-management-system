// Wait for the DOM to fully load before running scripts
document.addEventListener("DOMContentLoaded", function() {
    
    // Toggle Mobile Menu (if you implement a mobile nav)
    const menuToggle = document.getElementById("menu-toggle");
    if (menuToggle) {
        menuToggle.addEventListener("click", function() {
            const nav = document.querySelector("nav");
            if (nav) {
                nav.classList.toggle("open");
            }
        });
    }
    
    // Example: Basic form validation for the login form
    const loginForm = document.querySelector("form#login-form");
    if (loginForm) {
        loginForm.addEventListener("submit", function(e) {
            const emailInput = loginForm.querySelector('input[name="email"]');
            const passwordInput = loginForm.querySelector('input[name="password"]');
            
            if (!emailInput.value || !passwordInput.value) {
                e.preventDefault();
                alert("Please fill in both your email and password.");
            }
        });
    }
    
    // Example: Show/Hide Password Toggle
    const togglePassword = document.getElementById("toggle-password");
    if (togglePassword) {
        togglePassword.addEventListener("click", function() {
            const passwordField = document.querySelector('input[name="password"]');
            if (passwordField) {
                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    togglePassword.innerText = "Hide";
                } else {
                    passwordField.type = "password";
                    togglePassword.innerText = "Show";
                }
            }
        });
    }
    
    // Additional JavaScript functionality can be added here.
});