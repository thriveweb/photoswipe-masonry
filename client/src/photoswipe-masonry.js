/* global jQuery */

import PhotoSwipe from 'photoswipe';
import GalleryHelper from './helpers/gallery-helper';

var photoswipe_masonry = function ($) {
  const $pswp = $('.pswp')[0];
  const image = [];

  /// //////////////////////////////////////////////////////////////////////////////////////////
  // Gallery
  $('.psgal').each(function () {
    const $pic = $(this);
    const getItems = function () {
      const items = [];
      $pic.find('a').each(function () {
        const $href = $(this).attr('href');
        const $size = $(this).data('size').split('x');
        const $width = $size[0];
        const $height = $size[1];

        const item = {
          src: $href,
          w: $width,
          h: $height,
          el: $(this),
          msrc: $(this).find('img').attr('src'),
          title: $(this).attr('data-caption'),
        };
        items.push(item);
      });
      return items;
    };

    const items = getItems();

    /* $.each(items, function(index, value) {
      image[index]     = new Image();
      image[index].src = value['src'];
    }); */

    $pic.on('click', 'figure', function (event) {
      event.preventDefault();
      const $index = $(this).index();

      const options = {
        index: $index,
        bgOpacity: 0.9,
        showHideOpacity: false,
        galleryUID: $(this).parents('.psgal').attr('id'),
        getThumbBoundsFn(index) {
          const image = items[index].el.find('img');
          const offset = image.offset();
          return { x: offset.left, y: offset.top, w: image.width() };
        },
      };

      const lightBox = new PhotoSwipe($pswp, PhotoSwipeUI_Default, items, options);
      lightBox.init();
    });
  });

  /// //////////////////////////////////////////////////////////////////////////////////////////
  // Single image
  $('.single_photoswipe').each(function () {
    const $pic = $(this);
    const getItems = function () {
      const items = [];
      $pic.each(function () {
        const $href = $(this).attr('href');
        const $size = $(this).data('size').split('x');
        const $width = $size[0];
        const $height = $size[1];

        const item = {
          src: $href,
          w: $width,
          h: $height,
          el: $(this),
          msrc: $(this).find('img').attr('src'),
          title: $(this).find('img').attr('title'),
        };

        items.push(item);
      });
      return items;
    };

    const items = getItems();

    /* $.each(items, function(index, value) {
      image[index]     = new Image();
      image[index].src = value['src'];
    }); */

    $pic.on('click', 'img', function (event) {
      event.preventDefault();

      const $index = $(this).index();

      const options = {
        index: $index,
        shareEl: false,
        // galleryUID: $(this).parent().attr('id'),
        // bgOpacity: 0.9,
        // showHideOpacity: true,
        getThumbBoundsFn(index) {
          const image = items[index].el.find('img');
          const offset = image.offset();
          return { x: offset.left, y: offset.top, w: image.width() };
        },
      };

      const lightBox = new PhotoSwipe($pswp, PhotoSwipeUI_Default, items, options);
      lightBox.init();
    });
  });

  /// //////////////////////////////////////////////////////////////////////////////////////////
  // Parse URL and open gallery if it contains #&pid=3&gid=1
  const hashData = GalleryHelper.parseHash(window.location.hash.substring(1));

  if (hashData.gid) {
    $(`#${hashData.gid}`).each(function () {
      const $pic = $(this);
      const getItems = function () {
        const items = [];

        $pic.find('a').each(function () {
          const $href = $(this).attr('href');
          const $size = $(this).data('size').split('x');
          const $width = $size[0];
          const $height = $size[1];

          const item = {
            src: $href,
            w: $width,
            h: $height,
            el: $(this),
            msrc: $(this).find('img').attr('src'),
            title: $(this).attr('data-caption'),
          };
          items.push(item);
        });
        return items;
      };

      const items = getItems();
      $.each(items, (index, value) => {
        image[index] = new Image();
        image[index].src = value.src;
      });

      const $index = $(this).index();
      const options = {
        index: $index,
        bgOpacity: 0.9,
        showHideOpacity: false,
        galleryUID: `#${hashData.gid}`,
        getThumbBoundsFn(index) {
          const image = items[index].el.find('img');
          const offset = image.offset();
          return { x: offset.left, y: offset.top, w: image.width() };
        },
      };

      const lightBox = new PhotoSwipe(
        $pswp,
        PhotoSwipeUI_Default,
        items,
        options
      );

      lightBox.init();
    });
  }
};
