(function($) {
	"use strict";
	/* global jQuery, UncodeUpdateParameters, pagenow */

	$(document).on('ready', function() {
		var update_info = '<p><a href="' + UncodeUpdateParameters.update_instructions_url + '" target="_blank" rel="noopener noreferrer">' + UncodeUpdateParameters.update_instructions_text + '</a><span style="padding:0 10px;">|</span><a href="' + UncodeUpdateParameters.changelog_url + '" target="_blank" rel="noopener noreferrer">' + UncodeUpdateParameters.changelog_text + '</a></p>';

		$('#update-themes-table .plugin-title > p > strong').each(function() {
			if ('Uncode' == $(this).html()) {
				$(this).parent().append(update_info);
			}
		});

		$('.theme-browser .themes .theme').click( function() {
			setTimeout(function() {
				if ($('.theme-overlay .theme-name').length) {
					if (-1 < $( '.theme-overlay .theme-name').text().indexOf('Uncode')) {
						$('.theme-overlay .theme-info .notice > p > strong').append(update_info);
						$('.theme-overlay').find('.thickbox.open-plugin-details-modal').removeClass().attr('href', UncodeUpdateParameters.changelog_url);

						// Show modal for premium update
						if (UncodeUpdateParameters.is_uncode_active !== '1') {
							$('.theme-overlay').find('#update-theme').on('click', function() {
								showModalForPremiumProducts([], 'theme');

								return false;
							});
						}
					}
				}
			}, 10);
		});

		$('a.open-plugin-details-modal').each(function() {
			for (var key in UncodeUpdateParameters.premium_plugins) {
				if ($(this).attr('href').indexOf(key) != -1) {
					$(this).removeClass('thickbox');
					$(this).attr('target', '_blank');
					$(this).attr('href', UncodeUpdateParameters.changelog_url);
				}
			}
		});

		var importForPremiumOnly = true;

		// Show modals only when Uncode is not active
		if (UncodeUpdateParameters.is_uncode_active !== '1') {
			if (pagenow) {
				if ('themes' === pagenow) {
					// Grid view
					$('.update-message').on('click', function() {
						var currentTheme = $(this).closest('.theme');

						if (currentTheme.attr('data-slug') === 'uncode') {
							showModalForPremiumProducts([], 'theme');

							return false;
						}
					});
				} else if ('uncode_page_uncode-plugins' === pagenow) {
					// TGMPA links
					$('.uncode-premium-plugin-link').on('click', function() {
						var actionType = $(this).attr('data-action-type');

						if ('install' === actionType || 'update' === actionType) {
							var currentPluginSlug = $(this).attr('data-plugin-slug');

							if (currentPluginSlug in UncodeUpdateParameters.premium_plugins) {
								var pluginTitle = UncodeUpdateParameters.premium_plugins[currentPluginSlug].plugin_name;

								showModalForPremiumProducts(pluginTitle, 'plugin', actionType);

								return false;
							}
						}
					});
				} else if ('uncode_page_uncode-import-demo' === pagenow) {
					if (importForPremiumOnly) {
						// Import buttons
						var importButtons = $('.uncode-import-button').not('.uncode-import-button--delete');

						// First unbind the default modals
						importButtons.off();

						importButtons.on('click', function() {
							showModalForPremiumProducts(false, 'import', 'import');

							return false;
						});
					}
				} else if ('plugins' === pagenow) {
					// Create an array that contains the slug of each premium plugin
					var premiumPluginsSlugs = [];

					for (var key in UncodeUpdateParameters.premium_plugins) {
						premiumPluginsSlugs.push(UncodeUpdateParameters.premium_plugins[key].plugin_path);
					}

					// Show modal during bulk actions
					var bulkActionForms = $('.bulkactions');

					bulkActionForms.each(function() {
						var bulkButton = $(this).find('.action');
						var bulkSelect = $(this).find('select');

						bulkButton.on('click', function() {
							if ('update-selected' === bulkSelect.val()) {
								var selectedPluginsRows = $('table.wp-list-table').find('th.check-column');
								var hasPremiumPluginsSelected = false;
								var premiumPluginsSelected = [];

								selectedPluginsRows.each(function() {
									var pluginCheckbox = $(this).find('input[type="checkbox"]');

									if (pluginCheckbox.prop('checked')) {
										var pluginRow = $(this).closest('tr');
										var pluginSlug = pluginRow.attr('data-plugin');

										if (premiumPluginsSlugs.indexOf(pluginSlug) != -1) {
											var pluginTitle = pluginRow.find('.plugin-title strong').first().text();
											hasPremiumPluginsSelected = true;
											premiumPluginsSelected.push(pluginTitle);
										}
									}
								});

								if (hasPremiumPluginsSelected) {
									showModalForPremiumProducts(premiumPluginsSelected, 'plugin');
									return false;
								}
							}
						});
					});

					// Show modal when someone clicks on the update now link
					$('a.update-link').each(function() {
						for (var key in UncodeUpdateParameters.premium_plugins) {
							if ($(this).attr('href').indexOf(key) != -1) {
								var pluginTitle = UncodeUpdateParameters.premium_plugins[key].plugin_name;

								$(this).on('click', function(e) {
									showModalForPremiumProducts(pluginTitle, 'plugin');

									return false;
								});
							}
						}
					});
				} else if ('update-core' === pagenow) {
					// Create an array that contains the slug of each premium plugin
					var premiumPluginsSlugs = [];

					for (var key in UncodeUpdateParameters.premium_plugins) {
						premiumPluginsSlugs.push(UncodeUpdateParameters.premium_plugins[key].plugin_path);
					}

					// Plugin updates
					$('#upgrade-plugins').on('click', function() {
						var selectedPluginsRows = $('#update-plugins-table').find('td.check-column');
						var hasPremiumPluginsSelected = false;
						var premiumPluginsSelected = [];

						selectedPluginsRows.each(function() {
							var pluginCheckbox = $(this).find('input[type="checkbox"]');

							if (pluginCheckbox.prop('checked')) {
								var pluginRow = $(this).closest('tr');
								var pluginSlug = pluginCheckbox.val();

								if (premiumPluginsSlugs.indexOf(pluginSlug) != -1) {
									var pluginTitle = pluginRow.find('.plugin-title strong').first().text();
									hasPremiumPluginsSelected = true;
									premiumPluginsSelected.push(pluginTitle);
								}
							}
						});

						if (hasPremiumPluginsSelected) {
							showModalForPremiumProducts(premiumPluginsSelected, 'plugin');

							return false;
						}
					});

					// Theme updates
					$('#upgrade-themes').on('click', function() {
						var selectedThemesRows = $('#update-themes-table').find('td.check-column');
						var hasUncodeSelected = false;

						selectedThemesRows.each(function() {
							var themeCheckbox = $(this).find('input[type="checkbox"]');

							if (themeCheckbox.prop('checked') && 'uncode' === themeCheckbox.val()) {
								hasUncodeSelected = true;
							}
						});

						if (hasUncodeSelected) {
							showModalForPremiumProducts([], 'theme');

							return false;
						}
					});
				}
			}
		}

		function showModalForPremiumProducts(product, type, action) {
			var action = typeof action  === 'undefined' ? 'update' : action;
			var html = '';

			if ('theme' === type) {
				html += '<p>' + UncodeUpdateParameters.modal_texts.block_theme_update + '</p>';
			} else if ('import' === type) {
				html += '<strong>' + UncodeUpdateParameters.modal_texts.block_import_title + '</strong><p>' + UncodeUpdateParameters.modal_texts.block_import + '</p>';
			} else {
				if (product.constructor === Array) {
					html += UncodeUpdateParameters.modal_texts.block_multiple_plugin_update.replace('%s', action);
					html += '<ul>';
					for (var i = 0; i < product.length; i++) {
						html += '<li><strong>' + product[i] + '</strong></li>';
					}
					html += '</ul>';
				} else {
					html += UncodeUpdateParameters.modal_texts.block_single_plugin_update.replace('%s', action);
					html += '<ul><li><strong>' + product + '</strong></li></ul>';
				}
			}

			html += ' <a href="' + UncodeUpdateParameters.system_status_url + '">' + UncodeUpdateParameters.modal_texts.modal_button + '</a></span>';

			$("<div />").html(html).dialog({
				autoOpen: true,
				modal: true,
				dialogClass: 'uncode-modal uncode-modal-block-premium',
				title: UncodeUpdateParameters.modal_texts.modal_title,
				maxHeight: 800,
				width: 600,
				position: { my: "center", at: "center", of: window },
				open: function( event, ui ) {
					$('body').addClass('overflow_hidden');
				},
				close: function( event, ui ) {
					$('body').removeClass('overflow_hidden');
				}
			});
		}
	});
})(jQuery);
