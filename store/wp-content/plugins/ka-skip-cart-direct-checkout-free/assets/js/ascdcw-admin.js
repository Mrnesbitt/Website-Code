jQuery(document).ready(function($) {   
    $('.select-two-option').css('display','none');
    // Initialize select2
    $('#ascdcw_show_checkout_btn_on').select2({
        placeholder: '--select product type--',
        allowClear: true,
    }); 
    if (my_data.stored_product_types && my_data.stored_product_types.length > 0) {
        $('#ascdcw_show_checkout_btn_on').val(my_data.stored_product_types).trigger('change');
    }
    //if enable plugin checkbox is checked then enable the fields otherwise display none
    var enableDisableButton = $('#ascdcw_enable_checkout');   
    function toggleVisibility() {
        var displayStyle = enableDisableButton.is(':checked') ? 'inline-block' : 'none';
        var importantStyle = ' !important'; 
        $('#ascdcw_checkout_btn_text').attr('style', 'display: ' + displayStyle + importantStyle);
        $('#ascdcw_checkout_btn_text_color_on_hover').attr('style', 'display: ' + displayStyle + importantStyle);
        $('#ascdcw_checkout_btn_bg_color_on_hover').attr('style', 'display: ' + displayStyle + importantStyle);
        $('#ascdcw_checkout_btn_text_color').attr('style', 'display: ' + displayStyle + importantStyle);
        $('#ascdcw_checkout_btn_bg_color').attr('style', 'display: ' + displayStyle + importantStyle); 
        $('.forminp-multiselect').attr('style', 'display: ' + displayStyle + importantStyle);
        $('.woocommerce table.form-table th label').attr('style', 'display: ' + displayStyle + importantStyle);
        if (enableDisableButton.is(':checked')) {$('.colorpickpreview').addClass('show');}
        else{$('.colorpickpreview').removeClass('show');}

    } 
    setTimeout(function(){
        toggleVisibility();  
    },1000);
    enableDisableButton.on('change', function() {
        toggleVisibility();
    });
    
});