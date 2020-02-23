var __ 					= wp.i18n.__,
	el 					= wp.element.createElement,
	registerBlockType 	= wp.blocks.registerBlockType,

	// Inspector Layout
	BlockControls 		= wp.editor.BlockControls,
	InspectorControls 	= wp.editor.InspectorControls,
	PanelBody			= wp.components.PanelBody,
	PanelRow 			= wp.components.PanelRow,

	// Controls
	Button 				= wp.components.Button,
	IconButton 			= wp.components.IconButton,
	TextControl 		= wp.components.TextControl,
	ToggleControl 		= wp.components.ToggleControl,
	SelectControl 		= wp.components.SelectControl,

	// Misc
	Placeholder 		= wp.components.Placeholder;

// In some rare cases the globally loaded LS_GB_l10n
// variable might not be available due to plugins making
// changes in the WP script queue. The below makes sure
// that we can at least avoid undef JS errors.
if( typeof LS_GB_l10n === 'undefined' ) {
	LS_GB_l10n = {};
}

registerBlockType( 'kreatura/layerslider', {

	title: 'LayerSlider',
	description: LS_GB_l10n.BlockDesc,
	keywords: [
		'animation',
		'gallery',
		'popup'
	],
	icon: 'images-alt2',
	category: 'widgets',
	supports: {
		html: false
	},

	attributes: {

		id: {
			type: 'string',
			default: ''
		},

		name: {
			type: 'string',
			default: ''
		},

		previewURL: {
			type: 'string',
			default: ''
		},

		autostart: {
			type: 'string',
			default: ''
		},

		firstslide: {
			type: 'string',
			default: ''
		},

		skin: {
			type: 'string',
			default: ''
		},

		slideCount: {
			type: 'integer',
			default: 1
		},

		marginTop: {
			type: 'string',
			default: ''
		},

		marginRight: {
			type: 'string',
			default: ''
		},

		marginBottom: {
			type: 'string',
			default: ''
		},

		marginLeft: {
			type: 'string',
			default: ''
		}
	},

	edit: function( props  ) {

		var attrs = props.attributes;
		var controls = [];



		// INSPECTOR CONTROLS
		// --------------------------------

		var skinsOptions = [{
			value: '',
			label: LS_GB_l10n.SkinInherit
		}];

		if( LS_GB_l10n.skins ) {
			for( var skinHandle in LS_GB_l10n.skins ) {
				skinsOptions.push({
					value: skinHandle,
					label: LS_GB_l10n.skins[ skinHandle ]
				})
			}
		}


		var lsInspectorControls =

			el( InspectorControls, {},

				// Panel Body
				el( PanelBody, { title: LS_GB_l10n.OverridePanel },

					// Description
					el( 'p', null, LS_GB_l10n.OverridePanelDesc ),


					// Skins
					el( SelectControl, {
						label: LS_GB_l10n.SkinLabel,
						value: attrs.skin,
						onChange: function( newValue ) {
							props.setAttributes({ skin: newValue })
						},
						options: skinsOptions
					}),


					// Auto Start Slideshow
					el( SelectControl, {
						label: LS_GB_l10n.AutoStartLabel,
						value: attrs.autostart,
						onChange: function( newValue ) {
							props.setAttributes({ autostart: newValue })
						},
						options: [
							{ value: '', label: LS_GB_l10n.AutoStartInherit },
							{ value: 'enabled', label: LS_GB_l10n.AutoStartEnable },
							{ value: 'disabled', label: LS_GB_l10n.AutoStartDisable },
						]
					}),


					// First Slide
					el( TextControl, {
						label: LS_GB_l10n.FirstSlideLabel,
						value: attrs.firstslide,
						placeholder: LS_GB_l10n.FirstSlideInherit,
						type: 'number',
						onChange: function( newValue ) {
							props.setAttributes({ firstslide: newValue });
						}
					}),
				),

				el( PanelBody, { title: LS_GB_l10n.LayoutPanel },

					// Description
					el( 'p', null, LS_GB_l10n.LayoutPanelDesc ),

					// Margin Controls
					el( 'p', {}, LS_GB_l10n.MarginLabel ),
					el( 'div', { className: 'ls-gb-margin-holder' },

						el( TextControl, {
							className: 'ls-gb-margin ls-gb-margin-top',
							value: attrs.marginTop,
							placeholder: '0px',
							onChange: function( newValue ) {
								props.setAttributes({ marginTop: newValue });
							}
						}),

						el( TextControl, {
							className: 'ls-gb-margin ls-gb-margin-right',
							value: attrs.marginRight,
							placeholder: LS_GB_l10n.MarginAutoPlaceholder,
							onChange: function( newValue ) {
								props.setAttributes({ marginRight: newValue });
							}
						}),

						el( TextControl, {
							className: 'ls-gb-margin ls-gb-margin-bottom',
							value: attrs.marginBottom,
							placeholder: '0px',
							onChange: function( newValue ) {
								props.setAttributes({ marginBottom: newValue });
							}
						}),

						el( TextControl, {
							className: 'ls-gb-margin ls-gb-margin-left',
							value: attrs.marginLeft,
							placeholder: LS_GB_l10n.MarginAutoPlaceholder,
							onChange: function( newValue ) {
								props.setAttributes({ marginLeft: newValue });
							}
						})
					)
				)
			);




		// BLOCK PLACEHOLDER
		// --------------------------------
		var lsBlockPlaceholder =
			el( Placeholder, {
					label: 'LayerSlider',
					icon: 'images-alt2',
					instructions: LS_GB_l10n.PlaceholderDesc,
				},

				el( Button, {
					isDefault: true,
					isLarge: true,
					onClick: function() {

						LS_SliderLibrary.openPopup( props );
					}
				}, LS_GB_l10n.SliderLibraryButton )

			);



		// Block Controls
		var lsBlockControls =

			el( BlockControls, null,
				el( wp.components.Toolbar, null,


					el( wp.components.ToolbarButton, {
						label: LS_GB_l10n.BlockEditLabel,
						icon: 'edit',
						onClick: function() {
							LS_SliderLibrary.openPopup( props );
						}
					})

				)
			);




		// BLOCK CONTENT
		// --------------------------------
		var classNames = 'ls-gb-block-content';

		if( ! attrs.previewURL ) {
			classNames += ' no-preview';
		}

		var lsBlockContent = el(
			'div', {
				className: classNames,
				style: {
					backgroundImage: 'url('+attrs.previewURL+')',
					marginTop: attrs.marginTop ? parseInt( attrs.marginTop )+'px' : 0,
					marginRight: attrs.marginRight ? parseInt( attrs.marginRight )+'px' : 0,
					marginBottom: attrs.marginBottom ? parseInt( attrs.marginBottom )+'px' : 0,
					marginLeft: attrs.marginLeft ? parseInt( attrs.marginLeft )+'px' : 0,
				}
			},
				el('div', { className: 'info' },
					el( 'div', { className: 'name' }, attrs.name )
				),

				el('span', { className: 'ls-arrow-left dashicons dashicons-arrow-left-alt2' }),
				el('span', { className: 'ls-arrow-right dashicons dashicons-arrow-right-alt2' }),
		);



		if( attrs.slideCount && attrs.slideCount > 1 ) {

			var lsSlidesHolder = el('div', { className: 'ls-slides-holder' } );
			lsBlockContent.props.children.push( lsSlidesHolder );

			if( ! lsSlidesHolder.props.children ) {
				lsSlidesHolder.props.children = [];
			}

			for( var c = 0; c < attrs.slideCount; c++ ) {

				lsSlidesHolder.props.children.push(
					el('span', { className: 'ls-slide-marker dashicons dashicons-marker' })
				);
			}
		}



		if( ! attrs.id ) {
			controls.push( lsBlockPlaceholder );
		} else {
			controls.push( lsBlockControls, lsBlockContent, lsInspectorControls );
		}

		return controls;
	},


	save: function( props ) {

		// We're going to be rendering in PHP, so save() can just return null.
		return null;
	}

});




