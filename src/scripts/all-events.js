// all-events.js

document.addEventListener('DOMContentLoaded', function() {
    // View Controls
    const viewButtons = document.querySelectorAll('.view-btn');
    const eventsList = document.getElementById('eventsList');
    const eventsContainer = document.getElementById('eventsContainer');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active button
            viewButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Create grid view if it doesn't exist
            if (this.dataset.view === 'grid' && !document.querySelector('.events-grid-view')) {
                createGridView();
            }
            
            // Show selected view
            switch (this.dataset.view) {
                case 'list':
                    showListView();
                    break;
                case 'grid':
                    showGridView();
                    break;
                case 'calendar':
                    showCalendarView();
                    break;
            }
        });
    });
    
    function showListView() {
        hideAllViews();
        if (eventsList) {
            eventsList.style.display = 'block';
        }
    }
    
    function showGridView() {
        hideAllViews();
        const gridView = document.querySelector('.events-grid-view');
        if (gridView) {
            gridView.style.display = 'grid';
        }
    }
    
    function showCalendarView() {
        hideAllViews();
        const calendarView = document.querySelector('.events-calendar-view');
        if (calendarView) {
            calendarView.style.display = 'block';
        } else {
            createCalendarView();
        }
    }
    
    function hideAllViews() {
        if (eventsList) eventsList.style.display = 'none';
        const gridView = document.querySelector('.events-grid-view');
        if (gridView) gridView.style.display = 'none';
        const calendarView = document.querySelector('.events-calendar-view');
        if (calendarView) calendarView.style.display = 'none';
    }
    
    function createGridView() {
        const eventsList = document.getElementById('eventsList');
        if (!eventsList) return;
        
        const events = eventsList.querySelectorAll('.event-list-item');
        const gridContainer = document.createElement('div');
        gridContainer.className = 'events-grid-view';
        
        events.forEach(eventItem => {
            const gridItem = document.createElement('div');
            gridItem.className = 'event-grid-item';
            
            // Clone and adapt the event item for grid view
            const eventContent = eventItem.innerHTML;
            gridItem.innerHTML = `
                <div class="event-grid-header">
                    <div class="date-day">${eventItem.querySelector('.date-day')?.textContent || ''}</div>
                    <div class="date-month">${eventItem.querySelector('.date-month')?.textContent || ''}</div>
                </div>
                <div class="event-grid-body">
                    <h3>${eventItem.querySelector('h3')?.innerHTML || ''}</h3>
                    <div class="event-tags">${eventItem.querySelector('.event-tags')?.innerHTML || ''}</div>
                    <div class="event-description">${eventItem.querySelector('.event-description')?.textContent || ''}</div>
                </div>
            `;
            
            gridContainer.appendChild(gridItem);
        });
        
        eventsList.parentNode.insertBefore(gridContainer, eventsList.nextSibling);
    }
    
    function createCalendarView() {
        const calendarContainer = document.createElement('div');
        calendarContainer.className = 'events-calendar-view';
        calendarContainer.innerHTML = `
            <div class="calendar-view-header">
                <h3>Calendar View</h3>
                <p>Select a month to view events</p>
            </div>
            <div id="miniCalendar"></div>
            <div id="calendarEvents"></div>
        `;
        
        eventsContainer.appendChild(calendarContainer);
        initializeMiniCalendar();
    }
    
    function initializeMiniCalendar() {
        // This would be a simplified version of your main calendar
        // For now, show a message
        document.getElementById('miniCalendar').innerHTML = `
            <div class="calendar-placeholder">
                <i class="fas fa-calendar-alt"></i>
                <p>Calendar view would show events on a monthly calendar here.</p>
                <p>To implement this fully, you would need to:</p>
                <ul>
                    <li>Load events for the current month</li>
                    <li>Display them in a calendar grid</li>
                    <li>Add navigation between months</li>
                </ul>
                <button class="btn btn-primary" onclick="showListView()">
                    <i class="fas fa-list"></i> Switch to List View
                </button>
            </div>
        `;
    }
    
    // Sort events
    window.sortEvents = function(sortValue) {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', sortValue);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    };
    
    // Clear filters
    window.clearFilters = function() {
        window.location.href = 'all-events.php';
    };
    
    // Add to calendar functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-to-calendar-btn')) {
            const button = e.target.closest('.add-to-calendar-btn');
            const eventData = JSON.parse(button.dataset.event);
            addToCalendar(eventData);
        }
    });
    
    function addToCalendar(eventData) {
        // Generate ICS content
        const startDate = new Date(eventData.event_date + 'T' + (eventData.start_time || '00:00'));
        const endDate = eventData.end_time ? 
            new Date(eventData.event_date + 'T' + eventData.end_time) : 
            new Date(startDate.getTime() + 3600000);
        
        const formatDate = (date) => {
            return date.toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
        };
        
        const icsContent = `BEGIN:VCALENDAR
VERSION:2.0
CALSCALE:GREGORIAN
BEGIN:VEVENT
DTSTART:${formatDate(startDate)}
DTEND:${formatDate(endDate)}
SUMMARY:${eventData.title}
DESCRIPTION:${eventData.description}
LOCATION:${eventData.location || 'Not specified'}
STATUS:CONFIRMED
END:VEVENT
END:VCALENDAR`;
        
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
        
        showToast('Event added to your calendar!', 'success');
    }
    
    // Share functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.share-btn')) {
            const button = e.target.closest('.share-btn');
            const eventId = button.dataset.eventId;
            openShareModal(eventId);
        }
    });
    
    function openShareModal(eventId) {
        const modal = document.getElementById('shareModal');
        const shareUrl = document.getElementById('shareUrl');
        
        // Set the share URL
        const url = `${window.location.origin}/event-details.php?id=${eventId}`;
        shareUrl.value = url;
        
        // Update share links
        const shareOptions = modal.querySelectorAll('.share-option');
        shareOptions.forEach(option => {
            const platform = option.dataset.platform;
            let shareLink = '';
            
            switch (platform) {
                case 'facebook':
                    shareLink = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                    break;
                case 'twitter':
                    shareLink = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=Check out this event!`;
                    break;
                case 'whatsapp':
                    shareLink = `https://api.whatsapp.com/send?text=${encodeURIComponent(`Check out this event: ${url}`)}`;
                    break;
                case 'email':
                    shareLink = `mailto:?subject=School Event&body=Check out this event: ${url}`;
                    break;
                case 'link':
                    // No link needed for copy functionality
                    return;
            }
            
            option.href = shareLink;
            if (platform !== 'link') {
                option.target = '_blank';
            }
        });
        
        modal.style.display = 'block';
    }
    
    // Export functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.export-btn')) {
            const button = e.target.closest('.export-btn');
            const format = button.dataset.format;
            exportEvents(format);
        }
    });
    
    function exportEvents(format) {
        const currentUrl = new URL(window.location.href);
        
        switch (format) {
            case 'pdf':
                currentUrl.pathname = 'export-events.php';
                currentUrl.searchParams.set('format', 'pdf');
                window.open(currentUrl.toString(), '_blank');
                break;
            case 'excel':
                currentUrl.pathname = 'export-events.php';
                currentUrl.searchParams.set('format', 'excel');
                window.open(currentUrl.toString(), '_blank');
                break;
            case 'ics':
                currentUrl.pathname = 'export-events.php';
                currentUrl.searchParams.set('format', 'ics');
                window.open(currentUrl.toString(), '_blank');
                break;
        }
        
        // Close modal
        document.getElementById('exportModal').style.display = 'none';
    }
    
    // Copy share URL
    window.copyShareUrl = function() {
        const shareUrl = document.getElementById('shareUrl');
        shareUrl.select();
        shareUrl.setSelectionRange(0, 99999); // For mobile devices
        document.execCommand('copy');
        
        // Show success message
        const originalText = shareUrl.nextElementSibling.innerHTML;
        shareUrl.nextElementSibling.innerHTML = '<i class="fas fa-check"></i> Copied!';
        
        setTimeout(() => {
            shareUrl.nextElementSibling.innerHTML = originalText;
        }, 2000);
    };
    
    // Modal functionality
    const modals = document.querySelectorAll('.modal');
    const closeButtons = document.querySelectorAll('.close-modal');
    
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.modal').style.display = 'none';
        });
    });
    
    window.addEventListener('click', function(e) {
        modals.forEach(modal => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
    
    // Toast notifications
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
        
        .calendar-placeholder {
            text-align: center;
            padding: 3rem;
            background: #f8f9fa;
            border-radius: 10px;
            border: 2px dashed #ddd;
        }
        
        .calendar-placeholder i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 1rem;
        }
        
        .calendar-placeholder p {
            color: #666;
            margin-bottom: 1rem;
        }
        
        .calendar-placeholder ul {
            text-align: left;
            max-width: 400px;
            margin: 1rem auto;
            color: #666;
        }
    `;
    document.head.appendChild(style);
});