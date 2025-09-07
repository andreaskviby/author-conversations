import './bootstrap';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import persist from '@alpinejs/persist';
import intersect from '@alpinejs/intersect';

// Register Alpine.js plugins
Alpine.plugin(focus);
Alpine.plugin(persist);
Alpine.plugin(intersect);

// Make Alpine available globally
window.Alpine = Alpine;

// Start Alpine
Alpine.start();
