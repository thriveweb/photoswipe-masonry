
jQuery(".psgal-inline").each(function () {

    var use_masonry = jQuery(this).data('psgal_use_masonary');
    var thumb_width = jQuery(this).data('psgal_thumbnail_width');
    var psgal_id = jQuery(this).data('psgal_id');
    var psgal_container_id = '#psgal_' + psgal_id;
    var container = jQuery(psgal_container_id);

    // initialize  after all images have loaded
    container.imagesLoaded().progress(function () {
        if (use_masonry == 0) {
            container.masonry({
                // options...
                itemSelector: '.msnry_items',
                columnWidth: thumb_width,
                fitWidth: true,
                resize: true,
            });

            container.addClass(' photoswipe_showme');
        }
    }
    );
});