var LS_SliderLibrary = {

	initialized: false,
	props: false,

	init: function() {

		if( ! LS_SliderLibrary.initialized ) {

			LS_SliderLibrary.initialized = true;

			// Close events
			$(document).on('click', '.gutenberg-layerslider-overlay', function() {
				LS_SliderLibrary.closePopup();
			});

			$(document).on('click', '.gutenberg-layerslider-window .close-modal', function( event ) {

				event.preventDefault();
				LS_SliderLibrary.closePopup();
			});

			$(document).on('keydown', function( event ) {

				if( event.which === 27 ) {
					LS_SliderLibrary.closePopup();
				}
			});

			// Select slider
			$(document).on('click', '.gutenberg-layerslider-window .slider-item', function( event ) {
				LS_SliderLibrary.selectSlider( event, $( event.currentTarget ) );
				LS_SliderLibrary.insertSlider();
				LS_SliderLibrary.closePopup();
			});

		}
	},


	openPopup : function( props ) {

		if( ! LS_SliderLibrary.initialized ) {
			LS_SliderLibrary.init();
		}

		LS_SliderLibrary.props = props;


		// If the popup isn't already open, create it and load its content using ajax
		if( ! $('.gutenberg-layerslider-window').length ) {

			var modalMarkup =
			'<div class="gutenberg-layerslider-window" tabindex="-1">\
				<a href="#" class="close-modal"></a>\
				<h3 class="header" tabindex="0">'+LS_GB_l10n.WindowTitle+'</h3>\
				<div class="inner"></div>\
			</div>';

			// Prepend modal
			var $modal = $( modalMarkup ).prependTo('body'),
				$inner = $('.inner', $modal);



			// Add overlay
			$('<div>', { 'class' : 'gutenberg-layerslider-overlay'}).prependTo('body');

			var itemMarkup =
			'<div class="slider-item">\
				<div class="slider-item-wrapper">\
					<div class="preview">\
						<div class="no-preview">\
							<h5>'+LS_GB_l10n.NoPreview+'</h5>\
							<small>'+LS_GB_l10n.NoPreviewText+'</small>\
						</div>\
					</div>\
					<div class="info">\
						<div class="name"></div>\
					</div>\
				</div>\
			</div>';

			// Get sliders
			$.getJSON(ajaxurl, { action : 'ls_get_mce_sliders' }, function(data) {
				$.each(data, function(index, item) {
					var $item = $(itemMarkup);

					$item.data({
						id: item.id,
						name: item.name,
						previewURL: item.preview,
						slideCount: item.data.layers.length
					});

					if( item.preview ) {
						$('.preview', $item).empty().css({
							'background-image': 'url('+item.preview+')'
						});
					}

					if( item.name ) {
						$('.name', $item).html( item.name );
					}

					$item.appendTo( $inner );

				});

			});
		}
	},


	searchSlider : function() {

	},

	selectSlider : function( event, $item ) {

		// Select item
		$item.addClass('selected').siblings().removeClass('selected');
	},


	insertSlider: function() {

		// Get selected item
		var $modal 	= $('.gutenberg-layerslider-window'),
			$item 	= $('.slider-item.selected', $modal ).eq(0);

		LS_SliderLibrary.props.setAttributes({
			id: $item.data('id'),
			name: $item.data('name'),
			previewURL: $item.data('previewURL'),
			slideCount: $item.data('slideCount')
		});

	},


	closePopup : function() {

		if( $('.gutenberg-layerslider-window').length ) {
			$('.gutenberg-layerslider-overlay').remove();
			$('.gutenberg-layerslider-window').remove();
		}
	}
};