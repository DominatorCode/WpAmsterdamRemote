// controls show/hide of custom meta boxes "Rates", "Model Image" then tab was select/unselect
acf.addAction('show_field/type=text', function( field ){

    // if model tab was selected show it
    if (field.get('name') === 'model_rates')
        jQuery('#advanced-sortables').css('display', 'block');
    else if (field.get('name') === 'model_images')
        jQuery('#image-gallery-meta-box').css('display', 'block');

});

acf.addAction('hide_field/type=text', function( field ){

    // if model tab was selected show it
    if (field.get('name') === 'model_rates')
        jQuery('#advanced-sortables').css('display', 'none');
    else if (field.get('name') === 'model_images')
        jQuery('#image-gallery-meta-box').css('display', 'none');

});
