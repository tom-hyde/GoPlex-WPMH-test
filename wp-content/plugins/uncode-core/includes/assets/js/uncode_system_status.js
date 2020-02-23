jQuery(document).ready(function ($) {
	$('#php-memory-check').on('click', function(e) {
		e.preventDefault();

		$('.saved-memory').remove();
		$('.real-memory .calculating').show();
		$('.real-memory .yes').hide();
		$('.real-memory .error').hide();

		var post_data = {
			action: 'uncode_php_test_memory',
			php_test_memory_nonce: SystemStatusParameters.nonce
		};

		$.post(ajaxurl, post_data, function (response) {
			if (response) {
				var get_memory_array = String(response).split('\n'),
					get_memory;
				$(get_memory_array).each(function(index, el) {
					var temp_memory = el.replace( /^\D+/g, '');
					if ('%'+temp_memory == el) get_memory = temp_memory;
				});
				var	memory_string;
				if (get_memory < 96) {
					memory_string = $('.real-memory .error');
				} else {
					memory_string = $('.real-memory .yes');
				}

				memory_string.text(memory_string.text().replace("%d%", get_memory));
				memory_string.show();
			}
		}).fail(function (response) {
			var get_memory_array = String(response.responseText).split('\n'),
				get_memory;
			$(get_memory_array).each(function(index, el) {
				var temp_memory = el.replace( /^\D+/g, '');
				if ('%'+temp_memory == el) get_memory = temp_memory;
			});
			var	memory_string;
			if (get_memory < 96) {
				memory_string = $('.real-memory .error');
			} else {
				memory_string = $('.real-memory .yes');
			}

			memory_string.text(memory_string.text().replace("%d%", get_memory));
			memory_string.show();
		}).always(function () {
			$('.real-memory .calculating').hide();
		});

	})
});
