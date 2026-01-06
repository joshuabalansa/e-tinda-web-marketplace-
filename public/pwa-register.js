// Fallback PWA Registration Script
// This file can be included directly if Vite is not available
// It provides basic service worker registration

(function() {
  'use strict';

  // Register Service Worker
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
      navigator.serviceWorker
        .register('/service-worker.js', { scope: '/' })
        .then(function(registration) {
          console.log('[PWA] Service Worker registered successfully:', registration.scope);
        })
        .catch(function(error) {
          console.error('[PWA] Service Worker registration failed:', error);
        });
    });
  }

  // PWA Installation Prompt Handler
  let deferredPrompt;
  window.addEventListener('beforeinstallprompt', function(e) {
    console.log('[PWA] Installation prompt available');
    e.preventDefault();
    deferredPrompt = e;
  });

  // Expose install function globally
  window.installPWA = function() {
    if (deferredPrompt) {
      deferredPrompt.prompt();
      deferredPrompt.userChoice.then(function(choiceResult) {
        if (choiceResult.outcome === 'accepted') {
          console.log('[PWA] User accepted the install prompt');
        }
        deferredPrompt = null;
      });
    }
  };
})();

