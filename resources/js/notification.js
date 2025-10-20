// Notification handling script

document.addEventListener('DOMContentLoaded', function() {
    // Initialize notifications
    initNotifications();
    
    // Set up polling for new notifications
    setInterval(fetchNotifications, 30000); // Check every 30 seconds
});

/**
 * Initialize notification functionality
 */
function initNotifications() {
    // Initial fetch of notifications
    fetchNotifications();
    
    // Set up click handler for "Mark all as read" button
    const markAllReadBtn = document.querySelector('.mark-all-read');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            markAllAsRead();
        });
    }
    
    // Set up notification click handler to mark individual notifications as read
    const notificationItems = document.querySelectorAll('.notification-item');
    notificationItems.forEach(item => {
        item.addEventListener('click', function() {
            const notificationId = this.dataset.notificationId;
            if (notificationId) {
                markAsRead(notificationId);
            }
        });
    });
}

/**
 * Fetch notifications from the server
 */
function fetchNotifications() {
    fetch('/api/notifications')
        .then(response => response.json())
        .then(data => {
            updateNotificationBadge(data.unreadCount);
            updateNotificationDropdown(data.notifications);
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
        });
}

/**
 * Update the notification badge with the count of unread notifications
 */
function updateNotificationBadge(count) {
    const badge = document.querySelector('.notification-badge');
    
    if (badge) {
        if (count > 0) {
            badge.textContent = count > 9 ? '9+' : count;
            badge.classList.remove('d-none');
        } else {
            badge.classList.add('d-none');
        }
    }
}

/**
 * Update the notification dropdown with the latest notifications
 */
function updateNotificationDropdown(notifications) {
    const dropdown = document.querySelector('.notification-dropdown');
    
    if (!dropdown) return;
    
    if (notifications.length === 0) {
        dropdown.innerHTML = `
            <li class="text-center py-3">
                <p class="text-sm mb-0">Aucune notification</p>
            </li>
        `;
        return;
    }
    
    let html = '';
    
    notifications.forEach(notification => {
        const isUnread = notification.read_at === null;
        const bgClass = isUnread ? 'bg-light' : '';
        const data = notification.data;
        const timeAgo = timeAgoFromDate(new Date(notification.created_at));
        
        html += `
            <li class="mb-2 notification-item" data-notification-id="${notification.id}">
                <a class="dropdown-item border-radius-md ${bgClass}" href="/manage/events/${data.event_id}">
                    <div class="d-flex py-1">
                        <div class="my-auto">
                            <div class="avatar avatar-sm border-radius-sm bg-danger d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-exclamation-triangle text-white"></i>
                            </div>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="text-sm font-weight-normal mb-1">
                                <span class="font-weight-bold">Événement à risque:</span> ${data.event_title}
                            </h6>
                            <p class="text-xs text-secondary mb-0 d-flex align-items-center">
                                <i class="fa fa-clock opacity-6 me-1"></i>
                                ${timeAgo}
                            </p>
                        </div>
                    </div>
                </a>
            </li>
        `;
    });
    
    html += `
        <li class="mt-2 text-center">
            <button class="btn btn-sm btn-primary w-100 mark-all-read">
                Marquer tout comme lu
            </button>
        </li>
    `;
    
    dropdown.innerHTML = html;
    
    // Reattach event listeners
    const markAllReadBtn = dropdown.querySelector('.mark-all-read');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            markAllAsRead();
        });
    }
    
    const notificationItems = dropdown.querySelectorAll('.notification-item');
    notificationItems.forEach(item => {
        item.addEventListener('click', function() {
            const notificationId = this.dataset.notificationId;
            if (notificationId) {
                markAsRead(notificationId);
            }
        });
    });
}

/**
 * Mark all notifications as read
 */
function markAllAsRead() {
    // Create a form and submit it
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/notifications/mark-all-read';
    
    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);
    
    document.body.appendChild(form);
    form.submit();
}

/**
 * Mark a specific notification as read
 */
function markAsRead(notificationId) {
    // Create a form and submit it
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/notifications/${notificationId}/mark-read`;
    
    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);
    
    document.body.appendChild(form);
    form.submit();
}

/**
 * Format a date as "time ago" (e.g., "2 minutes ago")
 */
function timeAgoFromDate(date) {
    const seconds = Math.floor((new Date() - date) / 1000);
    
    let interval = Math.floor(seconds / 31536000);
    if (interval > 1) return interval + " ans";
    if (interval === 1) return "1 an";
    
    interval = Math.floor(seconds / 2592000);
    if (interval > 1) return interval + " mois";
    if (interval === 1) return "1 mois";
    
    interval = Math.floor(seconds / 86400);
    if (interval > 1) return interval + " jours";
    if (interval === 1) return "1 jour";
    
    interval = Math.floor(seconds / 3600);
    if (interval > 1) return interval + " heures";
    if (interval === 1) return "1 heure";
    
    interval = Math.floor(seconds / 60);
    if (interval > 1) return interval + " minutes";
    if (interval === 1) return "1 minute";
    
    if (seconds < 10) return "à l'instant";
    
    return Math.floor(seconds) + " secondes";
}
