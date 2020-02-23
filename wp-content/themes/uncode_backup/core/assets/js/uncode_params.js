!function($) {
	$('.uncode-inner-tabs').each(function(){
		var $tabs = $(this),
			$lis = $('li', $tabs),
			$active = $('li.active', $tabs),
			activeTab = $active.attr('data-tab'),
			$parent = $tabs.parents('.vc_edit-form-tab').eq(0);

		activeTab = $active.attr('data-tab'),
		activeJSON = JSON.parse(activeTab);

		var showHideParams = function(activeJSON, start){

			$('.vc_shortcode-param', $parent).each(function(){
				var $shortcode_param = $(this),
					param_settings = $shortcode_param.attr('data-param_settings'),
					paramJSON = JSON.parse(param_settings),
					arrVal;

				if ( typeof paramJSON.tab != 'undefined' && typeof paramJSON.tab.element != 'undefined' && paramJSON.tab.element == activeJSON.param  ) {
					arrVal = paramJSON.tab.value;
		            arrVal.indexOf(activeJSON.tab)
		            if ( arrVal.indexOf(activeJSON.tab) < 0 ) {
		            	if ( start ) {
			            	$shortcode_param.hide();
			            } else {
			            	$shortcode_param.fadeOut(150);
			            }
		            } else {
		            	if ( start ) {
			            	$shortcode_param.show();
			            } else {
			            	setTimeout(function(){
				            	$shortcode_param.fadeIn(150);
			            	}, 150)
			            }
		            }
				}

			});

		}

		showHideParams(activeJSON, true);

		$lis.on('click', function(e){
			e.preventDefault();
			$lis.removeClass('active');
			var $li = $(this).addClass('active'),
				dataTab = $li.attr('data-tab'),
				tabJSON = JSON.parse(dataTab);

			showHideParams(tabJSON, false);

		});
	});
}(window.jQuery);
