if ('serviceWorker' in navigator) {
  window.addEventListener('load',()=> {
  navigator.serviceWorker.register('js/service-worker.js');
})};

navigator.serviceWorker.ready.then(console.log('Service Worker is running.'));