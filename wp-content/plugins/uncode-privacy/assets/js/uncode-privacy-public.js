(function( $ ) {
	'use strict';

	var query_args  = location.search,
		base_url = location.protocol + '//' + location.host + location.pathname;

	if ( -1 !== query_args.indexOf( 'notify=1' ) ) {
		window.history.replaceState( {}, document.title, base_url );
	}

	window.uncode_toolkit_privacy_has_consent = function( consent ) {
		// Check consents that are on by default first
		var consentSwitch = $('#gdpr-consent-' + consent);

		if (consentSwitch.length) {
			if (consentSwitch.attr('data-default-on') === 'true' && consentSwitch.prop('checked')) {
				return true;
			}
		}

		// Check saved cookies
		if ( Cookies.get('uncode_privacy[consent_types]') ) {
			var consentArray = JSON.parse( Cookies.get('uncode_privacy[consent_types]') );
			if ( consentArray.indexOf( consent ) > -1 ) {
				return true;
			}
		}

		return false;
	}

	$(function() {

		if ( ! Cookies.get('uncode_privacy[privacy_bar]') ) {
			$('.gdpr.gdpr-privacy-bar').delay(1000).slideDown(600);
		};

		/**
		 * This runs when user clicks on privacy preferences bar agree button.
		 * It submits the form that is still hidden with the cookies and consent options.
		 */
		$(document).on('click', '.gdpr.gdpr-privacy-bar .gdpr-agreement', function() {
			// $('.gdpr.gdpr-privacy-bar').removeAttr('style'); // Fix slideDown
			// console.log('remove style');
			$('.gdpr.gdpr-privacy-bar').addClass('gdpr-hide-bar');
			Cookies.set('uncode_privacy[privacy_bar]', 1, { expires: 365 });
			// $('.gdpr-privacy-preferences-frm').submit(); // Old code
		});

		/**
		 * Set the privacy bar cookie after privacy preference submission.
		 * This hides the privacy bar from showing after saving privacy preferences.
		 */
		$(document).on('submit', '.gdpr-privacy-preferences-frm', function() {
			Cookies.set('uncode_privacy[privacy_bar]', 1, { expires: 365 });
		});

		/**
		 * Display the privacy preferences modal.
		 */
		$(document).on('click', '.gdpr-preferences', function() {
			var type = $(this).data('type');
			$('.gdpr-overlay').fadeIn();
			$('body').addClass('gdpr-noscroll');
			$('.gdpr.gdpr-privacy-preferences .gdpr-wrapper').fadeIn();
		});

		/**
		 * Close the privacy preferences modal.
		 */
		$(document).on('click', '.gdpr.gdpr-privacy-preferences .gdpr-close, .gdpr-overlay', function() {
			$('.gdpr-overlay').fadeOut();
			$('body').removeClass('gdpr-noscroll');
			$('.gdpr.gdpr-privacy-preferences .gdpr-wrapper').fadeOut();
		});

		/**
		 * Trigger events
		 */
		$('body').on('click', '.gdpr-preferences', function(e){
			e.preventDefault();
			$(window).trigger('gdprOpen');
		}).on('click', '.gdpr.gdpr-privacy-preferences .gdpr-close, .gdpr-overlay', function(){
			$(window).trigger('gdprClose');
		});


		/**
		 * Check switch via JS
		 */
		var switches = $('.gdpr-switch').find('input');

		function add_active_color(el) {
			el.next().css('background', Uncode_Privacy_Parameters.accent_color);
		}

		function add_default_color(el) {
			el.next().css('background', '#ccc');
		}

		switches.each(function() {
			var _this = $(this);

			if ($('body').hasClass('logged-in')) {
				if (_this.prop('checked')) {
					add_active_color(_this);
				}
			}

			_this.on('change', function() {
				if (_this.prop('checked')) {
					add_active_color(_this);
				} else {
					add_default_color(_this);
				}
			});
		});

		if (!$('body').hasClass('logged-in')) {
			switches.each(function() {
				var _this = $(this);
				var type = _this.attr('name') == 'user_consents[]' ? 'consent' : 'cookie';

				if (type == 'consent') {
					var is_allowed = uncode_toolkit_privacy_has_consent(_this.val());

					if (is_allowed) {
						_this.prop('checked', true);
						add_active_color(_this);
					} else {
						_this.prop('checked', false);
						add_default_color(_this);
					}
				}
			});
		}
	});

})( jQuery );
