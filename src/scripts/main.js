// main.js - Shared JavaScript functions

// Mobile Navigation Toggle
function toggleMenu() {
    const navMenu = document.querySelector('.nav-menu');
    navMenu.classList.toggle('active');
}

// Close mobile menu when clicking on a link
document.addEventListener('DOMContentLoaded', function() {
    // Close mobile menu when clicking on navigation links
    document.querySelectorAll('.nav-menu a').forEach(link => {
        link.addEventListener('click', () => {
            const navMenu = document.querySelector('.nav-menu');
            navMenu.classList.remove('active');
        });
    });
    
    // Gallery Filter Functionality
    const galleryFilterButtons = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    if (galleryFilterButtons.length > 0) {
        galleryFilterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                galleryFilterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Get filter value
                const filterValue = this.getAttribute('data-filter');
                
                // Show/hide gallery items
                galleryItems.forEach(item => {
                    if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    }
    
    // Form Validation for Contact Form
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Simple validation
            const name = document.getElementById('name')?.value;
            const email = document.getElementById('email')?.value;
            const message = document.getElementById('message')?.value;
            
            if ((name && email && message) && (!name || !email || !message)) {
                alert('Please fill in all required fields.');
                return;
            }
            
            // Email validation
            if (email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    alert('Please enter a valid email address.');
                    return;
                }
            }
            
            // Show success message
            alert('Thank you for your message! This is a demo form. In a real application, this would submit to a server.');
            
            // Reset form
            this.reset();
        });
    }
    
    // Form Validation for Event Form
    const eventForm = document.getElementById('eventForm');
    if (eventForm) {
        eventForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const eventName = document.getElementById('event-name')?.value;
            const eventDate = document.getElementById('event-date')?.value;
            
            if (eventName && eventDate && (!eventName || !eventDate)) {
                alert('Please fill in all required fields.');
                return;
            }
            
            alert('Event submitted for approval! This is a demo form.');
            this.reset();
        });
    }
    
    // Form Validation for Enrolment Form
    const enrolmentForm = document.getElementById('enrolmentForm');
    if (enrolmentForm) {
        enrolmentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const studentName = document.getElementById('student-name')?.value;
            const parentName = document.getElementById('parent-name')?.value;
            const parentEmail = document.getElementById('parent-email')?.value;
            
            if ((studentName && parentName && parentEmail) && (!studentName || !parentName || !parentEmail)) {
                alert('Please fill in all required fields.');
                return;
            }
            
            if (parentEmail) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(parentEmail)) {
                    alert('Please enter a valid email address.');
                    return;
                }
            }
            
            alert('Application submitted successfully! You will receive a confirmation email shortly.');
            this.reset();
        });
    }
    
    // FAQ Toggle Functionality
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            const icon = this.querySelector('.faq-icon');
            
            // Close other open FAQs
            document.querySelectorAll('.faq-answer.active').forEach(activeAnswer => {
                if (activeAnswer !== answer) {
                    activeAnswer.classList.remove('active');
                    const activeIcon = activeAnswer.previousElementSibling.querySelector('.faq-icon');
                    if (activeIcon) activeIcon.textContent = '+';
                }
            });
            
            // Toggle current FAQ
            answer.classList.toggle('active');
            icon.textContent = answer.classList.contains('active') ? '−' : '+';
        });
    });
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Newsletter Form Submission
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = this.querySelector('input[type="email"]');
            if (emailInput.value) {
                alert('Thank you for subscribing to our newsletter!');
                emailInput.value = '';
            }
        });
    }
    
    // Add current year to footer
    const yearSpan = document.querySelector('.current-year');
    if (yearSpan) {
        yearSpan.textContent = new Date().getFullYear();
    }
    
    // Image Gallery Modal (basic version) - Use different variable name
    const galleryImageItems = document.querySelectorAll('.gallery-item');
    galleryImageItems.forEach(item => {
        item.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            const title = this.querySelector('.item-img')?.textContent || 'Gallery Image';
            alert(`Viewing: ${title}\nCategory: ${category}`);
        });
    });
    
    // Calendar Day Click Event
    const calendarDays = document.querySelectorAll('.calendar-day');
    calendarDays.forEach(day => {
        day.addEventListener('click', function() {
            const date = this.textContent;
            if (date) {
                alert(`Date: December ${date}, 2023\n${this.classList.contains('event') ? 'Event day!' : 'No events scheduled.'}`);
            }
        });
    });
    
    // Staff Page Search Functionality
    const staffSearchInput = document.querySelector('.search-input');
    const staffFilterButtons = document.querySelectorAll('.department-filter .filter-btn');
    
    if (staffSearchInput) {
        staffSearchInput.addEventListener('input', function() {
            // Search functionality would be implemented here
            console.log('Searching for:', this.value);
        });
    }
    
    if (staffFilterButtons.length > 0) {
        staffFilterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                staffFilterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Filter functionality would be implemented here
                console.log('Filtering by:', this.textContent);
            });
        });
    }
});

