if (window.location.href.includes('checkout')) {
    // Wait for 1 mili seconds before cleaning the URL
    setTimeout(function() {
      // Remove all query parameters from the URL
      var cleanUrl = window.location.href.split('?')[0];
      if (window.location.href !== cleanUrl) {
        window.history.replaceState({}, '', cleanUrl);
      }
    }, 1);
  }