/**
 * PWA Handler - Progressive Web App İstemci Yönetimi
 * 
 * Özellikler:
 * - Service Worker kaydı ve güncelleme
 * - Install prompt yönetimi
 * - Push notification
 * - Online/Offline durum takibi
 * - Background sync
 */

class PWAHandler {
  constructor() {
    this.swRegistration = null;
    this.deferredPrompt = null;
    this.isOnline = navigator.onLine;
    this.updateAvailable = false;
    
    this.init();
  }
  
  // ==================== INITIALIZATION ====================
  
  async init() {
    console.log('[PWA] Initializing...');
    
    // Service Worker desteği kontrolü
    if (!('serviceWorker' in navigator)) {
      console.warn('[PWA] Service Worker not supported');
      return;
    }
    
    // Event listener'ları kur
    this.setupEventListeners();
    
    // Service Worker'ı kaydet
    await this.registerServiceWorker();
    
    // Online/Offline durumu göster
    this.updateOnlineStatus();
    
    // Install prompt hazırla
    this.setupInstallPrompt();
    
    // Push notification desteğini kontrol et
    this.checkPushSupport();
    
    // Periyodik güncellemeleri kontrol et
    this.scheduleUpdateChecks();
    
    console.log('[PWA] Initialization complete');
  }
  
  // ==================== SERVICE WORKER ====================
  
  async registerServiceWorker() {
    try {
      this.swRegistration = await navigator.serviceWorker.register(
        '/manifest/service-worker.js',
        { scope: '/' }
      );
      
      console.log('[PWA] Service Worker registered:', this.swRegistration.scope);
      
      // Güncelleme kontrolü
      this.swRegistration.addEventListener('updatefound', () => {
        this.handleUpdateFound();
      });
      
      // Controller değişimi
      navigator.serviceWorker.addEventListener('controllerchange', () => {
        console.log('[PWA] Controller changed, reloading...');
        window.location.reload();
      });
      
      // Service Worker mesajlarını dinle
      navigator.serviceWorker.addEventListener('message', (event) => {
        this.handleServiceWorkerMessage(event);
      });
      
      return this.swRegistration;
    } catch (error) {
      console.error('[PWA] Service Worker registration failed:', error);
      throw error;
    }
  }
  
