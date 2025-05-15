document.addEventListener('DOMContentLoaded', function() {
    // Ensure the eye icon is visible as soon as the page loads
    var passwordField = document.getElementById('password');
    var eyeIcon = document.getElementById('eye-icon');

    // Toggle the password visibility when the eye icon is clicked
    if (eyeIcon) {
        eyeIcon.addEventListener('click', function() {
            var type = passwordField.type === "password" ? "text" : "password";
            passwordField.type = type;

            // Toggle the icon between "eye" and "eye-slash" based on visibility
            eyeIcon.innerHTML = (type === "password") ? "<i class='fas fa-eye'></i>" : "<i class='fas fa-eye-slash'></i>";
        });
    }
});
