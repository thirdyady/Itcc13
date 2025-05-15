document.addEventListener("DOMContentLoaded", function () {
    const forms = document.querySelectorAll("form.mark-claimed-form");

    forms.forEach(function (form) {
        form.addEventListener("submit", function (e) {
            const fullName = prompt("Enter the full name of the person claiming this item:");
            if (!fullName || fullName.trim() === "") {
                e.preventDefault(); // Stop submission
                alert("Name is required!");
                return;
            }

            // Find the claimed_by hidden input and set its value
            const claimedByInput = form.querySelector("input[name='claimed_by']");
            if (claimedByInput) {
                claimedByInput.value = fullName.trim();
            }
        });
    });
});
