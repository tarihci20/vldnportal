// Service Worker Configuration
const CACHE_VERSION = 'v1.0.0';
const CACHE_NAME = `vildan-portal-${CACHE_VERSION}`;
const OFFLINE_URL = '/offline.html';

// Cache Stratejisi: Farklı varlıklar için farklı cache süreleri
const CACHE_ASSETS = {
  // Temel dosyalar - hemen cache
  essential: [
    '/',
    '/offline.html',
    '/manifest/manifest.json',
    '/assets/css/main.css',
    '/assets/css/themes.css',
    '/assets/js/app.js',
    '/assets/js/pwa-handler.js',
    '/assets/img/logo.png',
    '/assets/img/logo-dark.png'
  ],
  
  // Statik varlıklar - uzun süre cache
  static: [
    '/assets/css/responsive.css',
    '/assets/js/theme-switcher.js',
    '/assets/js/ajax-handler.js',
    '/assets/js/debounce.js',
    '/assets/js/calendar.js'
  ],
  
  // İkonlar
  icons: [
    '/manifest/icons/icon-72x72.png',
    '/manifest/icons/icon-96x96.png',
    '/manifest/icons/icon-128x128.png',
    '/manifest/icons/icon-144x144.png',
    '/manifest/icons/icon-152x152.png',
    '/manifest/icons/icon-192x192.png',
    '/manifest/icons/icon-384x384.png',
    '/manifest/icons/icon-512x512.png'
  ]
};

// CDN kaynakları
const CDN_URLS = [
  'https://cdn.tailwindcss.com',
  'https://cdn.jsdelivr.net/npm/alpinejs',
  'https://cdn.jsdelivr.net/npm/chart.js',
  'https://cdn.jsdelivr.net/npm/fullcalendar'
];

// API endpoint'leri - network first
const API_ROUTES = [
  '/api/',
  '/ajax/'
];

// Offline sync için bekleyen istekler
let pendingRequests = [];

// ==================== INSTALL EVENT ====================
self.addEventListener('install', (event) => {
  console.log('[Service Worker] Installing...');
  
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('[Service Worker] Caching essential files');
        
        // Tüm varlıkları cache'le
        const allAssets = [
          ...CACHE_ASSETS.essential,
          ...CACHE_ASSETS.static,
          ...CACHE_ASSETS.icons
        ];
        
        return cache.addAll(allAssets);
      })
      .then(() => {
        console.log('[Service Worker] Installation complete');
        return self.skipWaiting(); // Hemen aktif et
      })
      .catch((error) => {
        console.error('[Service Worker] Installation failed:', error);
      })
  );
});

// ==================== ACTIVATE EVENT ====================
self.addEventListener('activate', (event) => {
  console.log('[Service Worker] Activating...');
  
  event.waitUntil(
    caches.keys()
      .then((cacheNames) => {
        // Eski cache'leri temizle
        return Promise.all(
          cacheNames.map((cacheName) => {
            if (cacheName !== CACHE_NAME) {
              console.log('[Service Worker] Deleting old cache:', cacheName);
              return caches.delete(cacheName);
            }
          })
        );
      })
      .then(() => {
        console.log('[Service Worker] Activation complete');
        return self.clients.claim(); // Hemen kontrol al
      })
  );
});

// ==================== FETCH EVENT ====================
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);
  
  // POST istekleri için özel işlem
  if (request.method === 'POST') {
    event.respondWith(handlePostRequest(request));
    return;
  }
  
  // API istekleri - Network First
  if (isApiRequest(url.pathname)) {
    event.respondWith(networkFirst(request));
    return;
  }
  
  // CDN kaynakları - Stale While Revalidate
  if (isCDNRequest(url.href)) {
    event.respondWith(staleWhileRevalidate(request));
    return;
  }
  
  // Statik varlıklar - Cache First
  if (isStaticAsset(url.pathname)) {
    event.respondWith(cacheFirst(request));
    return;
  }
  
  // HTML sayfaları - Network First with Cache Fallback
  if (request.headers.get('accept').includes('text/html')) {
    event.respondWith(networkFirstWithFallback(request));
    return;
  }
  
  // Diğerleri - varsayılan strateji
  event.respondWith(cacheFirst(request));
});

// ==================== CACHE STRATEGIES ====================

