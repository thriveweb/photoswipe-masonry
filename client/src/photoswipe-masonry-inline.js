jQuery('.psgal').each(function(){
  var use_masonry = jQuery(this).data('psgal_use_masonary');
  var thumb_width = jQuery(this).data('psgal_thumbnail_width');
  var psgal_id    = jQuery(this).data('psgal_id');
  var psgal_container_id = jQuery(this).data('psgal_container_id');
  var psgal_container_id = document.querySelector('#psgal_'+psgal_id);

  // initialize after all images have loaded
  imagesLoaded(psgal_container_id, function() {
    if (!use_masonry) {
      // initialize Masonry after all images have loaded
      new Masonry(psgal_container_id, {
        // options...
        itemSelector: '.msnry_item',

        // In case the thumb_width is a string, parse it to get the number.
        // Failure to do so will cause a runtime error when parsed by
        // Masonry.
        columnWidth: ThumbnailHelper.parseWidth(thumb_width),
        fitWidth: true,
        resize: true
      });
      (psgal_container_id).className += ' photoswipe_showme';
    }
  });

});

