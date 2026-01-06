// PWA Registration and Installation Handler
// This file handles service worker registration and PWA installation prompts

// Register Service Worker
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    // Register service worker with proper scope
    navigator.serviceWorker
      .register('/service-worker.js', { scope: '/' })
      .then((registration) => {
        console.log('[PWA] Service Worker registered successfully:', registration.scope);

        // Check for updates periodically
        setInterval(() => {
          registration.update();
        }, 60000); // Check every minute

        // Handle service worker updates
        registration.addEventListener('updatefound', () => {
          const newWorker = registration.installing;
          newWorker.addEventListener('statechange', () => {
            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
              // New service worker available
              showUpdateNotification();
            }
          });
        });
      })
      .catch((error) => {
        console.error('[PWA] Service Worker registration failed:', error);
      });

    // Listen for service worker controller changes
    navigator.serviceWorker.addEventListener('controllerchange', () => {
      console.log('[PWA] Service Worker controller changed - reloading page');
      window.location.reload();
    });
  });
}

// PWA Installation Prompt
let deferredPrompt;
let installButton = null;

// Listen for the beforeinstallprompt event
window.addEventListener('beforeinstallprompt', (e) => {
  console.log('[PWA] Installation prompt available');
  // Prevent the default browser install prompt
  e.preventDefault();
  // Store the event for later use
  deferredPrompt = e;
  // Show custom install button if it exists
  showInstallButton();
});

// Show install button if it exists in the DOM
function showInstallButton() {
  installButton = document.getElementById('pwa-install-button');
  if (installButton) {
    installButton.style.display = 'block';
    installButton.addEventListener('click', installPWA);
  }
}

// Install PWA
function installPWA() {
  if (!deferredPrompt) {
    return;
  }

  // Show the install prompt
  deferredPrompt.prompt();

  // Wait for the user to respond
  deferredPrompt.userChoice.then((choiceResult) => {
    if (choiceResult.outcome === 'accepted') {
      console.log('[PWA] User accepted the install prompt');
    } else {
      console.log('[PWA] User dismissed the install prompt');
    }
    // Clear the deferredPrompt
    deferredPrompt = null;
    // Hide install button
    if (installButton) {
      installButton.style.display = 'none';
    }
  });
}

// Check if app is already installed
window.addEventListener('appinstalled', () => {
  console.log('[PWA] App installed successfully');
  deferredPrompt = null;
  if (installButton) {
    installButton.style.display = 'none';
  }
  // Show success message
  showNotification('E-Tinda has been installed successfully!', 'success');
});

// Check if app is running in standalone mode (installed)
function isStandalone() {
  return (
    window.matchMedia('(display-mode: standalone)').matches ||
    window.navigator.standalone === true ||
    document.referrer.includes('android-app://')
  );
}

// Show update notification
function showUpdateNotification() {
  // Create a simple notification element
  const notification = document.createElement('div');
  notification.className = 'pwa-update-notification alert alert-info alert-dismissible fade show';
  notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
  notification.innerHTML = `
    <strong>Update Available!</strong>
    <p>A new version of E-Tinda is available.</p>
    <button type="button" class="btn btn-sm btn-primary" onclick="window.location.reload()">
      Update Now
    </button>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  `;
  document.body.appendChild(notification);

  // Auto-dismiss after 10 seconds
  setTimeout(() => {
    if (notification.parentNode) {
      notification.remove();
    }
  }, 10000);
}

// Show notification helper
function showNotification(message, type = 'info') {
  // Use existing toast system if available
  if (typeof toastr !== 'undefined') {
    toastr[type](message);
  } else {
    // Fallback to console
    console.log(`[PWA Notification] ${type}: ${message}`);
  }
}

// Export functions for global use
window.installPWA = installPWA;
window.isStandalone = isStandalone;

