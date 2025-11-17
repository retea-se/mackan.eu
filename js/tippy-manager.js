// Tippy.js Manager with MutationObserver for dynamic content
// Ensures tooltips work on dynamically added elements

(function() {
  'use strict';

  let tippyInitialized = false;
  let observer = null;

  // Initialize Tippy.js on all elements with data-tippy-content
  function initializeTippy(root = document) {
    if (typeof tippy === 'undefined') {
      console.warn('⚠️ Tippy.js not loaded yet');
      return false;
    }

    const elements = root.querySelectorAll('[data-tippy-content]');
    if (elements.length > 0) {
      try {
        tippy(elements);
        console.log(`✅ Tippy initialized on ${elements.length} element(s)`);
        return true;
      } catch (error) {
        console.error('❌ Tippy initialization error:', error);
        return false;
      }
    }
    return true;
  }

  // Setup MutationObserver to watch for dynamically added content
  function setupMutationObserver() {
    if (observer || typeof MutationObserver === 'undefined') {
      return;
    }

    observer = new MutationObserver(function(mutations) {
      let needsInit = false;

      mutations.forEach(function(mutation) {
        // Check added nodes
        mutation.addedNodes.forEach(function(node) {
          // Only process element nodes
          if (node.nodeType === 1) {
            // Check if the node itself has data-tippy-content
            if (node.hasAttribute && node.hasAttribute('data-tippy-content')) {
              needsInit = true;
            }
            // Check if any children have data-tippy-content
            if (node.querySelectorAll) {
              const tippyElements = node.querySelectorAll('[data-tippy-content]');
              if (tippyElements.length > 0) {
                needsInit = true;
              }
            }
          }
        });
      });

      // Initialize Tippy on new elements if needed
      if (needsInit && typeof tippy !== 'undefined') {
        setTimeout(function() {
          initializeTippy();
        }, 50); // Small delay to ensure DOM is settled
      }
    });

    // Start observing the document body for changes
    observer.observe(document.body, {
      childList: true,
      subtree: true
    });

    console.log('✅ MutationObserver setup for Tippy.js');
  }

  // Main initialization with retry mechanism
  function init(attempts = 0) {
    if (typeof tippy !== 'undefined') {
      // Initialize existing elements
      initializeTippy();

      // Setup observer for future dynamic content
      setupMutationObserver();

      tippyInitialized = true;
      console.log('✅ Tippy Manager fully initialized');
      return;
    }

    // Retry if Tippy not loaded yet
    if (attempts < 50) { // 5 seconds max (50 x 100ms)
      setTimeout(function() {
        init(attempts + 1);
      }, 100);
    } else {
      console.error('❌ Tippy.js failed to load after 5 seconds');
    }
  }

  // Start initialization when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
      init();
    });
  } else {
    // DOM already loaded
    init();
  }

  // Expose global function for manual reinitialization if needed
  window.reinitTippy = function(root) {
    initializeTippy(root);
  };
})();