  handleUpdateFound() {
    console.log('[PWA] Update found');
    
    const newWorker = this.swRegistration.installing;
    
    newWorker.addEventListener('statechange', () => {
      if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
        console.log('[PWA] Update available');
        this.updateAvailable = true;
        this.showUpdateNotification();
      }
    });
  }
  
  showUpdateNotification() {
    // SweetAlert2 ile güncelleme bildirimi
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Güncelleme Mevcut!',
        text: 'Vildan Portal\'ın yeni bir sürümü mevcut. Güncellemek ister misiniz?',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Güncelle',
        cancelButtonText: 'Daha Sonra',
        confirmButtonColor: '#3B82F6'
      }).then((result) => {
        if (result.isConfirmed) {
          this.applyUpdate();
        }
      });
    } else {
      // Basit notification
      if (confirm('Yeni güncelleme mevcut! Şimdi güncellemek ister misiniz?')) {
        this.applyUpdate();
      }
    }
  }
  
  applyUpdate() {
    if (this.swRegistration && this.swRegistration.waiting) {
      this.swRegistration.waiting.postMessage({ type: 'skip-waiting' });
    }
  }
  
  async checkForUpdates() {
    if (this.swRegistration) {
      try {
        await this.swRegistration.update();
        console.log('[PWA] Manual update check complete');
      } catch (error) {
        console.error('[PWA] Update check failed:', error);
      }
    }
  }
  
  scheduleUpdateChecks() {
    // Her 1 saatte bir güncelleme kontrolü
    setInterval(() => {
      console.log('[PWA] Checking for updates...');
      this.checkForUpdates();
    }, 60 * 60 * 1000); // 1 saat
  }
  
  handleServiceWorkerMessage(event) {
    const { type, data } = event.data;
    
    console.log('[PWA] Message from SW:', type, data);
    
    switch (type) {
      case 'sync-success':
        this.showSyncNotification(data);
        break;
        
      case 'cache-updated':
        console.log('[PWA] Cache updated');
        break;
        
      default:
        console.log('[PWA] Unknown message type:', type);
    }
  }
  
  // ==================== INSTALL PROMPT ====================
  
  setupInstallPrompt() {
    // beforeinstallprompt event'ini dinle
    window.addEventListener('beforeinstallprompt', (e) => {
      console.log('[PWA] Install prompt available');
      
      // Otomatik prompt'u engelle
      e.preventDefault();
      
      // Daha sonra kullanmak üzere sakla
      this.deferredPrompt = e;
      
      // Install butonunu göster
      this.showInstallButton();
    });
    
    // Başarılı kurulum
    window.addEventListener('appinstalled', () => {
      console.log('[PWA] App installed successfully');
      this.deferredPrompt = null;
      this.hideInstallButton();
      this.trackInstallation();
    });
  }
  
  showInstallButton() {
    const installBtn = document.getElementById('pwa-install-btn');
    
    if (installBtn) {
      installBtn.style.display = 'block';
      installBtn.addEventListener('click', () => this.promptInstall());
    } else {
      // Dinamik buton oluştur
      this.createInstallButton();
    }
  }
  
  createInstallButton() {
    const button = document.createElement('button');
    button.id = 'pwa-install-btn';
    button.className = 'fixed bottom-4 right-4 bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-blue-700 transition-all duration-300 flex items-center gap-2 z-50';
    button.innerHTML = `
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
      </svg>
      <span>Uygulamayı Yükle</span>
    `;
    
    button.addEventListener('click', () => this.promptInstall());
    document.body.appendChild(button);
  }
  
  hideInstallButton() {
    const installBtn = document.getElementById('pwa-install-btn');
    if (installBtn) {
      installBtn.style.display = 'none';
    }
  }
  
  async promptInstall() {
    if (!this.deferredPrompt) {
      console.log('[PWA] Install prompt not available');
      return;
    }
    
    // Install prompt'u göster
    this.deferredPrompt.prompt();
    
    // Kullanıcı seçimini bekle
    const { outcome } = await this.deferredPrompt.userChoice;
    
    console.log('[PWA] User choice:', outcome);
    
    if (outcome === 'accepted') {
      console.log('[PWA] User accepted install');
    } else {
      console.log('[PWA] User dismissed install');
    }
    
    // Prompt'u temizle
    this.deferredPrompt = null;
    this.hideInstallButton();
  }
  
  trackInstallation() {
    // Analytics'e gönder (varsa)
    if (typeof gtag !== 'undefined') {
      gtag('event', 'pwa_install', {
        event_category: 'engagement',
        event_label: 'PWA Installed'
      });
    }
  }
  
  // ==================== PUSH NOTIFICATIONS ====================
  
  checkPushSupport() {
    if (!('Notification' in window)) {
      console.warn('[PWA] Notifications not supported');
      return false;
    }
    
    if (!('PushManager' in window)) {
      console.warn('[PWA] Push messaging not supported');
      return false;
    }
    
    console.log('[PWA] Push notifications supported');
    return true;
  }
  
  async requestNotificationPermission() {
    if (!this.checkPushSupport()) {
      return false;
    }
    
    try {
      const permission = await Notification.requestPermission();
      
      console.log('[PWA] Notification permission:', permission);
      
      if (permission === 'granted') {
        await this.subscribeToPush();
        return true;
      }
      
      return false;
    } catch (error) {
      console.error('[PWA] Notification permission error:', error);
      return false;
    }
  }
  
  async subscribeToPush() {
    if (!this.swRegistration) {
      console.warn('[PWA] No service worker registration');
      return null;
    }
    
    try {
      // VAPID public key'i backend'den al
      const vapidPublicKey = await this.getVAPIDPublicKey();
      
      const subscription = await this.swRegistration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: this.urlBase64ToUint8Array(vapidPublicKey)
      });
      
      console.log('[PWA] Push subscription:', subscription);
      
      // Subscription'ı backend'e gönder
      await this.sendSubscriptionToBackend(subscription);
      
      return subscription;
    } catch (error) {
      console.error('[PWA] Push subscription failed:', error);
      return null;
    }
  }
  
  async getVAPIDPublicKey() {
    try {
      const response = await fetch('/api/push/vapid-key');
      const data = await response.json();
      return data.publicKey;
    } catch (error) {
      console.error('[PWA] Failed to get VAPID key:', error);
      // Fallback - geliştirme için placeholder
      return 'BEl62iUYgUivxIkv69yViEuiBIa-Ib37J8xYjEB6jdGYn4mKB0Xh6Fjqf8q_6_6_6_6_6_6_6_6_6_6_6_6_6_6';
    }
  }
  
  async sendSubscriptionToBackend(subscription) {
    try {
      const response = await fetch('/api/push/subscribe', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(subscription)
      });
      
      if (!response.ok) {
        throw new Error('Subscription failed');
      }
      
      console.log('[PWA] Subscription sent to backend');
    } catch (error) {
      console.error('[PWA] Failed to send subscription:', error);
    }
  }
  
  urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
      .replace(/\-/g, '+')
      .replace(/_/g, '/');
    
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    
    for (let i = 0; i < rawData.length; ++i) {
      outputArray[i] = rawData.charCodeAt(i);
    }
    
    return outputArray;
  }
  
  // ==================== ONLINE/OFFLINE ====================
  
  setupEventListeners() {
    // Online/Offline event'leri
    window.addEventListener('online', () => this.handleOnline());
    window.addEventListener('offline', () => this.handleOffline());
    
    // Sayfa görünürlüğü
    document.addEventListener('visibilitychange', () => {
      if (!document.hidden) {
        this.checkForUpdates();
      }
    });
    
    // Sayfa yüklendiğinde
    window.addEventListener('load', () => {
      this.updateOnlineStatus();
    });
  }
  
  handleOnline() {
    console.log('[PWA] Online');
    this.isOnline = true;
    this.updateOnlineStatus();
    
    // Background sync tetikle
    if (this.swRegistration && 'sync' in this.swRegistration) {
      this.swRegistration.sync.register('sync-offline-requests')
        .then(() => console.log('[PWA] Background sync registered'))
        .catch(err => console.error('[PWA] Background sync failed:', err));
    }
    
    // Bildirim göster
    this.showOnlineNotification();
  }
  
  handleOffline() {
    console.log('[PWA] Offline');
    this.isOnline = false;
    this.updateOnlineStatus();
    
    // Bildirim göster
    this.showOfflineNotification();
  }
  
  updateOnlineStatus() {
    const statusIndicator = document.getElementById('online-status');
    
    if (statusIndicator) {
      if (this.isOnline) {
        statusIndicator.classList.remove('offline');
        statusIndicator.classList.add('online');
        statusIndicator.textContent = 'Çevrimiçi';
      } else {
        statusIndicator.classList.remove('online');
        statusIndicator.classList.add('offline');
        statusIndicator.textContent = 'Çevrimdışı';
      }
    }
  }
  
  showOnlineNotification() {
    this.showToast('İnternet bağlantısı geri geldi', 'success');
  }
  
  showOfflineNotification() {
    this.showToast('İnternet bağlantısı kesildi. Bazı özellikler çalışmayabilir.', 'warning');
  }
  
  showSyncNotification(data) {
    this.showToast('Offline yapılan işlemler senkronize edildi', 'success');
  }
  
  // ==================== UTILITY METHODS ====================
  
  showToast(message, type = 'info') {
    // Toast bildirim göster (Tailwind kullanarak)
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 animate-slide-in ${
      type === 'success' ? 'bg-green-500' :
      type === 'warning' ? 'bg-yellow-500' :
      type === 'error' ? 'bg-red-500' :
      'bg-blue-500'
    } text-white`;
    
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
      toast.classList.add('animate-slide-out');
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }
  
  async clearCache() {
    if (this.swRegistration) {
      await this.swRegistration.active.postMessage({ type: 'clear-cache' });
      console.log('[PWA] Cache cleared');
    }
  }
  
  async unregister() {
    if (this.swRegistration) {
      await this.swRegistration.unregister();
      console.log('[PWA] Service Worker unregistered');
    }
  }
}

// Global PWA instance oluştur
const pwa = new PWAHandler();

// Global scope'a ekle
window.PWA = pwa;

// Export
export default pwa;