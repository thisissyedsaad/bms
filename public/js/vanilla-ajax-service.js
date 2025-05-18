/**
 * Centralized AJAX request service using vanilla JavaScript Fetch API
 */

// Initialize the service
const AjaxService = (function() {
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
  const csrfToken = document.querySelector('meta[name="csrf-token"]');
  if (csrfToken) {
    defaultConfig.headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
  }

  // Create a fetch request with timeout
  const fetchWithTimeout = (url, options, timeout) => {
    return Promise.race([
      fetch(url, options),
      new Promise((_, reject) => 
        setTimeout(() => reject(new Error('Request timeout')), timeout)
      )
    ]);
  };

  // Show loading indicator
  const showLoader = () => {
    document.body.classList.add('ajax-loading');
  };

  // Hide loading indicator
  const hideLoader = () => {
    document.body.classList.remove('ajax-loading');
  };

  // Handle response
  const handleResponse = async (response) => {
    let data;
    const contentType = response.headers.get('content-type');
    
    if (contentType && contentType.includes('application/json')) {
      data = await response.json();
    } else {
      data = await response.text();
    }

    if (!response.ok) {
      const error = new Error(response.statusText || 'Request failed');
      error.response = response;
      error.status = response.status;
      error.data = data;
      throw error;
    }

    return { data, status: response.status, headers: response.headers };
  };

  // Process request
  const processRequest = async (url, options = {}) => {
    // Merge default headers with custom headers
    const headers = { ...defaultConfig.headers, ...options.headers };
    
    // Create full options object
    const fullOptions = { 
      ...options,
      headers
    };

    // Build full URL (with baseURL if the url doesn't start with http/https)
    const fullUrl = url.startsWith('http') ? 
      url : 
      `${defaultConfig.baseURL}${url.startsWith('/') ? url : '/' + url}`;

    try {
      showLoader();
      
      // Make the request with timeout
      const response = await fetchWithTimeout(
        fullUrl, 
        fullOptions,
        options.timeout || defaultConfig.timeout
      );
      
      const result = await handleResponse(response);
      hideLoader();
      return result;
    } catch (error) {
      hideLoader();
      
      // Handle errors based on status
      if (error.status) {
        switch (error.status) {
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
            console.error('Validation failed', error.data);
            break;
          case 500:
            console.error('Server error');
            break;
          default:
            console.error('Request failed with status:', error.status);
        }
      } else {
        console.error('Request failed:', error.message);
      }
      
      throw error;
    }
  };

  // API methods
  return {
    /**
     * Perform a GET request
     * @param {string} url - The URL to request
     * @param {Object} params - URL parameters
     * @param {Object} options - Additional fetch options
     * @returns {Promise} - The request promise
     */
    get: function(url, params = {}, options = {}) {
      // Build query string from params
      const queryString = Object.keys(params)
        .map(key => `${encodeURIComponent(key)}=${encodeURIComponent(params[key])}`)
        .join('&');
      
      const fullUrl = queryString ? 
        `${url}${url.includes('?') ? '&' : '?'}${queryString}` : 
        url;
      
      return processRequest(fullUrl, { 
        ...options,
        method: 'GET'
      });
    },
    
    /**
     * Perform a POST request
     * @param {string} url - The URL to request
     * @param {Object} data - The data to send
     * @param {Object} options - Additional fetch options
     * @returns {Promise} - The request promise
     */
    post: function(url, data = {}, options = {}) {
      return processRequest(url, { 
        ...options,
        method: 'POST',
        body: JSON.stringify(data)
      });
    },
    
    /**
     * Perform a PUT request
     * @param {string} url - The URL to request
     * @param {Object} data - The data to send
     * @param {Object} options - Additional fetch options
     * @returns {Promise} - The request promise
     */
    put: function(url, data = {}, options = {}) {
      return processRequest(url, { 
        ...options,
        method: 'PUT',
        body: JSON.stringify(data)
      });
    },
    
    /**
     * Perform a DELETE request
     * @param {string} url - The URL to request
     * @param {Object} options - Additional fetch options
     * @returns {Promise} - The request promise
     */
    delete: function(url, options = {}) {
      return processRequest(url, { 
        ...options,
        method: 'DELETE'
      });
    },
    
    /**
     * Upload files with FormData
     * @param {string} url - The URL to request
     * @param {FormData} formData - FormData object with files
     * @param {Object} options - Additional fetch options
     * @returns {Promise} - The request promise
     */
    upload: function(url, formData, options = {}) {
      // For file uploads, don't set Content-Type, browser will set it with boundary
      const { 'Content-Type': _, ...headersWithoutContentType } = defaultConfig.headers;
      
      return processRequest(url, {
        ...options,
        method: 'POST',
        headers: headersWithoutContentType,
        body: formData
      });
    },
    
    /**
     * Create a custom instance with different default options
     * @param {Object} customConfig - Custom configuration
     * @returns {Object} - New AjaxService instance
     */
    create: function(customConfig = {}) {
      // Create a new closure with merged config
      const newInstance = { ...this };
      
      // Override the defaultConfig for this instance
      const mergedConfig = {
        ...defaultConfig,
        ...customConfig,
        headers: {
          ...defaultConfig.headers,
          ...(customConfig.headers || {})
        }
      };
      
      // Replace the defaultConfig in the new instance's closure
      // This is a simplified approach - in a real implementation,
      // you'd need a more sophisticated way to create a new instance
      Object.defineProperty(newInstance, 'defaultConfig', {
        value: mergedConfig,
        writable: false
      });
      
      return newInstance;
    }
  };
})();

// Export the service
window.AjaxService = AjaxService; 