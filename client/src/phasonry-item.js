class PhasonryItem {
  construct(element) {
    this.element = element;
  }

  this.element = null;
  this.itemIndex = 0;
  this.galleryItemBackgroundOpacity = 1;

  getItemIndex() {
    return this.itemIndex;
  }

  getElement() {
    return this.element;
  }

  setItemIndex(value) {
    this.itemIndex = parseInt(value, 10);
  }
  getGalleryItemBackgroundOpacity() {
    return this.galleryItemBackgroundOpacity;
  }

  setGalleryItemBackgroundOpacity(value) {
    this.galleryItemBackgroundOpacity = parseInt(value.toString(), 10);
  }

  initializeItemOptions(galleryId) {
    return {
      index: this.getItemIndex(),
      bgOpacity: this.galleryItemBackgroundOpacity(),
      showHideOpacity: false,
      galleryUID: galleryId,
      getThumbBoundsFn(index) {

      }
    }
  }

  makePhotoSwipeItem(link, width, height, imageSource, title) {
    return {
      src: link,
      w: width,
      h: height,
      el: this.getElement(),
      msrc: imageSource,
      title: title
    }
  }
}
