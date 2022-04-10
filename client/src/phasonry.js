/* global jQuery */

import PhotoSwipe from 'photoswipe';
import GalleryHelper from './helpers/gallery-helper';
import PhasonryItem from './phasonry-item';
import PhasonryGallery from './phasonry-gallery';

export class Phasonry
{
  construct() {
  }
  // Properties

  setupGalleries() {
    // Register click event for each photoswipe gallery on the page.
    document
      .querySelectorAll('.psgal')
      .forEach((galleryElement) => this.setupGallery(galleryElement));

    // Register events for every photoswipe gallery with a single image.
    // document.querySelectorAll('.single_photoswipe').forEach((i) => );
  }

  setupGallery(galleryElement) {
    const galleryItems = this.getItems(galleryElement);

    galleryElement
      .querySelectorAll('figure')
      .addEventListener('click', this.galleryItemClickEvent);
  };

  galleryItemClickEvent(e) {
    e.preventDefault();
    e.stopPropagation();

    const options = this.initializeItemOptions(
    );

    const lightBox = new PhotoSwipe();
    lightBox.init();
  }

  getItems(parentElement = document) {
    const items = [];

    parentElement
      .querySelectorAll('a')
      .forEach((galleryItem) => items.push(extractData(galleryItem)));

    return items;
  }

  extractData(element) {
    [width, height] = element.getAttribute('data-size').split('x');
    const imageSource = element.querySelector('img').src;

    return makePhotoSwipeItem(
      element.href,
      width,
      height,
      element,
      imageSource,
      element.getAttribute('data-caption');
    );
  }

}
