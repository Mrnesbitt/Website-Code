jQuery(function ($) {
    // Function for simple products
    function handleSimpleProduct() { 
        var url = ascdcw_vars.addtocart_url;
        var qtySelector = '.product-type-simple .quantity input.qty';
        var button = 'a.ascdcw-simple-product';
        var input_qty = $(qtySelector);

        function validateQuantity(input_field) {
            var quantity = parseInt(input_field.val(), 10);
            var max = parseInt(input_field.attr('max'), 10);
            var min = parseInt(input_field.attr('min'), 10) || 1;

            if (isNaN(quantity)) {
                input_field[0].setCustomValidity('Please enter a valid quantity.');
                return false;
            }
            if (quantity < min) {
                input_field[0].setCustomValidity(`The quantity must be at least ${min}.`);
                return false;
            }
            if (!isNaN(max) && quantity > max) {
                input_field[0].setCustomValidity(`The maximum available quantity is ${max}.`);
                return false;
            }
            input_field[0].setCustomValidity('');
            return true;
        }

        function updateButtonState() {
            if (validateQuantity(input_qty)) {
                var quantity = parseInt(input_qty.val(), 10) || 1;
                $(button).attr('href', url + '&quantity=' + quantity).removeClass('disabled').css('pointer-events', '');
            } else {
                $(button).addClass('disabled').css('pointer-events', 'none').removeAttr('href');
            }
        }

        input_qty.on('input change blur', function () { 
            validateQuantity(input_qty);
            input_qty[0].reportValidity();  // This triggers the custom validity message to be shown
            updateButtonState();
        });

        updateButtonState();
    }

    // Function for variable products
    function handleVariableProduct() { 
        var baseUrl = ascdcw_vars.addtocart_url;
        var button = 'a.ascdcw-variable-product';
        var qtySelector = '.variations_button .quantity input.qty';
        var form = $('.variations_form');

        function validateQuantity(input_field) {
            var quantity = parseInt(input_field.val(), 10);
            var max = parseInt(input_field.attr('max'), 10);
            var min = parseInt(input_field.attr('min'), 10) || 1;

            if (isNaN(quantity)) {
                input_field[0].setCustomValidity('Please enter a valid quantity.');
                return false;
            }
            if (quantity < min) {
                input_field[0].setCustomValidity(`The quantity must be at least ${min}.`);
                return false;
            }
            if (!isNaN(max) && quantity > max) {
                input_field[0].setCustomValidity(`The maximum available quantity is ${max}.`);
                return false;
            }
            input_field[0].setCustomValidity('');
            return true;
        }

        function updateButtonState() {
            var variationId = $('input[name="variation_id"]').val();
            var input_qty = $(qtySelector);

            if (validateQuantity(input_qty) && variationId) {
                var quantity = parseInt(input_qty.val(), 10) || 1;
                var attributes = form.serializeArray();
                var attributeParams = attributes
                    .filter(function (attr) {
                        return attr.name.startsWith('attribute_');
                    })
                    .map(function (attr) {
                        return attr.name + '=' + encodeURIComponent(attr.value);
                    })
                    .join('&');

                var checkoutUrl = baseUrl + '&variation_id=' + variationId + '&' + attributeParams + '&quantity=' + quantity;
                 
                $(button).attr('href', checkoutUrl).removeClass('disabled').css('pointer-events', '');
            } else {
                $(button).addClass('disabled').css('pointer-events', 'none').removeAttr('href');
            }
        }

        form.on('change', 'table.variations select', updateButtonState);
        $(qtySelector).on('input change blur', function () {
            validateQuantity($(this));
            $(this)[0].reportValidity();  // This triggers the custom validity message to be shown
            updateButtonState();
        });

        updateButtonState();
    }

    // Function for grouped products
    function handleGroupedProduct() { 
        var button = 'a.ascdcw-grouped-product'; // Button selector
        var table = '.woocommerce-grouped-product-list input.qty'; // Quantity input fields
         
        
        function validateQuantity(input_field) {
            var quantity = parseInt(input_field.val(), 10);
            var max = parseInt(input_field.attr('max'), 10);
            var min = parseInt(input_field.attr('min'), 10) || 0;
    
            if (isNaN(quantity)) {
                input_field[0].setCustomValidity('Please enter a valid quantity.');
                return false;
            }
            if (quantity < min) {
                input_field[0].setCustomValidity(`The quantity must be at least ${min}.`);
                return false;
            }
            if (!isNaN(max) && quantity > max) {
                input_field[0].setCustomValidity(`The maximum available quantity is ${max}.`);
                return false;
            }
            input_field[0].setCustomValidity('');
            return true;
        }
    
        function updateButtonState() {
            var isValid = true;
            var quantities = {};
            var allZero = true; 
            $(table).each(function () {
                var qtyInput = $(this);
                var productIdMatch = qtyInput.attr('name').match(/\[(\d+)\]/); // Extract product ID from the name attribute
                var qty = parseInt(qtyInput.val(), 10) || 0;
    
                // If product ID is found in the name attribute
                if (productIdMatch && productIdMatch[1]) {
                    var productId = productIdMatch[1]; 
                    // Check for valid quantity
                    if (qty > 0) {
                        if (!validateQuantity(qtyInput)) {
                            isValid = false;
                        } else {
                            quantities['quantity[' + productId + ']'] = qty;
                            allZero = false; // At least one quantity is greater than zero
                        }
                    }
                }
            }); 
            if (isValid && !allZero) { 
                var queryString = $.param(quantities);   
                $(button).attr('href', ascdcw_vars.addtocart_url + '?add-to-cart=' + ascdcw_vars.product_id + '&' + queryString)
                    .removeClass('disabled')
                    .css('pointer-events', '')
                    .prop('disabled', false);
            } else {
                $(button).addClass('disabled')
                    .css('pointer-events', 'none')
                    .removeAttr('href')
                    .prop('disabled', true);
            }
        }
     
        $(table).on('input change blur', function () {
            validateQuantity($(this));
            $(this)[0].reportValidity();   
            updateButtonState();
        });
     
        updateButtonState();
    }
 

    // Initialize appropriate handlers based on product type
    if (ascdcw_vars.product_type === 'simple') {
        handleSimpleProduct();
    } else if (ascdcw_vars.product_type === 'variable') {
        handleVariableProduct();
    } else if (ascdcw_vars.product_type === 'grouped') {
        handleGroupedProduct();
    }
});
