$(document).ready(function() {
    // Toggle Available Courses Section
    $('#toggleAvailableCourses').click(function() {
        const section = $('#availableCoursesSection');
        const button = $(this);
        
        section.slideToggle(400, function() {
            const isVisible = section.is(':visible');
            button.html(isVisible ? 
                '<i class="fas fa-minus"></i> Hide Available Courses' : 
                '<i class="fas fa-plus"></i> Add New Course'
            );
        });
    });

    // Enroll in a course
    $('.enroll-btn').click(function() {
        const button = $(this);
        const courseId = button.data('course-id');
        
        // Add loading animation
        button.html('<div class="loading"></div>');
        button.prop('disabled', true);

        $.ajax({
            url: '../api/student/enroll.php',
            method: 'POST',
            data: { course_id: courseId },
            success: function(response) {
                if(response.success) {
                    // Show success message
                    showNotification('Successfully enrolled in course!', 'success');
                    // Reload page after a short delay
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showNotification(response.message || 'Enrollment failed!', 'error');
                    // Reset button
                    button.html('<i class="fas fa-plus-circle"></i> Enroll');
                    button.prop('disabled', false);
                }
            },
            error: function() {
                showNotification('An error occurred!', 'error');
                // Reset button
                button.html('<i class="fas fa-plus-circle"></i> Enroll');
                button.prop('disabled', false);
            }
        });
    });

    // Withdraw from a course
    $('.withdraw-btn').click(function() {
        if(confirm('Are you sure you want to withdraw from this course?')) {
            const button = $(this);
            const enrollmentId = button.data('enrollment-id');
            
            // Add loading animation
            button.html('<div class="loading"></div>');
            button.prop('disabled', true);

            $.ajax({
                url: '../api/student/withdraw.php',
                method: 'POST',
                data: { enrollment_id: enrollmentId },
                success: function(response) {
                    if(response.success) {
                        showNotification('Successfully withdrawn from course!', 'success');
                        // Animate card removal
                        button.closest('.course-card').fadeOut(400, function() {
                            $(this).remove();
                            // Reload page if no courses left
                            if($('.enrolled-courses .course-card').length === 0) {
                                window.location.reload();
                            }
                        });
                    } else {
                        showNotification(response.message || 'Withdrawal failed!', 'error');
                        // Reset button
                        button.html('<i class="fas fa-minus-circle"></i> Withdraw');
                        button.prop('disabled', false);
                    }
                },
                error: function() {
                    showNotification('An error occurred!', 'error');
                    // Reset button
                    button.html('<i class="fas fa-minus-circle"></i> Withdraw');
                    button.prop('disabled', false);
                }
            });
        }
    });

    // View course details
    $('.view-details-btn').click(function() {
        const courseId = $(this).data('course-id');
        const modal = $('#courseModal');
        const modalContent = $('#courseDetails');
        
        // Show loading state
        modalContent.html('<div class="loading"></div>');
        modal.fadeIn(300);

        // Fetch course details
        $.ajax({
            url: '../api/student/course-details.php',
            method: 'GET',
            data: { course_id: courseId },
            success: function(response) {
                if(response.success) {
                    modalContent.html(`
                        <h2>${response.course.title}</h2>
                        <p class="course-code">Code: ${response.course.course_code}</p>
                        <div class="course-info">
                            <p>${response.course.description}</p>
                            <p><strong>Credits:</strong> ${response.course.credits}</p>
                            <p><strong>Professor:</strong> ${response.course.professor_name}</p>
                        </div>
                    `);
                } else {
                    modalContent.html('<p class="error">Failed to load course details.</p>');
                }
            },
            error: function() {
                modalContent.html('<p class="error">An error occurred while loading course details.</p>');
            }
        });
    });

    // Close modal
    $('.close, .modal').click(function(e) {
        if(e.target === this) {
            $('#courseModal').fadeOut(300);
        }
    });

    // Notification system
    function showNotification(message, type) {
        // Remove existing notifications
        $('.notification').remove();
        
        // Create new notification
        const notification = $(`
            <div class="notification ${type}">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                ${message}
            </div>
        `);
        
        // Add to document
        $('body').append(notification);
        
        // Animate in
        setTimeout(() => notification.addClass('show'), 100);
        
        // Remove after delay
        setTimeout(() => {
            notification.removeClass('show');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
});
