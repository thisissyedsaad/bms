/**
 * Centralized AJAX request service using jQuery
 */

// Initialize the service
const AjaxService = (function($) {
  // Make sure jQuery is available
  if (typeof $ === 'undefined') {
    console.error('jQuery is required for this service');
    return {};
  }
  
  // Default configuration for all requests
  const defaultConfig = {
    baseURL: window.location.origin,
    timeout: 30000, // 30 seconds timeout
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    }
  };

  // Add CSRF token to requests if available
  const csrfToken = $('meta[name="csrf-token"]').attr('content');
  if (csrfToken) {
    defaultConfig.headers['X-CSRF-TOKEN'] = csrfToken;
    
    // Also set up jQuery AJAX defaults for CSRF
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': csrfToken
      }
    });
  }

  // Show loading indicator
  const showLoader = () => {
    $('body').addClass('ajax-loading');
  };

  // Hide loading indicator
  const hideLoader = () => {
    $('body').removeClass('ajax-loading');
  };

  // Process request
  const processRequest = (url, options = {}) => {
    // Merge default headers with custom headers
    const headers = $.extend({}, defaultConfig.headers, options.headers || {});
    
    // Build full URL (with baseURL if the url doesn't start with http/https)
    const fullUrl = url.startsWith('http') ? 
      url : 
      `${defaultConfig.baseURL}${url.startsWith('/') ? url : '/' + url}`;
    
    // Create the jQuery ajax settings
    const settings = $.extend(true, {
      url: fullUrl,
      headers: headers,
      timeout: options.timeout || defaultConfig.timeout,
      beforeSend: function() {
        showLoader();
      },
      complete: function() {
        hideLoader();
      },
      error: function(jqXHR, textStatus, errorThrown) {
        // Handle errors based on status
        if (jqXHR.status) {
          switch (jqXHR.status) {
            case 401:
              console.error('Unauthorized access');
              break;
            case 403:
              console.error('Forbidden access');
              break;
            case 404:
              console.error('Resource not found');
              break;
            case 422:
              console.error('Validation failed', jqXHR.responseJSON);
              break;
            case 500:
              console.error('Server error');
              break;
            default:
              console.error('Request failed with status:', jqXHR.status);
          }
        } else if (textStatus === 'timeout') {
          console.error('Request timed out');
        } else if (textStatus === 'abort') {
          console.error('Request aborted');
        } else {
          console.error('Request failed:', textStatus, errorThrown);
        }
      }
    }, options);
    
    // Return a promise
    return $.ajax(settings);
  };

  // API methods
  return {
    /**
     * Perform a GET request
     * @param {string} url - The URL to request
     * @param {Object} params - URL parameters
     * @param {Object} options - Additional jQuery ajax options
     * @returns {Promise} - The jQuery ajax promise
     */
    get: function(url, params = {}, options = {}) {
      return processRequest(url, $.extend({
        method: 'GET',
        data: params
      }, options));
    },
    
    /**
     * Perform a POST request
     * @param {string} url - The URL to request
     * @param {Object} data - The data to send
     * @param {Object} options - Additional jQuery ajax options
     * @returns {Promise} - The jQuery ajax promise
     */
    post: function(url, data = {}, options = {}) {
      return processRequest(url, $.extend({
        method: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json'
      }, options));
    },
    
    /**
     * Perform a PUT request
     * @param {string} url - The URL to request
     * @param {Object} data - The data to send
     * @param {Object} options - Additional jQuery ajax options
     * @returns {Promise} - The jQuery ajax promise
     */
    put: function(url, data = {}, options = {}) {
      return processRequest(url, $.extend({
        method: 'PUT',
        data: JSON.stringify(data),
        contentType: 'application/json'
      }, options));
    },
    
    /**
     * Perform a DELETE request
     * @param {string} url - The URL to request
     * @param {Object} options - Additional jQuery ajax options
     * @returns {Promise} - The jQuery ajax promise
     */
    delete: function(url, options = {}) {
      return processRequest(url, $.extend({
        method: 'DELETE'
      }, options));
    },
    
    /**
     * Upload files with FormData
     * @param {string} url - The URL to request
     * @param {FormData} formData - FormData object with files
     * @param {Object} options - Additional jQuery ajax options
     * @returns {Promise} - The jQuery ajax promise
     */
    upload: function(url, formData, options = {}) {
      return processRequest(url, $.extend({
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false
      }, options));
    },
    
    /**
     * Create a custom instance with different default options
     * @param {Object} customConfig - Custom configuration
     * @returns {Object} - New AjaxService instance
     */
    create: function(customConfig = {}) {
      // This is a simplified approach
      const newDefaultConfig = $.extend(true, {}, defaultConfig, customConfig);
      
      // Return a new instance with the custom config
      return AjaxService($, newDefaultConfig);
    }
  };
})(jQuery); // Pass jQuery as parameter

// Export the service
window.AjaxService = AjaxService; 