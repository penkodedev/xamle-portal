// JavaScript functions for login page

function togglePasswordVisibility(event) {
    event.preventDefault();
    let passwordInput = document.getElementById('user_pass');
    let showPasswordLink = document.querySelector('.show-pass a');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        showPasswordLink.textContent = 'Hide Password';
    } else {
        passwordInput.type = 'password';
        showPasswordLink.textContent = 'Show Password';
    }
}

function updateRemainingTime() {
    let remainingTimeElement = document.getElementById('remaining-time');
    if (remainingTimeElement) {
        let remainingTime = parseInt(remainingTimeElement.innerText, 10);

        if (remainingTime > 0) {
            remainingTime--;
            remainingTimeElement.innerText = remainingTime.toString();
            setTimeout(updateRemainingTime, 1000);
        } else {
            let errorDiv = document.getElementById('error-message');
            if (errorDiv) {
                errorDiv.style.display = 'none';
            }
        }
    }
}

// Combine both DOMContentLoaded event listeners
document.addEventListener('DOMContentLoaded', function() {
    updateRemainingTime();

    let emailErrorDiv = document.querySelector('.email-valid-error');
    if (emailErrorDiv) {
        emailErrorDiv.addEventListener('click', function() {
            emailErrorDiv.style.display = 'none';
        });
    }
});
