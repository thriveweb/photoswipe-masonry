jQuery(function ($) {
	$('.psgal-block').each(function () {
		var $grid = $(this);

		$grid.masonry({
			itemSelector: '.msnry_items',
			gutter: parseInt($grid.data('psgalGap'), 10) || 0,
			percentPosition: true
		});
		$grid.addClass('photoswipe_showme');

		// Re-layout as lazy-loaded images arrive
		$grid.imagesLoaded().progress(function () {
			$grid.masonry('layout');
		});
	});
});
