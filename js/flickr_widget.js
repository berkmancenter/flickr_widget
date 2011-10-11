jQuery(function() {
    jQuery('.flickr_badge_wrapper .flickr_image a').lightBox({
        imageLoading: '<?php echo plugins_url( '/images/lightbox-ico-loading.gif', __FILE__ ) ?>',
        imageBtnClose: '<?php echo plugins_url( '/images/lightbox-btn-close.gif', __FILE__ ) ?>',
        imageBtnPrev: '<?php echo plugins_url( '/images/lightbox-btn-prev.gif', __FILE__ ) ?>',
        imageBtnNext: '<?php echo plugins_url( '/images/lightbox-btn-next.gif', __FILE__ ) ?>',
        imageBlank: '<?php echo plugins_url( '/images/lightbox-blank.gif', __FILE__ ) ?>'
    });
});