// Cache First - Önce cache'e bak, yoksa network'ten al
async function cacheFirst(request) {
  const cachedResponse = await caches.match(request);
  
  if (cachedResponse) {
    return cachedResponse;
  }
  
  try {
    const networkResponse = await fetch(request);
    
    if (networkResponse.ok) {
      const cache = await caches.open(CACHE_NAME);
      cache.put(request, networkResponse.clone());
    }
    
    return networkResponse;
  } catch (error) {
    console.error('[Service Worker] Fetch failed:', error);
    return caches.match(OFFLINE_URL);
  }
}

// Network First - Önce network'e git, yoksa cache'ten al
async function networkFirst(request) {
  try {
    const networkResponse = await fetch(request);
    
    if (networkResponse.ok) {
      const cache = await caches.open(CACHE_NAME);
      cache.put(request, networkResponse.clone());
    }
    
    return networkResponse;
  } catch (error) {
    console.log('[Service Worker] Network failed, trying cache');
    
    const cachedResponse = await caches.match(request);
    
    if (cachedResponse) {
      return cachedResponse;
    }
    
    // API istekleri için offline response
    if (isApiRequest(new URL(request.url).pathname)) {
      return new Response(
        JSON.stringify({ 
          error: 'Offline', 
          message: 'İnternet bağlantınız yok. Lütfen daha sonra tekrar deneyin.' 
        }),
        { 
          status: 503,
          headers: { 'Content-Type': 'application/json' }
        }
      );
    }
    
    return caches.match(OFFLINE_URL);
  }
}

// Network First with Fallback - HTML sayfaları için
async function networkFirstWithFallback(request) {
  try {
    const networkResponse = await fetch(request);
    return networkResponse;
  } catch (error) {
    const cachedResponse = await caches.match(request);
    return cachedResponse || caches.match(OFFLINE_URL);
  }
}

// Stale While Revalidate - Cache'ten hemen dön, arka planda güncelle
async function staleWhileRevalidate(request) {
  const cachedResponse = await caches.match(request);
  
  const fetchPromise = fetch(request).then((networkResponse) => {
    if (networkResponse.ok) {
      const cache = caches.open(CACHE_NAME);
      cache.then((c) => c.put(request, networkResponse.clone()));
    }
    return networkResponse;
  });
  
  return cachedResponse || fetchPromise;
}

// ==================== POST REQUEST HANDLING ====================
async function handlePostRequest(request) {
  try {
    const response = await fetch(request.clone());
    return response;
  } catch (error) {
    console.log('[Service Worker] POST request failed, queuing for sync');
    
    // Offline ise sync için kaydet
    const requestData = {
      url: request.url,
      method: request.method,
      headers: Object.fromEntries(request.headers),
      body: await request.clone().text(),
      timestamp: Date.now()
    };
    
    await saveForSync(requestData);
    
    // Background sync tetikle
    if ('sync' in self.registration) {
      await self.registration.sync.register('sync-offline-requests');
    }
    
    return new Response(
      JSON.stringify({ 
        queued: true,
        message: 'İstek kaydedildi ve internet bağlantısı geldiğinde gönderilecek.'
      }),
      { 
        status: 202,
        headers: { 'Content-Type': 'application/json' }
      }
    );
  }
}

// ==================== BACKGROUND SYNC ====================
self.addEventListener('sync', (event) => {
  console.log('[Service Worker] Background sync triggered');
  
  if (event.tag === 'sync-offline-requests') {
    event.waitUntil(syncOfflineRequests());
  }
});

async function syncOfflineRequests() {
  try {
    const requests = await getQueuedRequests();
    
    console.log(`[Service Worker] Syncing ${requests.length} offline requests`);
    
    for (const requestData of requests) {
      try {
        const response = await fetch(requestData.url, {
          method: requestData.method,
          headers: requestData.headers,
          body: requestData.body
        });
        
        if (response.ok) {
          await removeFromQueue(requestData);
          
          // Client'lara bildir
          notifyClients({
            type: 'sync-success',
            url: requestData.url,
            timestamp: requestData.timestamp
          });
        }
      } catch (error) {
        console.error('[Service Worker] Sync failed for:', requestData.url);
      }
    }
  } catch (error) {
    console.error('[Service Worker] Sync process failed:', error);
  }
}

