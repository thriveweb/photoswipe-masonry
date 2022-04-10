import ImagesLoaded from 'imagesloaded';
import Masonry from 'masonry-layout';
import ThumbnailHelper from './helpers/thumbnail-helper';

function initializeMasonry(useMasonry, psgalContainerId, thumbWidth) {
  if (!useMasonry) {
    // initialize Masonry after all images have loaded
    /* eslint-disable no-new */
    (new Masonry(psgalContainerId, {
      // options...
      itemSelector: '.msnry_item',

      // In case the thumbWidth is a string, parse it to get the number.
      // Failure to do so will cause a runtime error when parsed by
      // Masonry.
      columnWidth: ThumbnailHelper.parseWidth(thumbWidth),
      fitWidth: true,
      resize: true,
    }));

    psgalContainerId.classList.add('photoswipe_showme');
  }
}

function initializeGallery(galleryElement) {
  const useMasonry = galleryElement
    .getAttribute('data-psgal_use_masonary');
  const thumbWidth = galleryElement
    .getAttribute('data-psgal_thumbnail_width');
  const psgalId = galleryElement
    .getAttribute('data-psgal_id');

  const psgalContainerId = document.querySelector(`#psgal_${psgalId}`);

  // initialize after all images have loaded
  ImagesLoaded.imagesLoaded(
    psgalContainerId,
    initializeMasonry(useMasonry, psgalContainerId, thumbWidth),
  );
}

document
  .querySelectorAll('.psgal')
  .forEach((galleryElement) => initializeGallery(galleryElement));
