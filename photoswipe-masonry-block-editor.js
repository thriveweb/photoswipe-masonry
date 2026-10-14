(function (wp) {
	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var addFilter = wp.hooks.addFilter;
	var createHigherOrderComponent = wp.compose.createHigherOrderComponent;
	var useSelect = wp.data.useSelect;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var PanelBody = wp.components.PanelBody;
	var ToggleControl = wp.components.ToggleControl;
	var RangeControl = wp.components.RangeControl;
	var __ = wp.i18n.__;

	var DEFAULT_GAP = 10;

	function isGallery(name) {
		return name === 'core/gallery';
	}

	// Matches core: columns defaults to min(3, image count)
	function getColumns(attributes, imageCount) {
		return attributes.columns || Math.max(1, Math.min(3, imageCount));
	}

	addFilter('blocks.registerBlockType', 'photoswipe-masonry/gallery-attributes', function (settings, name) {
		if (!isGallery(name)) {
			return settings;
		}
		settings.attributes = Object.assign({}, settings.attributes, {
			psMasonry: { type: 'boolean', default: false },
			psGap: { type: 'number', default: DEFAULT_GAP }
		});
		return settings;
	});

	var withMasonryControls = createHigherOrderComponent(function (BlockEdit) {
		return function (props) {
			if (!isGallery(props.name)) {
				return el(BlockEdit, props);
			}

			var attributes = props.attributes;
			var setAttributes = props.setAttributes;
			var imageCount = useSelect(function (select) {
				return select('core/block-editor').getBlockCount(props.clientId);
			}, [props.clientId]);

			return el(
				Fragment,
				null,
				el(BlockEdit, props),
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __('PhotoSwipe Masonry', 'photoswipe-masonry'), initialOpen: true },
						el(ToggleControl, {
							label: __('Masonry layout', 'photoswipe-masonry'),
							help: attributes.psMasonry
								? __('Images open in the PhotoSwipe lightbox. Link settings are ignored.', 'photoswipe-masonry')
								: __('Display this gallery as a PhotoSwipe masonry grid.', 'photoswipe-masonry'),
							checked: !!attributes.psMasonry,
							onChange: function (value) {
								setAttributes({ psMasonry: value });
							},
							__nextHasNoMarginBottom: true
						}),
						attributes.psMasonry && el(RangeControl, {
							label: __('Columns', 'photoswipe-masonry'),
							value: getColumns(attributes, imageCount),
							min: 1,
							max: 8,
							onChange: function (value) {
								setAttributes({ columns: value });
							},
							__next40pxDefaultSize: true,
							__nextHasNoMarginBottom: true
						}),
						attributes.psMasonry && el(RangeControl, {
							label: __('Spacing (px)', 'photoswipe-masonry'),
							value: attributes.psGap !== undefined ? attributes.psGap : DEFAULT_GAP,
							min: 0,
							max: 60,
							allowReset: true,
							resetFallbackValue: DEFAULT_GAP,
							onChange: function (value) {
								setAttributes({ psGap: value === undefined ? DEFAULT_GAP : value });
							},
							__next40pxDefaultSize: true,
							__nextHasNoMarginBottom: true
						})
					)
				)
			);
		};
	}, 'withPhotoSwipeMasonryControls');

	addFilter('editor.BlockEdit', 'photoswipe-masonry/gallery-controls', withMasonryControls);

	// Editor canvas preview
	var withMasonryPreview = createHigherOrderComponent(function (BlockListBlock) {
		return function (props) {
			if (!isGallery(props.name) || !props.attributes.psMasonry) {
				return el(BlockListBlock, props);
			}

			var attributes = props.attributes;
			var imageCount = useSelect(function (select) {
				return select('core/block-editor').getBlockCount(props.clientId);
			}, [props.clientId]);
			var wrapperProps = props.wrapperProps || {};
			var gap = attributes.psGap !== undefined ? attributes.psGap : DEFAULT_GAP;

			return el(BlockListBlock, Object.assign({}, props, {
				className: [props.className, 'is-psgal-masonry'].filter(Boolean).join(' '),
				wrapperProps: Object.assign({}, wrapperProps, {
					style: Object.assign({}, wrapperProps.style, {
						'--psgal-columns': getColumns(attributes, imageCount),
						'--psgal-gap': gap + 'px'
					})
				})
			}));
		};
	}, 'withPhotoSwipeMasonryPreview');

	addFilter('editor.BlockListBlock', 'photoswipe-masonry/gallery-preview', withMasonryPreview);

	// Standalone inserter entry: a Gallery with Masonry pre-enabled
	wp.blocks.registerBlockVariation('core/gallery', {
		name: 'photoswipe-masonry',
		title: __('PhotoSwipe Masonry Gallery', 'photoswipe-masonry'),
		description: __('Display images in a masonry grid that opens in the PhotoSwipe lightbox.', 'photoswipe-masonry'),
		keywords: ['photoswipe', 'masonry', 'lightbox', 'images', 'photos'],
		icon: el(
			'svg',
			{ xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', width: 24, height: 24 },
			el('path', { d: 'M4 4h7v9H4zM13 4h7v5h-7zM13 11h7v9h-7zM4 15h7v5H4z' })
		),
		attributes: { psMasonry: true },
		scope: ['inserter', 'transform'],
		isActive: function (blockAttributes) {
			return !!blockAttributes.psMasonry;
		}
	});
})(window.wp);
