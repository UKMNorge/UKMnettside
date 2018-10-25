jQuery(document).on('click', '#banner_image_select', function(e) {
    var custom_uploader = wp.media({
        title: 'Velg banner-bilde',
        button: {
            text: 'Bruk dette bildet'
        },
        multiple: false  // Set this to true to allow multiple files to be selected
    })
    .on('select', function() {
        var attachment = custom_uploader.state().get('selection').first().toJSON();
        
        //console.log( attachment );
        var image = attachment.sizes.large;
        if( image == null || image == undefined ) {
	        image = attachment.sizes.full;
        }
        
        jQuery('.forside-header').css('background-image', 'url('+ image.url +')');
        jQuery('.forside-detaljer').slideDown();
        jQuery('#banner_image_input').val( image.url );
        jQuery('#banner_image_id_input').val( attachment.id );
    })
    .open();
});
jQuery(document).on('click', '#banner_image_remove', function(){
    jQuery('.forside-header').attr('background-image', false);
    jQuery('.forside-detaljer').slideUp();
    jQuery('#banner_image_input').val(false);
});

jQuery(document).on('change', '#position-y', function(){
    jQuery('.forside-header').css('background-position-y', $(this).val().replace('bottom','95%') );
});

jQuery(document).on('change', '#position-x', function(){
    jQuery('.forside-header').css('background-position-x', $(this).val() );
});