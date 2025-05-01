jQuery(document).ready(function($) {
    // Check for the specific themes
    if ($('body').hasClass('theme-twentytwentythree') || 
        $('body').hasClass('theme-twentytwentyfour') || 
        $('body').hasClass('theme-twentytwentyfive')) { 
  
      //listing page button 
      $('.ascdcw-checkout-button').each(function() {
        var checkoutButton = $(this);
        checkoutButton.addClass('wp-element-button');
      });
      // Select the "Checkout" button and place it inside the li div
      $('.ascdcw-checkout-button.ascdcw-shop-page-btn').each(function() {
        var checkoutButton = $(this);
        
        // Find the closest parent <li> element and find the button container
        var listItem = checkoutButton.closest('li');
        var buttonContainer = listItem.find('.wp-block-button.wc-block-components-product-button.align-center');
        
        // If the button container exists, append the "Checkout" button to it
        if (buttonContainer.length) {
          // checkoutButton.addClass('wp-block-button__link');
          checkoutButton.addClass('wp-element-button');
         
          buttonContainer.append(checkoutButton);
        }
      });
    }
    if($('body').hasClass('theme-woodmart') && $('body').hasClass('woocommerce-shop'))
    {
      $('.ascdcw-checkout-button').each(function() {
        var checkoutButton = $(this);
        checkoutButton.addClass('wd-button-wrapper');
      });
    } 
  
    
  
  }); 
   