    document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('passwordInput');
    const passwordConfirmationInput = document.getElementById('passwordConfirmationInput');
    const passwordError = document.getElementById('passwordError');
    const passwordSuccess = document.getElementById('passwordSuccess');
    const passwordConfirmationError = document.getElementById('passwordConfirmationError');
    const passwordConfirmationSuccess = document.getElementById('passwordConfirmationSuccess');

    passwordInput.addEventListener('input', () => {
        const passwordValue = passwordInput.value;

        // Check if password contains at least one uppercase letter, one digit, and one special character
        const uppercaseRegex = /[A-Z]/;
        const digitRegex = /[0-9]/;
        const specialCharRegex = /[!@#$%^&*(),.?":{}|<>]/;

        const isUppercasePresent = uppercaseRegex.test(passwordValue);
        const isDigitPresent = digitRegex.test(passwordValue);
        const isSpecialCharPresent = specialCharRegex.test(passwordValue);

        // Password length check (minimum 8 characters)
        const isLengthValid = passwordValue.length >= 8;

        // Update error message based on validation results
        if (!isLengthValid || !isUppercasePresent || !isDigitPresent || !isSpecialCharPresent) {
            passwordError.style.display = 'block';
            passwordError.textContent = 'Password harus minimal 8 karakter dan mengandung huruf besar, angka, serta karakter khusus.';
            passwordSuccess.style.display = 'none';
        } else {
            passwordError.style.display = 'none';
            passwordError.textContent = '';
            passwordSuccess.style.display = 'block';
            passwordSuccess.textContent = 'Password memenuhi kriteria.';
        }

        // Check if passwords match (optional)
        if (passwordConfirmationInput.value !== passwordValue) {
            passwordConfirmationError.style.display = 'block';
            passwordConfirmationError.textContent = 'Password tidak cocok.';
            passwordConfirmationSuccess.style.display = 'none';
        } else {
            passwordConfirmationError.style.display = 'none';
            passwordConfirmationError.textContent = '';
            passwordConfirmationSuccess.style.display = 'block';
            passwordConfirmationSuccess.textContent = 'Password cocok.';
        }
    });

    // Additional event listener for password confirmation check
    passwordConfirmationInput.addEventListener('input', () => {
        if (passwordConfirmationInput.value !== passwordInput.value) {
            passwordConfirmationError.style.display = 'block';
            passwordConfirmationError.textContent = 'Password tidak cocok.';
            passwordConfirmationSuccess.style.display = 'none';
        } else {
            passwordConfirmationError.style.display = 'none';
            passwordConfirmationError.textContent = '';
            passwordConfirmationSuccess.style.display = 'block';
            passwordConfirmationSuccess.textContent = 'Password cocok.';
        }
    });
});