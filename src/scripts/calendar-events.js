// calendar-events.js

document.addEventListener('DOMContentLoaded', function () {
    // Event filtering
    function filterEvents(category) {
        const eventCards = document.querySelectorAll('.event-card');
        const filterButtons = document.querySelectorAll('.filter-btn');

        // Update active button
        filterButtons.forEach(btn => {
            if (btn.textContent.toLowerCase().includes(category.toLowerCase())) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        // Filter events
        eventCards.forEach(card => {
            if (category === 'all' || card.dataset.category === category) {
                card.style.display = 'block';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 10);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });
    }

    // Form step navigation
    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', function () {
            const currentStep = document.querySelector('.form-step.active');
            const nextStep = document.getElementById('step' + this.dataset.next);
            const currentStepNumber = currentStep.id.replace('step', '');

            // Validate current step
            if (validateStep(currentStepNumber)) {
                currentStep.classList.remove('active');
                nextStep.classList.add('active');

                // Update step indicator
                document.querySelectorAll('.step').forEach(step => {
                    step.classList.remove('active');
                });
                document.querySelector(`.step[data-step="${this.dataset.next}"]`).classList.add('active');

                // Smooth scroll to next step
                nextStep.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    document.querySelectorAll('.prev-step').forEach(button => {
        button.addEventListener('click', function () {
            const currentStep = document.querySelector('.form-step.active');
            const prevStep = document.getElementById('step' + this.dataset.prev);

            currentStep.classList.remove('active');
            prevStep.classList.add('active');

            // Update step indicator
            document.querySelectorAll('.step').forEach(step => {
                step.classList.remove('active');
            });
            document.querySelector(`.step[data-step="${this.dataset.prev}"]`).classList.add('active');

            // Smooth scroll to previous step
            prevStep.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    // Form validation
    function validateStep(stepNumber) {
        const step = document.getElementById('step' + stepNumber);
        const inputs = step.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.style.borderColor = '#dc3545';
                isValid = false;

                // Add error message
                let errorDiv = input.parentElement.querySelector('.error-message');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message';
                    errorDiv.style.color = '#dc3545';
                    errorDiv.style.fontSize = '0.875rem';
                    errorDiv.style.marginTop = '0.25rem';
                    input.parentElement.appendChild(errorDiv);
                }
                errorDiv.textContent = 'This field is required';
            } else {
                input.style.borderColor = '#ddd';
                const errorDiv = input.parentElement.querySelector('.error-message');
                if (errorDiv) {
                    errorDiv.remove();
                }
            }

            // Email validation
            if (input.type === 'email' && input.value.trim()) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(input.value)) {
                    input.style.borderColor = '#dc3545';
                    isValid = false;

                    let errorDiv = input.parentElement.querySelector('.error-message');
                    if (!errorDiv) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'error-message';
                        errorDiv.style.color = '#dc3545';
                        errorDiv.style.fontSize = '0.875rem';
                        errorDiv.style.marginTop = '0.25rem';
                        input.parentElement.appendChild(errorDiv);
                    }
                    errorDiv.textContent = 'Please enter a valid email address';
                }
            }
        });

        return isValid;
    }

    // Character counter for description
    const descriptionTextarea = document.getElementById('event-description');
    if (descriptionTextarea) {
        descriptionTextarea.addEventListener('input', function () {
            const charCount = this.value.length;
            const counter = this.parentElement.querySelector('.char-count');
            counter.textContent = `${charCount}/500 characters`;

            if (charCount > 500) {
                counter.style.color = '#dc3545';
            } else {
                counter.style.color = '#666';
            }
        });
    }

    // Recurring event toggle
    const recurringCheckbox = document.getElementById('recurring-event');
    const recurringOptions = document.getElementById('recurring-options');

    if (recurringCheckbox && recurringOptions) {
        recurringCheckbox.addEventListener('change', function () {
            if (this.checked) {
                recurringOptions.style.display = 'block';
            } else {
                recurringOptions.style.display = 'none';
            }
        });
    }

    // Add to calendar functionality
    document.querySelectorAll('.add-to-calendar-btn').forEach(button => {
        button.addEventListener('click', function () {
            const eventData = JSON.parse(this.dataset.event);

            // Create ICS file
            const icsContent = generateICSEvent(eventData);

            // Download ICS file
            const blob = new Blob([icsContent], { type: 'text/calendar' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${eventData.title.replace(/\s+/g, '_')}.ics`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);

            // Show success message
            showToast('Event added to your calendar!', 'success');
        });
    });

    // Generate ICS event
    function generateICSEvent(event) {
        const startDate = new Date(event.event_date + 'T' + (event.start_time || '00:00'));
        const endDate = event.end_time ? new Date(event.event_date + 'T' + event.end_time) : new Date(startDate.getTime() + 3600000);

        // Format dates for ICS
        const formatDate = (date) => {
            return date.toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
        };

        return `BEGIN:VCALENDAR
VERSION:2.0
CALSCALE:GREGORIAN
BEGIN:VEVENT
DTSTART:${formatDate(startDate)}
DTEND:${formatDate(endDate)}
SUMMARY:${event.title}
DESCRIPTION:${event.description}
LOCATION:${event.location || 'Not specified'}
STATUS:CONFIRMED
END:VEVENT
END:VCALENDAR`;
    }

    // Show toast notification
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;

        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: ${type === 'success' ? '#28a745' : '#dc3545'};
            color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1000;
            animation: slideIn 0.3s ease;
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    // Set minimum date for event date
    const eventDateInput = document.getElementById('event-date');
    if (eventDateInput) {
        const today = new Date().toISOString().split('T')[0];
        eventDateInput.min = today;
    }

    // Mobile app modal
    window.showMobileOptions = function () {
        const modal = document.getElementById('mobileAppModal');
        modal.style.display = 'block';
    };

    // Close modal
    document.querySelector('.close-modal').addEventListener('click', function () {
        document.getElementById('mobileAppModal').style.display = 'none';
    });

    // Close modal when clicking outside
    window.addEventListener('click', function (event) {
        const modal = document.getElementById('mobileAppModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Form submission
    const eventForm = document.getElementById('eventSubmissionForm');
    if (eventForm) {
        eventForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            // Validate all steps
            let allValid = true;
            for (let i = 1; i <= 3; i++) {
                if (!validateStep(i)) {
                    allValid = false;
                }
            }

            if (!allValid) {
                showToast('Please fill all required fields correctly', 'error');
                return;
            }

            // Submit form via AJAX
            const formData = new FormData(this);

            try {
                const response = await fetch('submit-event.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showToast('Event submitted successfully! We\'ll review it soon.', 'success');
                    eventForm.reset();

                    // Reset to step 1
                    document.querySelectorAll('.form-step').forEach(step => {
                        step.classList.remove('active');
                    });
                    document.getElementById('step1').classList.add('active');

                    // Reset step indicators
                    document.querySelectorAll('.step').forEach(step => {
                        step.classList.remove('active');
                    });
                    document.querySelector('.step[data-step="1"]').classList.add('active');
                } else {
                    showToast(result.message || 'Error submitting event', 'error');
                }
            } catch (error) {
                showToast('Network error. Please try again.', 'error');
            }
        });
    }

    // Auto-scroll to today in calendar
    const todayCell = document.querySelector('.calendar-day.active');
    if (todayCell) {
        todayCell.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center' });
    }

    // Initialize tooltips
    const tooltipTriggers = document.querySelectorAll('[data-tooltip]');
    tooltipTriggers.forEach(trigger => {
        trigger.addEventListener('mouseenter', function () {
            // READ first — get the trigger position before any DOM mutation
            const rect = this.getBoundingClientRect();

            const tooltip = document.createElement('div');
            tooltip.className = 'custom-tooltip';
            tooltip.textContent = this.dataset.tooltip;
            // Use visibility:hidden to measure without showing, then position
            tooltip.style.cssText = `
                position: fixed;
                background: #333;
                color: white;
                padding: 0.5rem 0.75rem;
                border-radius: 4px;
                font-size: 0.875rem;
                white-space: nowrap;
                z-index: 1000;
                pointer-events: none;
                visibility: hidden;
            `;
            document.body.appendChild(tooltip);

            // Now batch the reads of tooltip dimensions in one rAF (after paint)
            // so we don't force a synchronous relayout
            requestAnimationFrame(() => {
                const tw = tooltip.offsetWidth;
                const th = tooltip.offsetHeight;
                tooltip.style.top = (rect.top + window.scrollY - th - 5) + 'px';
                tooltip.style.left = (rect.left + window.scrollX + rect.width / 2 - tw / 2) + 'px';
                tooltip.style.visibility = 'visible';
            });

            this.dataset.tooltipId = tooltip;
        });

        trigger.addEventListener('mouseleave', function () {
            if (this.dataset.tooltipId) {
                this.dataset.tooltipId.remove();
                delete this.dataset.tooltipId;
            }
        });
    });

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .custom-tooltip {
            animation: fadeIn 0.2s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(style);
});