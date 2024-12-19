document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation for delete actions
    const deleteButtons = document.querySelectorAll('.btn-danger');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const confirmDelete = confirm('Are you sure you want to remove this item?');
            if (!confirmDelete) {
                e.preventDefault();
            }
        });
    });

    // Handle form submissions with AJAX if needed in the future
    const adminForms = document.querySelectorAll('form');
    adminForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Potential future AJAX form submission logic
        });
    });
});
