jQuery(function ($) {
	$('.psgal-inline').each(function () {
		var use_masonry = $(this).data('psgal_use_masonary');
		var thumb_width = parseInt($(this).data('psgal_thumbnail_width'), 10) || 150;
		var psgal_id = $(this).data('psgal_id');
		var container = $('#psgal_' + psgal_id);
		var narrowQuery = window.matchMedia('(max-width: 650px)');

		if (use_masonry != 0) {
			container.addClass('photoswipe_showme');
			return;
		}

		function setItemWidths(colWidth) {
			container.children('.msnry_items').css('width', colWidth);
		}

		function masonryOptions() {
			if (narrowQuery.matches) {
				// Floor half-width so 2 cols always fit (50% rounding collapses to 1 col)
				var colWidth = Math.floor(container.width() / 2);
				setItemWidths(colWidth);
				return {
					itemSelector: '.msnry_items',
					columnWidth: colWidth
				};
			}
			setItemWidths(thumb_width);
			return {
				itemSelector: '.msnry_items',
				columnWidth: thumb_width,
				fitWidth: true,
				resize: true
			};
		}

		function initMasonry() {
			if (container.data('masonry')) {
				container.masonry('destroy');
			}
			container.masonry(masonryOptions());
			container.addClass('photoswipe_showme');
		}

		container.imagesLoaded(function () {
			initMasonry();
		});

		narrowQuery.addEventListener('change', function () {
			if (container.hasClass('photoswipe_showme')) {
				initMasonry();
			}
		});

		var resizeTimer;
		$(window).on('resize', function () {
			if (!narrowQuery.matches || !container.data('masonry')) {
				return;
			}
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(initMasonry, 150);
		});
	});
});
