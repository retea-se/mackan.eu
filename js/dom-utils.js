// DOM Utilities - Safe DOM operations with null checks
// Prevents "Cannot read properties of null" errors

(function() {
  'use strict';

  // Safe querySelector with null check
  window.safeQuery = function(selector, context = document) {
    try {
      return context.querySelector(selector);
    } catch (e) {
      console.warn(`safeQuery failed for selector: ${selector}`, e);
      return null;
    }
  };

  // Safe querySelectorAll with null check
  window.safeQueryAll = function(selector, context = document) {
    try {
      return Array.from(context.querySelectorAll(selector) || []);
    } catch (e) {
      console.warn(`safeQueryAll failed for selector: ${selector}`, e);
      return [];
    }
  };

  // Safe getElementById with null check
  window.safeGetById = function(id) {
    try {
      const element = document.getElementById(id);
      if (!element) {
        console.warn(`Element with id "${id}" not found`);
      }
      return element;
    } catch (e) {
      console.warn(`safeGetById failed for id: ${id}`, e);
      return null;
    }
  };

  // Safe value getter with default
  window.safeGetValue = function(elementOrId, defaultValue = '') {
    try {
      const element = typeof elementOrId === 'string'
        ? document.getElementById(elementOrId)
        : elementOrId;

      if (!element) {
        console.warn(`safeGetValue: element not found`, elementOrId);
        return defaultValue;
      }

      return element.value !== undefined ? element.value : defaultValue;
    } catch (e) {
      console.warn(`safeGetValue failed`, e);
      return defaultValue;
    }
  };

  // Safe value setter
  window.safeSetValue = function(elementOrId, value) {
    try {
      const element = typeof elementOrId === 'string'
        ? document.getElementById(elementOrId)
        : elementOrId;

      if (!element) {
        console.warn(`safeSetValue: element not found`, elementOrId);
        return false;
      }

      if (element.value !== undefined) {
        element.value = value;
        return true;
      }

      return false;
    } catch (e) {
      console.warn(`safeSetValue failed`, e);
      return false;
    }
  };

  // Safe innerHTML setter
  window.safeSetHTML = function(elementOrId, html) {
    try {
      const element = typeof elementOrId === 'string'
        ? document.getElementById(elementOrId)
        : elementOrId;

      if (!element) {
        console.warn(`safeSetHTML: element not found`, elementOrId);
        return false;
      }

      element.innerHTML = html;
      return true;
    } catch (e) {
      console.warn(`safeSetHTML failed`, e);
      return false;
    }
  };

  // Safe textContent setter
  window.safeSetText = function(elementOrId, text) {
    try {
      const element = typeof elementOrId === 'string'
        ? document.getElementById(elementOrId)
        : elementOrId;

      if (!element) {
        console.warn(`safeSetText: element not found`, elementOrId);
        return false;
      }

      element.textContent = text;
      return true;
    } catch (e) {
      console.warn(`safeSetText failed`, e);
      return false;
    }
  };

  // Safe addEventListener
  window.safeAddListener = function(elementOrId, event, handler, options) {
    try {
      const element = typeof elementOrId === 'string'
        ? document.getElementById(elementOrId)
        : elementOrId;

      if (!element) {
        console.warn(`safeAddListener: element not found`, elementOrId);
        return false;
      }

      element.addEventListener(event, handler, options);
      return true;
    } catch (e) {
      console.warn(`safeAddListener failed`, e);
      return false;
    }
  };

  // Safe classList operations
  window.safeAddClass = function(elementOrId, ...classNames) {
    try {
      const element = typeof elementOrId === 'string'
        ? document.getElementById(elementOrId)
        : elementOrId;

      if (!element) {
        console.warn(`safeAddClass: element not found`, elementOrId);
        return false;
      }

      element.classList.add(...classNames);
      return true;
    } catch (e) {
      console.warn(`safeAddClass failed`, e);
      return false;
    }
  };

  window.safeRemoveClass = function(elementOrId, ...classNames) {
    try {
      const element = typeof elementOrId === 'string'
        ? document.getElementById(elementOrId)
        : elementOrId;

      if (!element) {
        console.warn(`safeRemoveClass: element not found`, elementOrId);
        return false;
      }

      element.classList.remove(...classNames);
      return true;
    } catch (e) {
      console.warn(`safeRemoveClass failed`, e);
      return false;
    }
  };

  window.safeToggleClass = function(elementOrId, className, force) {
    try {
      const element = typeof elementOrId === 'string'
        ? document.getElementById(elementOrId)
        : elementOrId;

      if (!element) {
        console.warn(`safeToggleClass: element not found`, elementOrId);
        return false;
      }

      return element.classList.toggle(className, force);
    } catch (e) {
      console.warn(`safeToggleClass failed`, e);
      return false;
    }
  };

  console.log('✅ DOM utilities loaded - Safe DOM operations available');
})();
