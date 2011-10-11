jQuery(function() {
    jQuery('.flickr_badge_wrapper .flickr_image a').lightBox({
        imageLoading: WPURLS.ImageRoot + 'lightbox-ico-loading.gif',
        imageBtnClose: WPURLS.ImageRoot + 'lightbox-btn-close.gif',
        imageBtnPrev: WPURLS.ImageRoot + 'lightbox-btn-prev.gif',
        imageBtnNext: WPURLS.ImageRoot + 'lightbox-btn-next.gif',
        imageBlank: WPURLS.ImageRoot + 'lightbox-blank.gif'
    });
});
