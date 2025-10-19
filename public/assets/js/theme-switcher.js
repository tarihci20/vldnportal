/**
 * Theme Switcher - Dark/Light Tema Değiştirme
 * localStorage ile kullanıcı tercihini kaydet
 */

// Tema Yöneticisi
const ThemeManager = {
  // Varsayılan tema
  defaultTheme: 'light',
  
  // Tema anahtarı
  storageKey: 'vildan-portal-theme',
  
  // Mevcut temayı al
  getCurrentTheme() {
    return localStorage.getItem(this.storageKey) || this.defaultTheme;
  },
  
  // Temayı kaydet
  setTheme(theme) {
    localStorage.setItem(this.storageKey, theme);
    document.documentElement.setAttribute('data-theme', theme);
    this.updateThemeIcon(theme);
    this.dispatchThemeChange(theme);
  },
  
  // Temayı değiştir
  toggleTheme() {
    const currentTheme = this.getCurrentTheme();
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    this.setTheme(newTheme);
    
    // Animasyon efekti
    this.addToggleAnimation();
  },
  
  // Tema ikonunu güncelle
  updateThemeIcon(theme) {
    const icon = document.getElementById('theme-icon');
    const text = document.getElementById('theme-text');
    
    if (!icon) return;
    
    if (theme === 'dark') {
      // Açık tema ikonu (Güneş)
      icon.innerHTML = `
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
      `;
      if (text) text.textContent = 'Açık Tema';
    } else {
      // Koyu tema ikonu (Ay)
      icon.innerHTML = `
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
      `;
      if (text) text.textContent = 'Koyu Tema';
    }
  },
  
  // Toggle animasyonu
  addToggleAnimation() {
    const body = document.body;
    body.style.transition = 'none';
    
    // Flash effect
    body.style.opacity = '0.95';
    
    setTimeout(() => {
      body.style.transition = 'opacity 0.3s ease';
      body.style.opacity = '1';
    }, 50);
  },
  
  // Tema değişikliği event'i
  dispatchThemeChange(theme) {
    const event = new CustomEvent('themechange', { 
      detail: { theme } 
    });
    window.dispatchEvent(event);
  },
  
  // Sistem teması dinle
  watchSystemTheme() {
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    
    mediaQuery.addEventListener('change', (e) => {
      // Kullanıcı manuel tema seçmemişse sistem temasını uygula
      if (!localStorage.getItem(this.storageKey)) {
        const theme = e.matches ? 'dark' : 'light';
        this.setTheme(theme);
      }
    });
  },
  
  // Başlangıç
  init() {
    // Kaydedilmiş temayı uygula
    const savedTheme = this.getCurrentTheme();
    this.setTheme(savedTheme);
    
    // Sistem teması değişikliklerini dinle
    this.watchSystemTheme();
    
    // Keyboard shortcut (Ctrl/Cmd + Shift + D)
    document.addEventListener('keydown', (e) => {
      if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
        e.preventDefault();
        this.toggleTheme();
      }
    });
  }
};

// Sayfa yüklendiğinde tema sistemini başlat
document.addEventListener('DOMContentLoaded', () => {
  ThemeManager.init();
});

// Theme toggle butonu için global fonksiyon
function toggleTheme() {
  ThemeManager.toggleTheme();
}

// Tema bilgisini al (diğer scriptler için)
function getCurrentTheme() {
  return ThemeManager.getCurrentTheme();
}

// Tema değişikliğini dinle (diğer componentler için)
// Kullanım: window.addEventListener('themechange', (e) => { console.log(e.detail.theme) })

/**
 * Auto Theme Switcher
 * Belirli saatlerde otomatik tema değiştir (opsiyonel)
 */
const AutoThemeSwitcher = {
  enabled: false, // Varsayılan: kapalı
  
  // Saat ayarları
  darkModeStart: 18, // 18:00
  lightModeStart: 7,  // 07:00
  
  // Otomatik tema kontrolü
  checkTime() {
    if (!this.enabled) return;
    
    const hour = new Date().getHours();
    const currentTheme = ThemeManager.getCurrentTheme();
    
    if (hour >= this.darkModeStart || hour < this.lightModeStart) {
      // Gece modu
      if (currentTheme !== 'dark') {
        ThemeManager.setTheme('dark');
        this.showNotification('Koyu tema otomatik olarak aktif edildi');
      }
    } else {
      // Gündüz modu
      if (currentTheme !== 'light') {
        ThemeManager.setTheme('light');
        this.showNotification('Açık tema otomatik olarak aktif edildi');
      }
    }
  },
  
  // Bildirim göster
  showNotification(message) {
    // Toast notification (opsiyonel)
    console.log('Auto Theme:', message);
  },
  
  // Başlat
  init() {
    // İlk kontrol
    this.checkTime();
    
    // Her saat başı kontrol et
    setInterval(() => {
      this.checkTime();
    }, 60 * 60 * 1000); // 1 saat
  },
  
  // Etkinleştir/Devre dışı bırak
  toggle() {
    this.enabled = !this.enabled;
    localStorage.setItem('auto-theme-enabled', this.enabled);
    
    if (this.enabled) {
      this.init();
    }
  }
};

// Otomatik tema değiştiricisi (isteğe bağlı)
// AutoThemeSwitcher.toggle(); // Etkinleştirmek için

/**
 * Smooth Theme Transition
 * Daha smooth geçiş için CSS değişkenleri ile animasyon
 */
function smoothThemeTransition() {
  const root = document.documentElement;
  
  // Transition class ekle
  root.style.transition = 'background-color 0.3s ease, color 0.3s ease';
  
  // Tüm elementlere transition ekle
  document.querySelectorAll('*').forEach(el => {
    el.style.transition = 'all 0.3s ease';
  });
  
  // Transition'ı bir süre sonra kaldır (performans için)
  setTimeout(() => {
    root.style.transition = '';
    document.querySelectorAll('*').forEach(el => {
      el.style.transition = '';
    });
  }, 300);
}

/**
 * Theme Preview
 * Kullanıcıya tema önizlemesi göster
 */
function previewTheme(theme) {
  const currentTheme = ThemeManager.getCurrentTheme();
  
  // Geçici olarak temayı değiştir
  document.documentElement.setAttribute('data-theme', theme);
  
  // 3 saniye sonra eski temaya dön
  setTimeout(() => {
    document.documentElement.setAttribute('data-theme', currentTheme);
  }, 3000);
}

/**
 * Theme Analytics
 * Tema kullanım istatistikleri (opsiyonel)
 */
const ThemeAnalytics = {
  trackThemeChange(theme) {
    // Analytics servisine gönder
    console.log('Theme changed to:', theme);
    
    // Google Analytics örneği
    if (typeof gtag !== 'undefined') {
      gtag('event', 'theme_change', {
        'theme': theme
      });
    }
  }
};

// Tema değişikliklerini takip et
window.addEventListener('themechange', (e) => {
  ThemeAnalytics.trackThemeChange(e.detail.theme);
});

/**
 * Theme Sync
 * Birden fazla sekme arasında tema senkronizasyonu
 */
window.addEventListener('storage', (e) => {
  if (e.key === ThemeManager.storageKey) {
    const newTheme = e.newValue || ThemeManager.defaultTheme;
    document.documentElement.setAttribute('data-theme', newTheme);
    ThemeManager.updateThemeIcon(newTheme);
  }
});

// Export (modül olarak kullanılacaksa)
if (typeof module !== 'undefined' && module.exports) {
  module.exports = {
    ThemeManager,
    AutoThemeSwitcher,
    toggleTheme,
    getCurrentTheme
  };
}