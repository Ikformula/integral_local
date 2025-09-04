const CACHE_NAME = 'attendance-cache-v1';
const urlsToCache = [
    '/',
    '/css/attendance-bootstrap.min.css',
    '/js/app.js',
    // Add other assets needed for offline functionality
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => cache.addAll(urlsToCache))
    );
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request)
            .then((response) => {
                if (response) {
                    return response;
                }
                return fetch(event.request);
            })
    );
});

self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-attendance') {
        event.waitUntil(syncAttendanceData());
    }
});

async function syncAttendanceData() {
    const offlineData = JSON.parse(localStorage.getItem('offlineAttendance') || '[]');

    for (const data of offlineData) {
        try {
            const response = await fetch('/api/mark-attendance', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                // Remove synced data from local storage
                const remainingData = offlineData.filter(item => item !== data);
                localStorage.setItem('offlineAttendance', JSON.stringify(remainingData));
            }
        } catch (error) {
            console.error('Sync failed:', error);
        }
    }
}