// ==================== PUSH NOTIFICATIONS ====================
self.addEventListener('push', (event) => {
  console.log('[Service Worker] Push notification received');
  
  const data = event.data ? event.data.json() : {};
  
  const options = {
    body: data.body || 'Yeni bildirim',
    icon: '/manifest/icons/icon-192x192.png',
    badge: '/manifest/icons/badge-72x72.png',
    vibrate: [200, 100, 200],
    data: data,
    actions: data.actions || [
      { action: 'open', title: 'Aç' },
      { action: 'close', title: 'Kapat' }
    ],
    requireInteraction: data.requireInteraction || false,
    tag: data.tag || 'default',
    renotify: true
  };
  
  event.waitUntil(
    self.registration.showNotification(data.title || 'Vildan Portal', options)
  );
});

// Bildirim tıklama
self.addEventListener('notificationclick', (event) => {
  console.log('[Service Worker] Notification clicked');
  
  event.notification.close();
  
  const action = event.action;
  const data = event.notification.data;
  
  if (action === 'close') {
    return;
  }
  
  // URL'yi aç veya odaklan
  const urlToOpen = data.url || '/';
  
  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true })
      .then((clientList) => {
        // Zaten açık bir pencere varsa onu odakla
        for (const client of clientList) {
          if (client.url === urlToOpen && 'focus' in client) {
            return client.focus();
          }
        }
        
        // Yeni pencere aç
        if (clients.openWindow) {
          return clients.openWindow(urlToOpen);
        }
      })
  );
});

// ==================== MESSAGE HANDLING ====================
self.addEventListener('message', (event) => {
  console.log('[Service Worker] Message received:', event.data);
  
  const { type, data } = event.data;
  
  switch (type) {
    case 'skip-waiting':
      self.skipWaiting();
      break;
      
    case 'clear-cache':
      event.waitUntil(clearAllCaches());
      break;
      
    case 'check-updates':
      event.waitUntil(checkForUpdates());
      break;
      
    case 'queue-request':
      event.waitUntil(saveForSync(data));
      break;
      
    default:
      console.log('[Service Worker] Unknown message type:', type);
  }
});

// ==================== HELPER FUNCTIONS ====================

function isApiRequest(pathname) {
  return API_ROUTES.some(route => pathname.startsWith(route));
}

function isCDNRequest(url) {
  return CDN_URLS.some(cdn => url.startsWith(cdn));
}

function isStaticAsset(pathname) {
  const staticExtensions = ['.css', '.js', '.png', '.jpg', '.jpeg', '.gif', '.svg', '.woff', '.woff2'];
  return staticExtensions.some(ext => pathname.endsWith(ext));
}

async function saveForSync(requestData) {
  const db = await openDatabase();
  const tx = db.transaction('sync-queue', 'readwrite');
  const store = tx.objectStore('sync-queue');
  await store.add(requestData);
}

async function getQueuedRequests() {
  const db = await openDatabase();
  const tx = db.transaction('sync-queue', 'readonly');
  const store = tx.objectStore('sync-queue');
  return await store.getAll();
}

async function removeFromQueue(requestData) {
  const db = await openDatabase();
  const tx = db.transaction('sync-queue', 'readwrite');
  const store = tx.objectStore('sync-queue');
  const cursor = await store.openCursor();
  
  while (cursor) {
    if (cursor.value.timestamp === requestData.timestamp) {
      await cursor.delete();
      break;
    }
    await cursor.continue();
  }
}

function openDatabase() {
  return new Promise((resolve, reject) => {
    const request = indexedDB.open('vildan-portal-sync', 1);
    
    request.onerror = () => reject(request.error);
    request.onsuccess = () => resolve(request.result);
    
    request.onupgradeneeded = (event) => {
      const db = event.target.result;
      if (!db.objectStoreNames.contains('sync-queue')) {
        db.createObjectStore('sync-queue', { autoIncrement: true });
      }
    };
  });
}

async function clearAllCaches() {
  const cacheNames = await caches.keys();
  await Promise.all(cacheNames.map(name => caches.delete(name)));
  console.log('[Service Worker] All caches cleared');
}

async function checkForUpdates() {
  try {
    const registration = await self.registration.update();
    console.log('[Service Worker] Update check complete');
    return registration;
  } catch (error) {
    console.error('[Service Worker] Update check failed:', error);
  }
}

function notifyClients(message) {
  self.clients.matchAll().then(clients => {
    clients.forEach(client => client.postMessage(message));
  });
}

console.log('[Service Worker] Loaded successfully');