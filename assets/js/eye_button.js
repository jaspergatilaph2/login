const passwordInput = document.getElementById('password');
const toggleButton = document.getElementById('togglePassword');
toggleButton.addEventListener('click', function (){
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.type = type;
    eyeIcon = this.classList.toggle('bi bi-eye');
    eyeIcon = this.classList.toggle('bi-eye-slash');
});