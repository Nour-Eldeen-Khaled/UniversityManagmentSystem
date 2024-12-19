document.addEventListener('DOMContentLoaded', function() {
    const userInfoIcon = document.querySelector('.user-info-icon');
    const userInfoDropdown = document.querySelector('.user-info-dropdown');

    if (userInfoIcon && userInfoDropdown) {
        userInfoIcon.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent document click from immediately closing
            userInfoDropdown.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            userInfoDropdown.classList.remove('show');
        });

        // Prevent dropdown from closing when clicking inside
        userInfoDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
});