// Smooth scrolling for hero section
document.addEventListener('DOMContentLoaded', function() {
    // Scroll indicator functionality
    const scrollIndicator = document.querySelector('.scroll-indicator');
    if (scrollIndicator) {
        scrollIndicator.addEventListener('click', function() {
            window.scrollTo({
                top: window.innerHeight - 100,
                behavior: 'smooth'
            });
        });
    }
    
    // Parallax effect for hero
    window.addEventListener('scroll', function() {
        const hero = document.querySelector('.hero');
        if (hero) {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            hero.style.backgroundPosition = 'center ' + rate + 'px';
        }
    });
    
    // Animate stats counter
    const statNumbers = document.querySelectorAll('.stat-number');
    statNumbers.forEach(stat => {
        const finalValue = parseInt(stat.textContent);
        let currentValue = 0;
        const increment = finalValue / 50;
        const timer = setInterval(() => {
            currentValue += increment;
            if (currentValue >= finalValue) {
                stat.textContent = finalValue + (stat.textContent.includes('+') ? '+' : '');
                clearInterval(timer);
            } else {
                stat.textContent = Math.floor(currentValue) + (stat.textContent.includes('+') ? '+' : '');
            }
        }, 30);
    });
});

/***************calendar section************************ */
// JavaScript for dynamic calendar functionality
document.addEventListener('DOMContentLoaded', function() {
    // Event filtering
    function filterEvents(category) {
        const eventCards = document.querySelectorAll('.event-card');
        const filterButtons = document.querySelectorAll('.filter-btn');
        
        // Update active button
        filterButtons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.textContent.toLowerCase().includes(category)) {
                btn.classList.add('active');
            }
        });
        
        // Filter events
        eventCards.forEach(card => {
            if (category === 'all' || card.dataset.category === category) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    // Add to Calendar functionality
    document.querySelectorAll('.add-to-calendar').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const eventData = JSON.parse(this.dataset.event);
            
            // Create iCalendar format
            const icsContent = createICS(eventData);
            downloadICS(icsContent, eventData.title);
            
            // Show success message
            alert('Event added to your calendar!');
        });
    });
    
    // Form submission
    const eventForm = document.getElementById('eventForm');
    if (eventForm) {
        eventForm.addEventListener('submit', function(e) {
            // Client-side validation
            const eventName = document.getElementById('event-name').value;
            const eventDate = document.getElementById('event-date').value;
            
            if (!eventName || !eventDate) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return;
            }
            
            // Validate date is not in the past
            const selectedDate = new Date(eventDate);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                e.preventDefault();
                alert('Event date cannot be in the past.');
                return;
            }
        });
    }
    
    // Month/Year quick navigation
    const monthSelect = document.getElementById('monthSelect');
    const yearSelect = document.getElementById('yearSelect');
    
    if (monthSelect && yearSelect) {
        monthSelect.addEventListener('change', function() {
            updateCalendarURL();
        });
        
        yearSelect.addEventListener('change', function() {
            updateCalendarURL();
        });
    }
    
    function updateCalendarURL() {
        const month = monthSelect.value;
        const year = yearSelect.value;
        window.location.href = `?month=${month}&year=${year}`;
    }
    
    // Helper function to create ICS content
    function createICS(eventData) {
        const start = new Date(eventData.event_date + ' ' + eventData.start_time).toISOString().replace(/-|:|\.\d+/g, '');
        const end = new Date(eventData.event_date + ' ' + eventData.end_time || eventData.start_time).toISOString().replace(/-|:|\.\d+/g, '');
        
        return `BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Learning Academy//School Calendar//EN
BEGIN:VEVENT
UID:${Date.now()}@learningacademy.edu
DTSTAMP:${new Date().toISOString().replace(/-|:|\.\d+/g, '')}
DTSTART:${start}
DTEND:${end}
SUMMARY:${eventData.title}
DESCRIPTION:${eventData.description}
LOCATION:${eventData.location || 'Learning Academy'}
CATEGORIES:${eventData.category}
END:VEVENT
END:VCALENDAR`;
    }
    
    // Helper function to download ICS file
    function downloadICS(content, filename) {
        const blob = new Blob([content], { type: 'text/calendar' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `${filename.replace(/\s+/g, '_')}.ics`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }
    
    // Initialize current date in form
    const today = new Date().toISOString().split('T')[0];
    const eventDateInput = document.getElementById('event-date');
    if (eventDateInput) {
        eventDateInput.min = today;
        eventDateInput.value = today;
    }
});