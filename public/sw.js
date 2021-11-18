/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************!*\
  !*** ./resources/js/sw.js ***!
  \****************************/
// Install service worker
self.addEventListener('install', function (event) {
  console.log('[ServiceWorker] Installed');
}); // Activate event

self.addEventListener('activate', function (event) {
  console.log('[ServiceWorker] Activated');
});
/******/ })()
;