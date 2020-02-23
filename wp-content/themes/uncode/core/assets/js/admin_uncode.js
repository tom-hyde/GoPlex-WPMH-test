/*!
 * Bez @VERSION
 * http://github.com/rdallasgray/bez
 *
 * A plugin to convert CSS3 cubic-bezier co-ordinates to jQuery-compatible easing functions
 *
 * With thanks to Nikolay Nemshilov for clarification on the cubic-bezier maths
 * See http://st-on-it.blogspot.com/2011/05/calculating-cubic-bezier-function.html
 *
 * Copyright @YEAR Robert Dallas Gray. All rights reserved.
 * Provided under the FreeBSD license: https://github.com/rdallasgray/bez/blob/master/LICENSE.txt
 */
(function(factory) {
  if (typeof exports === "object") {
    factory(require("jquery"));
  } else if (typeof define === "function" && define.amd) {
    define(["jquery"], factory);
  } else {
    factory(jQuery);
  }
}(function($) {
  $.extend({ bez: function(encodedFuncName, coOrdArray) {
    if ($.isArray(encodedFuncName)) {
      coOrdArray = encodedFuncName;
      encodedFuncName = 'bez_' + coOrdArray.join('_').replace(/\./g, 'p');
    }
    if (typeof $.easing[encodedFuncName] !== "function") {
      var polyBez = function(p1, p2) {
        var A = [null, null], B = [null, null], C = [null, null],
            bezCoOrd = function(t, ax) {
              C[ax] = 3 * p1[ax], B[ax] = 3 * (p2[ax] - p1[ax]) - C[ax], A[ax] = 1 - C[ax] - B[ax];
              return t * (C[ax] + t * (B[ax] + t * A[ax]));
            },
            xDeriv = function(t) {
              return C[0] + t * (2 * B[0] + 3 * A[0] * t);
            },
            xForT = function(t) {
              var x = t, i = 0, z;
              while (++i < 14) {
                z = bezCoOrd(x, 0) - t;
                if (Math.abs(z) < 1e-3) break;
                x -= z / xDeriv(x);
              }
              return x;
            };
        return function(t) {
          return bezCoOrd(xForT(t), 1);
        }
      };
      $.easing[encodedFuncName] = function(x, t, b, c, d) {
        return c * polyBez([coOrdArray[0], coOrdArray[1]], [coOrdArray[2], coOrdArray[3]])(t/d) + b;
      }
    }
    return encodedFuncName;
  }});
}));

(function($) {
	"use strict";
	$.fn.get_oembed = function(callback, onlycode, done, error) {
		var $this = $(this),
			$getoembed = $this.next('.oembed_code');

		if ($getoembed[0]) {
			var $getcode = $getoembed.html();
			$getoembed.remove();
			var data = {
					action: 'get_oembed',
					onlycode: onlycode,
					urlOembed: escape($getcode),
					dataType: "json",
			};
			$.post(SiteParameters.admin_ajax, data, function(data){
				if (data != '') {
					try {
						var parsedData = JSON.parse(data);
						if (parsedData.code == 'null') {
							$this.html('<b>oEmbed not supported.</b>');
							$this.closest('.uploader-uncode-media').find('.spinner').removeClass('visible');
						} else $this.html(parsedData.code);
						if (callback && onlycode) {
							var mime,
								imgWidth,
								imgHeight;
							mime = parsedData.mime;
							if (parsedData.mime == 'image/url') {
								$(parsedData.code).on('load', function(event) {
									imgWidth = $(event.target)[0].width;
									imgHeight = $(event.target)[0].height;
									callback(mime, imgWidth, imgHeight);
								});
							} else {
								imgWidth = parsedData.width;
								imgHeight = parsedData.height;
								callback(mime, imgWidth, imgHeight);
							}
						}
					} catch (e) {
						$this.closest('.uploader-uncode-media').find('.spinner').removeClass('visible');
					}
				}
			}).done(function(){
				if (typeof done != 'undefined' && $.isFunction(done))
					done();
			}).error(function(){
				if (typeof error != 'undefined' && $.isFunction(error))
					error();
			});
		}
	}
	$(document).on('ready', function() {
		$(document).on('DOMNodeInserted', function(e) {
			try {
				if ($(e.target).closest('.media-modal').length || $(e.target).closest('.wpb_el_type_media_element').length || $(e.target).closest('.attachments').length) {
					if ($(e.target).is('.attachment')) {
						if ($(e.target).find(".oembed").length > 0) $(e.target).find('.oembed:not(.rendered)').get_oembed(null, true);
					}
					if ($(e.target).find('.attachment-media-view').length > 0) {
						if ($(e.target).find(".oembed").length > 0) $(e.target).find('.oembed:not(.rendered)').get_oembed(null, true);
					}
					if ($(e.target).is('.format-settings')) {
						$.each($(".oembed:not(.rendered)", e.target), function(index, val) {
							$(this).get_oembed(null, true);
						});
					}
				}
			} catch (e) {}
		});
	});
	$('.vc_welcome-header').add('.vc_welcome-brand').add('#vc_no-content-add-text-block').remove();
	$('#vc_no-content-helper .vc_ui-help-block a').attr('href','https://support.undsgn.com/hc/en-us');
})(jQuery);

/*
 * EASYDROPDOWN - A Drop-down Builder for Styleable Inputs and Menus
 * Version: 2.1.4
 * License: Creative Commons Attribution 3.0 Unported - CC BY 3.0
 * http://creativecommons.org/licenses/by/3.0/
 * This software may be used freely on commercial and non-commercial projects with attribution to the author/copyright holder.
 * Author: Patrick Kunka
 * Copyright 2013 Patrick Kunka, All Rights Reserved
 */
(function($) {
	function EasyDropDown() {
		this.isField = true,
			this.down = false,
			this.inFocus = false,
			this.disabled = false,
			this.cutOff = false,
			this.hasLabel = false,
			this.keyboardMode = false,
			this.nativeTouch = true,
			this.wrapperClass = 'colors-dropdown',
			this.onChange = null;
	};
	EasyDropDown.prototype = {
		constructor: EasyDropDown,
		instances: {},
		init: function(domNode, settings) {
			var self = this;
			$.extend(self, settings);
			self.$select = $(domNode);
			self.id = domNode.id;
			self.options = [];
			self.$options = self.$select.find('option');
			self.isTouch = ('ontouchstart' in window || navigator.maxTouchPoints);
			self.$select.removeClass(self.wrapperClass + ' dropdown');
			if (self.$select.is(':disabled')) {
				self.disabled = true;
			};
			if (self.$options.length) {
				self.$options.each(function(i) {
					var $option = $(this);
					if ($option.is(':selected')) {
						self.selected = {
							index: i,
							title: $option.text(),
							color: $option.attr('class')
						}
						self.focusIndex = i;
					};
					if ($option.hasClass('label') && i == 0) {
						self.hasLabel = true;
						self.label = $option.text();
						$option.attr('value', '');
					} else {
						self.options.push({
							domNode: $option[0],
							title: $option.text(),
							value: $option.val(),
							color: $option.attr('class'),
							disabled: $option.is(':disabled'),
							selected: $option.is(':selected')
						});
					};
				});
				if (!self.selected) {
					self.selected = {
						index: 0,
						title: self.$options.eq(0).text(),
					}
					self.focusIndex = 0;
				};
				self.render();
			};
		},
		render: function() {
			var self = this,
				touchClass = self.isTouch && self.nativeTouch ? ' touch' : '',
				disabledClass = self.disabled ? ' disabled' : '';

			self.$container = self.$select.wrap('<div class="' + self.wrapperClass + touchClass + disabledClass + '"><span class="old"/></div>').parent().parent();
			self.$active = $('<span class="selected">' + self.selected.title + (self.selected.color != '' ? ' <small>: '+ self.selected.color +' </small>' : '') + '<span class="color style-' + self.selected.color + '-bg"></span></span>').appendTo(self.$container);
			self.$carat = $('<span class="carat"/>').appendTo(self.$container);
			self.$scrollWrapper = $('<div class="dropdown-colors-list"><ul/></div>').appendTo(self.$container);
			self.$dropDown = self.$scrollWrapper.find('ul');
			self.$form = self.$container.closest('form');
			$.each(self.options, function() {
				var option = this,
					disabled = option.disabled ? ' disabled' : '',
					active = option.selected ? ' active' : '',
					liclass = '';
				if (disabled != '' || active != '') liclass = ' class="' + disabled + active + '"';
				self.$dropDown.append('<li' + liclass + '>' + option.title + (option.color != '' ? ' <small>: '+ option.color +' </small>' : '') + '<span class="color style-' + option.color + '-bg"></span></li>');
			});
			self.$items = self.$dropDown.find('li');
			if (self.cutOff && self.$items.length > self.cutOff) self.$container.addClass('scrollable');
			self.getMaxHeight();
			if (self.isTouch && self.nativeTouch) {
				self.bindTouchHandlers();
			} else {
				self.bindHandlers();
			};
		},
		getMaxHeight: function() {
			var self = this;
			self.maxHeight = 0;
			for (i = 0; i < self.$items.length; i++) {
				var $item = self.$items.eq(i);
				self.maxHeight += $item.outerHeight();
				if (self.cutOff == i + 1) {
					break;
				};
			};
		},
		bindTouchHandlers: function() {
			var self = this;
			self.$container.on('click.easyDropDown', function() {
				self.$select.focus();
			});
			self.$select.on({
				change: function() {
					var $selected = $(this).find('option:selected'),
						title = $selected.text(),
						value = $selected.val();
					self.$active.text(title);
					if (typeof self.onChange === 'function') {
						self.onChange.call(self.$select[0], {
							title: title,
							value: value
						});
					};
				},
				focus: function() {
					self.$container.addClass('focus');
				},
				blur: function() {
					self.$container.removeClass('focus');
				}
			});
		},
		bindHandlers: function() {
			var self = this;
			self.query = '';
			self.$container.on({
				'click.easyDropDown': function() {
					if (!self.down && !self.disabled) {
						self.open();
					} else {
						self.close();
					};
				},
				'mousemove.easyDropDown': function() {
					if (self.keyboardMode) {
						self.keyboardMode = false;
					};
				}
			});
			$('body').on('click.easyDropDown.' + self.id, function(e) {
				var $target = $(e.target),
					classNames = self.wrapperClass.split(' ').join('.');
				if (!$target.closest('.' + classNames).length && self.down) {
					self.close();
				};
			});
			self.$items.on({
				'click.easyDropDown': function() {
					var index = $(this).index();
					self.select(index);
					self.$select.focus();
				},
				'mouseover.easyDropDown': function() {
					if (!self.keyboardMode) {
						var $t = $(this);
						$t.addClass('focus').siblings().removeClass('focus');
						self.focusIndex = $t.index();
					};
				},
				'mouseout.easyDropDown': function() {
					if (!self.keyboardMode) {
						$(this).removeClass('focus');
					};
				}
			});
			self.$select.on({
				'focus.easyDropDown': function() {
					self.$container.addClass('focus');
					self.inFocus = true;
				},
				'blur.easyDropDown': function() {
					self.$container.removeClass('focus');
					self.inFocus = false;
				},
				'keydown.easyDropDown': function(e) {
					if (self.inFocus) {
						self.keyboardMode = true;
						var key = e.keyCode;
						if (key == 38 || key == 40 || key == 32) {
							e.preventDefault();
							if (key == 38) {
								self.focusIndex--
									if ($(self.$items[self.focusIndex]).hasClass('disabled')) self.focusIndex--;
								self.focusIndex = self.focusIndex < 0 ? 0 : self.focusIndex;
							} else if (key == 40) {
								self.focusIndex++
									if ($(self.$items[self.focusIndex]).hasClass('disabled')) self.focusIndex++;
								self.focusIndex = self.focusIndex > self.$items.length - 1 ? self.$items.length - 1 : self.focusIndex;
							};
							if (!self.down) {
								self.open();
							};
							self.$items.removeClass('focus').eq(self.focusIndex).addClass('focus');
							if (self.cutOff) {
								self.scrollToView();
							};
							self.query = '';
						};
						if (self.down) {
							if (key == 9 || key == 27) {
								self.close();
							} else if (key == 13) {
								e.preventDefault();
								self.select(self.focusIndex);
								self.close();
								return false;
							} else if (key == 8) {
								e.preventDefault();
								self.query = self.query.slice(0, -1);
								self.search();
								clearTimeout(self.resetQuery);
								return false;
							} else if (key != 38 && key != 40) {
								var letter = String.fromCharCode(key);
								self.query += letter;
								self.search();
								clearTimeout(self.resetQuery);
							};
						};
					};
				},
				'keyup.easyDropDown': function() {
					self.resetQuery = setTimeout(function() {
						self.query = '';
					}, 1200);
				}
			});
			self.$dropDown.on('scroll.easyDropDown', function(e) {
				if (self.$dropDown[0].scrollTop >= self.$dropDown[0].scrollHeight - self.maxHeight) {
					self.$container.addClass('bottom');
				} else {
					self.$container.removeClass('bottom');
				};
			});
			if (self.$form.length) {
				self.$form.on('reset.easyDropDown', function() {
					var active = self.hasLabel ? self.label : self.options[0].title;
					self.$active.text(active);
				});
			};
		},
		unbindHandlers: function() {
			var self = this;
			self.$container.add(self.$select).add(self.$items).add(self.$form).add(self.$dropDown).off('.easyDropDown');
			$('body').off('.' + self.id);
		},
		open: function() {
			var self = this,
				scrollTop = window.scrollY || document.documentElement.scrollTop,
				scrollLeft = window.scrollX || document.documentElement.scrollLeft,
				scrollOffset = self.notInViewport(scrollTop);
			self.closeAll();
			self.getMaxHeight();
			self.$select.focus();
			//window.scrollTo(scrollLeft, scrollTop+scrollOffset);
			self.$container.addClass('open');
			self.$scrollWrapper.css('height', self.maxHeight + 'px');
			self.down = true;
		},
		close: function() {
			var self = this;
			self.$container.removeClass('open');
			self.$scrollWrapper.css('height', '0px');
			self.focusIndex = self.selected.index;
			self.query = '';
			self.down = false;
		},
		closeAll: function() {
			var self = this,
				instances = Object.getPrototypeOf(self).instances;
			for (var key in instances) {
				var instance = instances[key];
				instance.close();
			};
		},
		select: function(index) {
			var self = this;
			if (typeof index === 'string') {
				index = self.$select.find('option[value=' + index + ']').index() - 1;
			};
			var option = self.options[index],
				selectIndex = self.hasLabel ? index + 1 : index;
			self.$items.removeClass('active').eq(index).addClass('active');
			self.$active.html(option.title + (option.color != '' ? ' <small>: '+ option.color +' </small>' : '') + '<span class="color style-' + option.color + '-bg"></span>');
			self.$select.find('option').removeAttr('selected').eq(selectIndex).prop('selected', true).parent().trigger('change');
			self.selected = {
				index: index,
				title: option.title
			};
			self.focusIndex = i;
			if (typeof self.onChange === 'function') {
				self.onChange.call(self.$select[0], {
					title: option.title,
					value: option.value
				});
			};
		},
		search: function() {
			var self = this,
				lock = function(i) {
					self.focusIndex = i;
					self.$items.removeClass('focus').eq(self.focusIndex).addClass('focus');
					self.scrollToView();
				},
				getTitle = function(i) {
					return self.options[i].title.toUpperCase();
				};
			for (i = 0; i < self.options.length; i++) {
				var title = getTitle(i);
				if (title.indexOf(self.query) == 0) {
					lock(i);
					return;
				};
			};
			for (i = 0; i < self.options.length; i++) {
				var title = getTitle(i);
				if (title.indexOf(self.query) > -1) {
					lock(i);
					break;
				};
			};
		},
		scrollToView: function() {
			var self = this;
			if (self.focusIndex >= self.cutOff) {
				var $focusItem = self.$items.eq(self.focusIndex),
					scroll = ($focusItem.outerHeight() * (self.focusIndex + 1)) - self.maxHeight;
				self.$dropDown.scrollTop(scroll);
			};
		},
		notInViewport: function(scrollTop) {
			var self = this,
				range = {
					min: scrollTop,
					max: scrollTop + (window.innerHeight || document.documentElement.clientHeight)
				},
				menuBottom = self.$dropDown.offset().top + self.maxHeight;
			if (menuBottom >= range.min && menuBottom <= range.max) {
				return 0;
			} else {
				return (menuBottom - range.max) + 5;
			};
		},
		destroy: function() {
			var self = this;
			self.unbindHandlers();
			self.$select.unwrap().siblings().remove();
			self.$select.unwrap();
			delete Object.getPrototypeOf(self).instances[self.$select[0].id];
		},
		disable: function() {
			var self = this;
			self.disabled = true;
			self.$container.addClass('disabled');
			self.$select.attr('disabled', true);
			if (!self.down) self.close();
		},
		enable: function() {
			var self = this;
			self.disabled = false;
			self.$container.removeClass('disabled');
			self.$select.attr('disabled', false);
		},

		update: function() {
			var self = this;
			self.$options = self.$select.find('option');
			self.options = [];
			self.$options.each(function() {
				var $option = $(this);
				self.options.push({
					domNode: $option[0],
					title: $option.text(),
					value: $option.val(),
					color: $option.attr('class'),
					disabled: $option.is(':disabled'),
					selected: $option.is(':selected')
				})
			});
			self.$items = self.$dropDown.find('li');
			self.unbindHandlers();
			self.bindHandlers();
		}
	};
	var instantiate = function(domNode, settings) {
			domNode.id = !domNode.id ? 'EasyDropDown' + rand() : domNode.id;
			var instance = new EasyDropDown();
			if (!instance.instances[domNode.id]) {
				instance.instances[domNode.id] = instance;
				instance.init(domNode, settings);
			};
		},
		rand = function() {
			return ('00000' + (Math.random() * 16777216 << 0).toString(16)).substr(-6).toUpperCase();
		};
	$.fn.easyDropDown = function() {
		var args = arguments,
			dataReturn = [],
			eachReturn;
		eachReturn = this.each(function() {
			if (args && typeof args[0] === 'string') {
				var data = EasyDropDown.prototype.instances[this.id][args[0]](args[1], args[2]);
				if (data) dataReturn.push(data);
			} else {
				instantiate(this, args[0]);
			};
		});
		if (dataReturn.length) {
			return dataReturn.length > 1 ? dataReturn : dataReturn[0];
		} else {
			return eachReturn;
		};
	};
	$(function() {
		if (typeof Object.getPrototypeOf !== 'function') {
			if (typeof 'test'.__proto__ === 'object') {
				Object.getPrototypeOf = function(object) {
					return object.__proto__;
				};
			} else {
				Object.getPrototypeOf = function(object) {
					return object.constructor.prototype;
				};
			};
		};
		// $('select.dropdown').each(function() {
		// 	var json = $(this).attr('data-settings');
		// 	settings = json ? $.parseJSON(json) : {};
		// 	instantiate(this, settings);
		// });
	});
	$.fn.uncode_init_upload = function() {
		// Open up the media manager to handle editing image metadata.
		$(document).on('click', '.ot_upload_media', function(e) {
			e.preventDefault();
			var uncode_frames = {}, // Store our workflows in an object
				frame_id = 'uncode-editor', // Unique ID for each workflow
				default_view = wp.media.view.AttachmentsBrowser, // Store the default view to restore it later
				media = wp.media,
				field_id = $(this).parent('.option-tree-ui-upload-parent').find('input').attr('id'),
				post_id = $(this).attr('rel'),
				save_attachment_id = $('#' + field_id).val(),
				btnContent = '';
			// If the media frame already exists, reopen it.
			if (uncode_frames[frame_id]) {
				uncode_frames[frame_id].open();
				return;
			}
			media.view.uploadMediaView = media.View.extend({
				tagName: 'div',
				className: 'uploader-uncode-media',
				template: media.template('uploader-uncode-media'),
				events: {
					'click .close': 'hide',
					'paste #mle-code': 'entercode',
					'input #mle-code': 'entercode'
				},
				oembed_callback: function($mime, $width, $height) {
					var $this = (window['workflow'] != undefined) ? workflow : wp.media.frame,
						$el = $this.$el;
					$button = $el.find('.media-button'),
						$spinner = $el.find('.spinner');
					if ($mime != '') {
						$el.find('#mle-mime').val($mime);
						$el.find('#mle-width').val($width);
						$el.find('#mle-height').val($height);
						$button.removeAttr('disabled');
						$spinner.removeClass('visible');
					}
				},
				entercode: function(event) {
					var _this = this;
					var $el = $(this.$el),
						$codeInput = $el.find('#mle-code'),
						$codeDiv = $el.find('.oembed_code'),
						$oEmbedRender = $el.find('.oembed'),
						$spinner = $el.find('.spinner'),
						$code;
					setTimeout(function() {
						$code = $codeInput.val();
						if ($codeDiv.length == 0) $oEmbedRender.after('<div class="oembed_code">' + $code + '</div>');
						else $codeDiv.html($code);
						$oEmbedRender.get_oembed(_this.oembed_callback, true);
						$spinner.addClass('visible');
					}, 100);
				},
				recordmedia: function() {
					var $this = this,
						$el = $(this.$el),
						$button = uncode_frames[frame_id].$el.find('.media-button-select');
					if (uncode_frames[frame_id].content.get().el.className == 'uploader-uncode-media') {
						$button.attr('disabled', 'disabled');
						$.ajax({
							type: 'POST',
							dataType: "json",
							url: ajaxurl + '?action=recordMedia',
							data: $el.find('input[name],select[name],textarea[name]').serialize(),
							success: function(data) {
								if (!isNaN(data.id) && data != undefined) {
									btnContent += '<div class="option-tree-ui-image-wrap"><div class="oembed"><span class="spinner" style="display: block; float: left;"></span></div><div class="oembed_code" style="display: none;">' + data.url + '</div></div>';
									$('#' + field_id).val(data.id);
									$('#' + field_id + '_media').remove();
									$('#' + field_id).parent().parent('div').append('<div class="option-tree-ui-media-wrap" id="' + field_id + '_media" />');
									$('#' + field_id + '_media').append(btnContent).slideDown();
									$('#' + field_id + '_media .oembed').get_oembed(null, true);
									$('#' + field_id + '_media .spinner').removeClass('visible');
									$button.removeAttr('disabled');
									uncode_frames[frame_id].off('select');
									uncode_frames[frame_id].close();
								}
							}
						});
					} else {
						$this.select();
					}
				},
				ready: function() {
					var $this = this,
						$button = uncode_frames[frame_id].$el.find('.media-button-select');
					$button.off('click').on('click', function() {
						$this.recordmedia();
					});
				},
				select:function () {
					var selection = media.frame.toolbar.get('selection').selection.single();
					media.frame.close();
				}
			});
			// Create the media frame.
			uncode_frames[frame_id] = media({
				title: $(this).attr('title'),
				button: {
					text: option_tree.upload_text
				},
				multiple: false
			});
			uncode_frames[frame_id].on('router:render:browse', function(routerView) {
				routerView.set({
					upload: {
						text: 'Upload Files',
						priority: 20
					},
					browse: {
						text: 'Media Library',
						priority: 40
					},
					uncode: {
						text: 'Upload oEmbed',
						priority: 30
					}
				});
			});
			uncode_frames[frame_id].on('content:render:uncode', function() {
				uncode_frames[frame_id].content.set(new media.view.uploadMediaView({
					controller: this
				}));
			});
			uncode_frames[frame_id].on('close', function() {
				wp.media.view.AttachmentsBrowser = default_view;
			});
			uncode_frames[frame_id].on('select', function() {
				var attachment = uncode_frames[frame_id].state().get('selection').first(),
					href = attachment.attributes.url,
					attachment_id = attachment.attributes.id,
					mime = attachment.attributes.mime,
					regex = /^image\/(?:jpe?g|png|url|gif|x-icon)$/i;
				if (mime == 'oembed/svg') {
					btnContent += '<div class="option-tree-ui-image-wrap">' + attachment.attributes.description + '</div>';
				} else if (mime.match(regex)) {
					btnContent += '<div class="option-tree-ui-image-wrap"><img src="' + href + '" alt="" /></div>';
				} else {
					btnContent += '<div class="option-tree-ui-image-wrap"><div class="oembed"><span class="spinner" style="display: block; float: left;"></span></div><div class="oembed_code" style="display: none;">' + href + '</div></div>';
				}
				btnContent += '<a href="#" class="option-tree-ui-remove-media option-tree-ui-button button button-secondary light" title="' + option_tree.remove_media_text + '"><span class="icon fa fa-minus2"></span>' + option_tree.remove_media_text + '</a>';
				$('#' + field_id).val(attachment_id);
				$('#' + field_id + '_media').remove();
				$('#' + field_id).parent().parent('div').append('<div class="option-tree-ui-media-wrap" id="' + field_id + '_media" />');
				$('#' + field_id + '_media').append(btnContent).slideDown();
				$('#' + field_id + '_media .oembed').get_oembed();
				uncode_frames[frame_id].off('select');
				uncode_frames[frame_id].close();
			});
			uncode_frames[frame_id].on('open',function() {
				var selection = uncode_frames[frame_id].state().get('selection'),
			  attachment = media.attachment(save_attachment_id);
			  attachment.fetch();
			  selection.set( attachment );
			});
			// Finally, open the modal.
			uncode_frames[frame_id].open();
		});
		$(document).on('click', '.option-tree-ui-remove-media', function(e) {
			e.preventDefault();
			var cont = $(e.currentTarget).closest('td'),
				$input = cont.find('.option-tree-ui-upload-parent input'),
				$placeholder = cont.find('.option-tree-ui-media-wrap');
			$input.attr('value','');
			$placeholder.remove();
		});
	};

	if (typeof wp !== 'undefined' && wp.media && wp.media.view.AttachmentFilters) {
		// Extended Filters dropdown with taxonomy term selection values
		var MediaLibraryTaxonomyFilter = wp.media.view.AttachmentFilters.extend({
			className: 'attachment-filters',

			createFilters: function() {
				var filters = {};

				_.each( SiteParameters.media_cats.terms || {}, function( value, index ) {
					filters[ index ] = {
						text: value.name,
						props: {
							'media-category': value.slug,
						}
					};
				});

				filters.all = {
					text:  SiteParameters.media_cats.all_label,
					props: {
						'media-category': ''
					},
					priority: 10
				};

				this.filters = filters;
			}
		});

		/**
		 * Replace the media-toolbar with our own
		 */
		var AttachmentsBrowser = wp.media.view.AttachmentsBrowser;

		wp.media.view.AttachmentsBrowser = wp.media.view.AttachmentsBrowser.extend({
			createToolbar: function() {
				AttachmentsBrowser.prototype.createToolbar.call( this );

				this.toolbar.set( 'MediaLibraryTaxonomyFilter', new MediaLibraryTaxonomyFilter({
					controller: this.controller,
					model:      this.collection.props,
					priority:   -80
					}).render()
				);
			}
		});

		// Add custom class to attachment container
		wp.media.view.Attachment.Library = wp.media.view.Attachment.Library.extend({
	        className: function() {
				if (this.model.get('customClass')) {
					return 'attachment ' + this.model.get('customClass');
				}

				return 'attachment';
	        }
	    });
	}

	/* Save taxonomy */
	$('html').delegate( '.media-terms input', 'change', function(){

		var obj = $(this),
			container = obj.parents('.media-terms'),
			row = container.parent(),
			data = {
				action: 'save-media-terms',
				term_ids: [],
				attachment_id: container.data('id'),
				taxonomy: container.data('taxonomy')
			};

		container.find('input:checked').each(function(){
			data.term_ids.push( $(this).val() );
		});

		row.addClass('media-save-terms');
		container.find('input').prop('disabled', 'disabled');

		$.post( ajaxurl, data, function( response ){
			row.removeClass('media-save-terms');
			container.find('input').removeProp('disabled');
		});

	});

	// Add new taxonomy
	$('html').delegate('.toggle-add-media-term', 'click', function(e){
		e.preventDefault();
		$(this).parent().find('.add-new-term').toggle();
	});

	// Save new taxnomy
	$('html').delegate('.save-media-category', 'click', function(e){

		var obj = $(this),
			termField = obj.parent().find('input'),
			termParent = obj.parent().find('select'),
			data = {
				action: 'add-media-term',
				attachment_id: obj.data('id'),
				taxonomy: obj.data('taxonomy'),
				parent: termParent.val(),
				term: termField.val()
			};

		// No val
		if ( '' == data.term ) {
			termField.focus();
			return;
		}

		$.post(ajaxurl, data, function(response){

			obj.parents('.field').find('.media-terms ul:first').html( response.checkboxes );
			obj.parents('.field').find('select').replaceWith( response.selectbox );

			termField.val('');

		}, 'json' );

	});

	//////////////////////////////////////////////////////
	/// Theme panel + AJAX
	//////////////////////////////////////////////////////

	var theme_options_form = $('#option-tree-settings-api');
	var theme_options_form_data;
	var theme_options_number_of_inputs = 0;

	function count_recursive(data, count) {
		var count = typeof count === 'undefined' ? -1 : count;
		for (var i = 0; i < data.length; i++) {
			if ($.isArray(data[i])) {
				count--;
				count += count_r(data[i], 1);
			} else {
				count++;
			}
		}

		return count;
	}

	function count_theme_options_inputs(data) {
		var count = count_recursive(data);

		return count;
	}

	function calculate_max_input_vars(vars) {
		var vars = typeof vars  === 'undefined' ? 10000 : vars;
		var param = [];
		var var_string;
		var intData;

		for (i = 0; i < vars; i++) {
			param[i] = 'var_'+i;
		}

		$.ajax({
			url: ajaxurl,
			data: {
				action: 'uncode_test_vars',
				test_input_vars_from_theme_options_nonce: SiteParameters.theme_options_input_vars.max_vars_nonce,
				content: param
			},
			type: 'post',
			error: function(data) {
				show_max_input_vars_modal();

				if (SiteParameters.enable_debug == true) {
					// This console log is disabled by default
					// So nothing is printed in a typical installation
					//
					// It can be enabled for debugging purposes setting
					// the 'uncode_enable_debug_on_js_scripts' filter to true
					console.log('Max vars test failed');
				}
			},
			success: function(response) {
				if (response && response.success === false) {
					show_max_input_vars_modal();

					if (SiteParameters.enable_debug == true) {
						// This console log is disabled by default
						// So nothing is printed in a typical installation
						//
						// It can be enabled for debugging purposes setting
						// the 'uncode_enable_debug_on_js_scripts' filter to true
						console.log('Max vars test failed');
					}
				} else if (response && response.success === true) {
					intData = parseInt(response.data.count);

					if (intData < (vars - 1)) {
						if (SiteParameters.enable_debug == true) {
							// This console log is disabled by default
							// So nothing is printed in a typical installation
							//
							// It can be enabled for debugging purposes setting
							// the 'uncode_enable_debug_on_js_scripts' filter to true
							console.log('Real allowed max vars (after calculation): ' + parseInt(intData, 10));
						}

						if (intData < theme_options_number_of_inputs) {
							show_max_input_vars_modal();
						} else {
							save_theme_options();
						}
					} else {
						calculate_max_input_vars(vars + 10000);
					}

					// Save new calculated vars
					$.ajax({
						url: ajaxurl,
						type: 'post',
						data: {
							action: 'uncode_update_max_input_vars',
							calculated_vars: intData,
							update_input_vars_from_theme_options_nonce: SiteParameters.theme_options_input_vars.max_vars_nonce,
							theme_options_number_of_inputs: theme_options_number_of_inputs
						}
					}).done(function(response) {
						if (SiteParameters.enable_debug == true && response && response.success === false) {
							// This console log is disabled by default
							// So nothing is printed in a typical installation
							//
							// It can be enabled for debugging purposes setting
							// the 'uncode_enable_debug_on_js_scripts' filter to true
							console.log('Max vars update failed');
						}
					}).fail(function() {
						if (SiteParameters.enable_debug == true) {
							// This console log is disabled by default
							// So nothing is printed in a typical installation
							//
							// It can be enabled for debugging purposes setting
							// the 'uncode_enable_debug_on_js_scripts' filter to true
							console.log('Max vars update failed');
						}
					});
				} else {
					show_max_input_vars_modal();

					// Unknow error, show the modal
					if (SiteParameters.enable_debug == true) {
						// This console log is disabled by default
						// So nothing is printed in a typical installation
						//
						// It can be enabled for debugging purposes setting
						// the 'uncode_enable_debug_on_js_scripts' filter to true
						console.log('Unknown error during max vars text');
					}
				}
			}
		});
	}

	function show_max_input_vars_modal() {
		var default_recommended_vars = parseInt(SiteParameters.theme_options_input_vars.recommended_max_input_vars, 10);
		var theme_options_suggested_vars = theme_options_number_of_inputs < default_recommended_vars ? default_recommended_vars : Math.ceil(theme_options_number_of_inputs / 1000) * 1000;
		var modal_content = SiteParameters.theme_options_input_vars.locale.content.replace('dddd', theme_options_suggested_vars);

		modal_content += '<label class="uncode-dismiss-max-vars-modal-checkbox"><input id="uncode-dismiss-max-vars-modal-checkbox" type="checkbox" name="uncode-dismiss-max-vars-modal">' + SiteParameters.theme_options_input_vars.locale.button_cancel + '</label>';

		$("<div />").html(modal_content).dialog({
			autoOpen: true,
			modal: true,
			dialogClass: 'uncode-modal uncode-modal-max-vars',
			title: SiteParameters.theme_options_input_vars.locale.title,
			minHeight: 500,
			minWidth: 500,
			buttons: [{
				text: SiteParameters.theme_options_input_vars.locale.button_confirm,
				class: 'uncode-save-max-vars-modal',
				click: function () {
					var dismiss_checkbox = $('#uncode-dismiss-max-vars-modal-checkbox');

					if (dismiss_checkbox.prop('checked')) {
						set_dismiss_max_vars_modal_cookie();
					}

					save_theme_options();
					$(this).dialog("close");
				}
			}],
		});
	}

	function set_dismiss_max_vars_modal_cookie() {
		document.cookie = "uncode-dismiss-max-vars-modal=1";
	}

	function save_number_of_theme_options_inputs() {
		// Save number of inputs
		// This function fires only when there are no problems
		// with the declared vars. ie. when the declared max
		// inputs vars is bigger than the number of inputs.
		//
		// We need to update this value in the db in any case
		// because that value is used by uncode_get_recommended_max_input_vars() (PHP)
		// to update the system status
		//
		// When there are problmes instead (ie. when we show the modal)
		// we update this value in the 'uncode_update_max_input_vars' directly.
		$.ajax({
			url: ajaxurl,
			type: 'post',
			data: {
				action: 'uncode_update_theme_options_number_of_inputs',
				update_theme_options_number_of_inputs_nonce: SiteParameters.theme_options_input_vars.number_of_inputs_nonce,
				theme_options_number_of_inputs: theme_options_number_of_inputs
			}
		}).done(function(response) {
			if (SiteParameters.enable_debug == true && response && response.success === false) {
				// This console log is disabled by default
				// So nothing is printed in a typical installation
				//
				// It can be enabled for debugging purposes setting
				// the 'uncode_enable_debug_on_js_scripts' filter to true
				console.log('Theme Options inputs update failed');
			}
		}).fail(function() {
			if (SiteParameters.enable_debug == true) {
				// This console log is disabled by default
				// So nothing is printed in a typical installation
				//
				// It can be enabled for debugging purposes setting
				// the 'uncode_enable_debug_on_js_scripts' filter to true
				console.log('Theme Options inputs update failed');
			}
		});
	}

	$('.option-tree-save-button').on('click', function(e) {
		e.preventDefault();

		theme_options_form_data = theme_options_form.serializeArray();
		theme_options_number_of_inputs = count_theme_options_inputs(theme_options_form_data);

		if (SiteParameters.enable_debug == true) {
			// This console log is disabled by default
			// So nothing is printed in a typical installation
			//
			// It can be enabled for debugging purposes setting
			// the 'uncode_enable_debug_on_js_scripts' filter to true
			console.log('Form inputs count: ' + theme_options_number_of_inputs);
			console.log('Declared allowed max vars: ' + parseInt(SiteParameters.theme_options_input_vars.max_input_vars, 10));
			console.log('Dismiss cookie: ' + (document.cookie.indexOf('uncode-dismiss-max-vars-modal=') === -1 ? 'off' : 'on'));
		}

		if (document.cookie.indexOf('uncode-dismiss-max-vars-modal=') === -1 && SiteParameters.theme_options_input_vars.enable_max_input_vars_popup && parseInt(SiteParameters.theme_options_input_vars.max_input_vars, 10) < theme_options_number_of_inputs) {
			calculate_max_input_vars();
		} else {
			save_number_of_theme_options_inputs();
			save_theme_options();
		}
	});

	// Save theme options panel via AJAX
	function save_theme_options() {
		if (!theme_options_form.hasClass('ajax-enabled')) {
			theme_options_form.submit();
		}

		var _this = theme_options_form.removeClass('uncode-ajax-error').removeClass('uncode-ajax-saved');
		var formData = _this.serialize();
		var $spinner = $('#option-tree-sub-header button.option-tree-save-button .uncode-ot-spinner'),
			timeOut;

		// Save tinyMCE textareas
		tinyMCE.triggerSave();

		clearTimeout(timeOut);

		// Remove message container
		$('#theme-options-ajax-message .theme-options-ajax-message').remove();

		// Add loading class
		_this.addClass('uncode-ajax-loading');

		// Post the data
		$.post('options.php', formData).fail(function(xhr, status, error) {

			// Show error message
			var message = '<p class="theme-options-ajax-message__text">' + SiteParameters.ajax_save_message.error + '</p>';

			if (xhr.status) {
				message += '<p class="theme-options-ajax-message__error"><span class="theme-options-ajax-message__code">[' + xhr.status + ']</span> ' + SiteParameters.http_errors[xhr.status] + '</p>';

				if (!message) {
					message += '<p class="theme-options-ajax-message__error">' + SiteParameters.http_errors.unknown + '</p>';
				}
			}

			_this.removeClass('uncode-ajax-loading').addClass('uncode-ajax-error').addClass('uncode-ajax-error-text');

			$('#theme-options-ajax-message').append('<span class="theme-options-ajax-message">' + message + '</span>').fadeIn(250);

			timeOut = setTimeout(function(){
				_this.removeClass('uncode-ajax-error');
				$('#theme-options-ajax-message').fadeOut(500, function(){
					_this.removeClass('uncode-ajax-error-text');
				});
			}, 3000);

			if (SiteParameters.enable_debug == true) {
				// This console log is disabled by default
				// So nothing is printed in a typical installation
				//
				// It can be enabled for debugging purposes setting
				// the 'uncode_enable_debug_on_js_scripts' filter to true
				console.log(xhr);
			}
		}).done(function(res) {

			if ( res.indexOf('<body id="error-page">') !== -1 ) {

				_this.removeClass('uncode-ajax-loading').addClass('uncode-ajax-error').addClass('uncode-ajax-error-text');

				var message = '<p class="theme-options-ajax-message__text">' + SiteParameters.ajax_save_message.error;
				message += '<span class="theme-options-ajax-message__error"> ' + SiteParameters.http_errors.unknown + '</span></p>';
				$('#theme-options-ajax-message').append('<span class="theme-options-ajax-message">' + message + '</span>').fadeIn(250);

			} else if ( res.indexOf('<body class="login ') !== -1 ) {

				_this.removeClass('uncode-ajax-loading').addClass('uncode-ajax-error').addClass('uncode-ajax-error-text');

				var message = '<p class="theme-options-ajax-message__text">' + SiteParameters.ajax_save_message.error;
				message += '<span class="theme-options-ajax-message__error"> ' + SiteParameters.http_errors.login + '</span></p>';
				$('#theme-options-ajax-message').append('<span class="theme-options-ajax-message">' + message + '</span>').fadeIn(250);

			} else {

				reloadDynamicData();

				_this.removeClass('uncode-ajax-loading').addClass('uncode-ajax-saved').addClass('uncode-ajax-saved-text');

				var message = '<p class="theme-options-ajax-message__text">' + SiteParameters.ajax_save_message.success + '</p>';
				$('#theme-options-ajax-message').append('<span class="theme-options-ajax-message">' + message + '</span>').fadeIn(250);

			}

			timeOut = setTimeout(function(){
				_this.removeClass('uncode-ajax-saved').removeClass('uncode-ajax-error');
				$('#theme-options-ajax-message').fadeOut(500, function(){
					_this.removeClass('uncode-ajax-saved-text').removeClass('uncode-ajax-error-text');
				});
			}, 3000);

		});

		return false;
	}

	// After the data is saved via AJAX, append the new values to the dropdowns.
	// And reload the dynamic admin-custom.css file
	function reloadDynamicData() {
		var hasPaletteChanged = hasDynamicListChanged('_uncode_custom_colors_list');
		var hasFontFamilyChanged = hasDynamicListChanged('_uncode_font_groups');
		var hasFontSizeChanged = hasDynamicListChanged('_uncode_heading_font_sizes');
		var hasLineHeightChanged = hasDynamicListChanged('_uncode_heading_font_heights');
		var hasLetterSpacingChanged = hasDynamicListChanged('_uncode_heading_font_spacings');

		if (hasPaletteChanged) {
			refreshColorPalettes();
		}

		if (hasFontFamilyChanged) {
			refreshFontFamily();
		}

		if (hasFontSizeChanged) {
			refreshFontSize();
		}

		if (hasLineHeightChanged) {
			refreshLineHeight();
		}

		if (hasLetterSpacingChanged) {
			refreshLetterSpacing();
		}

		if (hasPaletteChanged) {
			refreshCSS();
		}
	}

	// When a dynamic list is modified (new item, new order, etc), a new 'changed' class
	// is added to the elelemt (see update_ids() and init_edit() in ot-admin.js).
	// This function checks the existence of that class. This function returns true also
	// when init_edit() is called (ie. when the user clicks on the pencil icon). I know,
	// the click on that button doesn't necessarily mean that something was modified.
	function hasDynamicListChanged(list) {
		var el = $('ul[data-name="' + list + '"]');

		if (!el.length) {
			return false;
		}

		return (el.hasClass('changed') ? true : false);
	}

	// Get the saved palette and reload the color dropdowns
	function refreshColorPalettes() {
		$.ajax({
			url: ajaxurl,
			data: {
				action: 'uncode_get_colors'
			},
			success: function(response) {
				var dropdowns = $('.uncode-color-select');
				var optionsString = '';
				var listString = '';

				$.each(response, function(i, val) {
					optionsString += '<option class="' + val.value + '" value="' + val.value + '">' + val.label + '</option>';
					listString += '<li>' + val.label + ' <small>:' + val.value + ' </small><span class="color style-' + val.value + '-bg"></span></li>';
				});

				dropdowns.each(function() {
					var _this = $(this);
					var selected = _this.val();

					// Update select
					_this.find('option:gt(0)').remove();
					_this.append(optionsString);

					// Add selected option to select
					if (selected) {
						_this.find('option[value="' + selected + '"]').attr('selected',true);
					}

					// Update easyDropdown
					if (window.navigator.userAgent.indexOf("Windows NT 10.0") == -1) {
						var colorList = _this.closest('.colors-dropdown').find('.dropdown-colors-list ul');
						colorList.find('li:gt(0)').remove();
						colorList.append(listString);
						_this.easyDropDown('update');
					}
				});
			}
		});
	}

	// Get the saved fonts and reload the font family dropdowns
	function refreshFontFamily() {
		$.ajax({
			url: ajaxurl,
			data: {
				action: 'uncode_get_font_family'
			},
			success: function(response) {
				var dropdowns = $('.uncode_font_family_dropdown');
				var optionsString = '';

				$.each(response, function(i, val) {
					optionsString += '<option value="' + val.value + '">' + val.label + '</option>';
				});

				dropdowns.each(function() {
					var _this = $(this);
					var selected = _this.val();

					// Update select
					_this.find('option').remove();
					_this.append(optionsString);

					// Add selected option to select
					if (selected) {
						_this.find('option[value="' + selected + '"]').attr('selected',true);
					}
				});
			}
		});
	}

	// Get the saved font sizes and reload the font size dropdowns
	function refreshFontSize() {
		$.ajax({
			url: ajaxurl,
			data: {
				action: 'uncode_get_font_size'
			},
			success: function(response) {
				var dropdowns = $('.uncode_font_size_dropdown');
				var optionsString = '';

				$.each(response, function(i, val) {
					optionsString += '<option value="' + val.value + '">' + val.label + '</option>';
				});

				dropdowns.each(function() {
					var _this = $(this);
					var selected = _this.val();

					// Update select
					_this.find('option').remove();
					_this.append(optionsString);

					// Add selected option to select
					if (selected) {
						_this.find('option[value="' + selected + '"]').attr('selected',true);
					}
				});
			}
		});
	}

	// Get the saved line heights and reload the line height dropdowns
	function refreshLineHeight() {
		$.ajax({
			url: ajaxurl,
			data: {
				action: 'uncode_get_line_height'
			},
			success: function(response) {
				var dropdowns = $('.uncode_line_height_dropdown');
				var optionsString = '';

				$.each(response, function(i, val) {
					optionsString += '<option value="' + val.value + '">' + val.label + '</option>';
				});

				dropdowns.each(function() {
					var _this = $(this);
					var selected = _this.val();

					// Update select
					_this.find('option').remove();
					_this.append(optionsString);

					// Add selected option to select
					if (selected) {
						_this.find('option[value="' + selected + '"]').attr('selected',true);
					}
				});
			}
		});
	}

	// Get the saved letter spacing and reload the letter spacing dropdowns
	function refreshLetterSpacing() {
		$.ajax({
			url: ajaxurl,
			data: {
				action: 'uncode_get_letter_spacing'
			},
			success: function(response) {
				var dropdowns = $('.uncode_letter_spacing_dropdown');
				var optionsString = '';

				$.each(response, function(i, val) {
					optionsString += '<option value="' + val.value + '">' + val.label + '</option>';
				});

				dropdowns.each(function() {
					var _this = $(this);
					var selected = _this.val();

					// Update select
					_this.find('option').remove();
					_this.append(optionsString);

					// Add selected option to select
					if (selected) {
						_this.find('option[value="' + selected + '"]').attr('selected',true);
					}
				});
			}
		});
	}

	// Change the href attribute of admin-custom.css. This will force the reload
	function refreshCSS() {
		var cssURL = $('#uncode-custom-style-css').attr('href');
		cssURL = updateQueryStringParameter(cssURL, 'id', Math.random(0,10000));
		$('#uncode-custom-style-css').attr('href', cssURL);
	}

	// Utlity function that adds or updates a query string parameter
	function updateQueryStringParameter(uri, key, value) {
		var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
		var separator = uri.indexOf('?') !== -1 ? "&" : "?";

		if (uri.match(re)) {
			return uri.replace(re, '$1' + key + "=" + value + '$2');
		} else {
			return uri + separator + key + "=" + value;
		}
	}

	//////////////////////////////////////////////////////
	/// Theme registration
	//////////////////////////////////////////////////////

	var themeRegistrationForm = $('#uncode-registration-form');
	var themeRegistrationTermsCheckbox = document.getElementById('uncode-registration-accept-terms');
	var themeRegistrationButton = document.getElementById('envato_update_info');

	if (themeRegistrationTermsCheckbox !== null && themeRegistrationTermsCheckbox.checked) {
		themeRegistrationButton.disabled = false;
	}

	if ( themeRegistrationTermsCheckbox !== null ) {
		themeRegistrationTermsCheckbox.addEventListener('change', function() {
			if (themeRegistrationTermsCheckbox.checked) {
				themeRegistrationButton.disabled = false;
			} else {
				themeRegistrationButton.disabled = true;
			}
		});
	}

	// Save purchase code via AJAX
	$(themeRegistrationButton).on('click', function(e) {
		e.preventDefault();

		var _this = $(this);
		var purchaseCode = themeRegistrationForm.find('#envato-purchase-code');
		var noticesContainer = themeRegistrationForm.find('#uncode-admin-registration-notice-container');

		// Return early if it as alreading doing AJAX request
		if ($(themeRegistrationButton).hasClass('uncode-ajax-loading')) {
			return false;
		}

		// Remove classes
		$(themeRegistrationButton).removeClass('uncode-ajax-saved uncode-ajax-error');

		// Hide notices
		themeRegistrationForm.find('.uncode-ui-notice').remove();

		// Check if we have a purchase code
		if (!purchaseCode.val()) {
			noticesContainer.append('<p class="uncode-ui-notice uncode-ui-notice--error">' + SiteParameters.theme_registration.locale.empty_purchase_code + '</p>');

			return false;
		}

		// Validate terms
		if (!themeRegistrationTermsCheckbox.checked) {
			noticesContainer.append('<p class="uncode-ui-notice uncode-ui-notice--error">' + SiteParameters.theme_registration.locale.empty_terms + '</p>');

			return false;
		}

		// Add loading class to the button
		$(themeRegistrationButton).addClass('uncode-ajax-loading');

		var post_data = {
			uncode_theme_registration_purchase_code: purchaseCode.val(),
			action: 'save_purchase_code',
			uncode_theme_registration_nonce: SiteParameters.theme_registration.nonce
		};

		$.post(ajaxurl, post_data, function (response) {
			if (response) {
				if (response.success === false) {
					noticesContainer.append('<p class="uncode-ui-notice uncode-ui-notice--error">' + response.data.message + '</p>');
				} else {
					noticesContainer.append('<p class="uncode-ui-notice uncode-ui-notice--success">' + response.data.message + '</p>');

					// Show deregister form and hide the other fields
					setTimeout(function () {
						noticesContainer.hide();
						$('.deregister-product-fields-container').show();
						themeRegistrationForm.find('.register-product-fields-container').hide();
						$('.registration-error-message').hide();
						$('.registration-ok-message').show();
					}, 3500);
				}
			}
		}).done(function () {
			// Add success class to the button
			$(themeRegistrationButton).addClass('uncode-ajax-saved');

		}).fail(function () {
			// Add error class to the button
			$(themeRegistrationButton).addClass('uncode-ajax-error');
		}).always(function () {
			// Remove loading class
			$(themeRegistrationButton).removeClass('uncode-ajax-loading');

			setTimeout(function () {
				$(themeRegistrationButton).removeClass('uncode-ajax-saved uncode-ajax-error');
			}, 3000);
		});
	});
})(jQuery);


(function($){

"use strict";

var UNCODE = window.UNCODE || {};

UNCODE.dismissAdminNotices = function(){

	$('#uncode_vc_admin_notice').on( 'click', '.notice-dismiss', function() {

		$.ajax({
			url: ajaxurl,
			data: {
				action: 'uncode_vc_admin_notice_dismiss'
			}
		});

	});

};

UNCODE.messagePosition = function(){

	var $form = $('#option-tree-settings-api'),
		$wrap = $('#theme-options-ajax-message', $form),
		$message = $('#uncode-ot-message');

	if ( !$form.length || !$wrap.length || !$message.length )
		return;


	$form.addClass('uncode-ajax-saved-text');

	var message = '<p class="theme-options-ajax-message__text">' + $message.text() + '</p>';
	$wrap.append('<span class="theme-options-ajax-message">' + message + '</span>');

	$message.remove();

	setTimeout(function(){
		$wrap.fadeIn(500);
	}, 1500);

	setTimeout(function(){
		$('#theme-options-ajax-message').fadeOut(500, function(){
			$form.removeClass('uncode-ajax-error-text');
		});
	}, 7000);

};

UNCODE.managePageOptions = function(){
	$( ".ot-numeric-slider-wrap" ).has('#_uncode_product_media_size').each(function(){
		var $input = $('.ot-numeric-slider-helper-input', this),
			$slide = $( ".ot-numeric-slider", this);

		if ( $input.val() == 0 )
			$input.val('Inherit');

		$slide.on( "slide slidechange", function( event, ui ) {
			if (ui.value == 0)
				$input.val('Inherit');
		} );
	});
};

UNCODE.toggleDescription = function(){
	var $sections = $('.format-settings, .vc_shortcode-param, .loop_params_holder .vc_row, .system-status-list tr'),
		$descriptions = $('small.description, .vc_description').hide();
	$('.format-settings.desc-active, .vc_shortcode-param.desc-active, .loop_params_holder .vc_row, .system-status-list tr').removeClass('desc-active');

	$sections.each(function(){
		var $section = $(this),
			$toggle = $('.toggle-description', $section),
			$description = $('small.description, .vc_description', $section).eq(0);

		$toggle.off('click')
		.on('click', function(){
			$('.format-settings.desc-active, .vc_shortcode-param.desc-active, .loop_params_holder .vc_row, .system-status-list tr').not($section).removeClass('desc-active');
			$section.toggleClass('desc-active');
			$descriptions.not($description).slideUp(350, $.bez([0.685, 0.595, 0.020, 0.720]));

			var opacityToggle = $section.hasClass('desc-active') ? 1 : 0;

			$description.slideToggle(350, $.bez([0.685, 0.595, 0.020, 0.720])).animate(
			{ opacity: opacityToggle },
			{ queue: false, duration: 350}, $.bez([0.685, 0.595, 0.020, 0.720])
			);

		});

	});

	var $holders = $('.loop_params_holder, .vc_param_group-list');
	$holders.each(function(){
		var $holder = $(this),
			$holdParent = $holder.closest('.vc_shortcode-param');
		$holder.hover(function(){
			$holdParent.addClass('no-propagate');
		}, function(){
			$holdParent.removeClass('no-propagate');
		});
	});

};

UNCODE.toggleIconDetector = function(){

	var runToggleIconDetector = function(){
		var $selectors = $('.vc-icons-selector');

		$selectors.each(function(){
			var $sel = $(this),
				$selector = $('.selector', $sel),
				$button = $('.selector-button', $sel),
				$popup = $('.selector-popup', $sel),
				avoidMult;
			$button.on('click', function(){
				clearTimeout(avoidMult);
				avoidMult = setTimeout(function(){
					if ( $popup.is(':visible') ) {
						$sel.addClass('open');
					} else {
						$sel.removeClass('open');
					}
				}, 450);
			});
		});
	};

	var $picker = $('.vc-iconpicker').on('change', runToggleIconDetector);
};

UNCODE.stickyButton = function(){

	var $form = $('#option-tree-settings-api'),
		$header = $('#option-tree-sub-header', $form),
		$bodyContent = $('#post-body-content', $form),
		wrapTop = document.getElementById('wpwrap').offsetTop,
		client, off, offTop, offLeft, offW;

	if ( ! $form.length || ! $header.length || ! $bodyContent.length )
		return;

	var stickyButtonRun = function(){
		client = $bodyContent[0].getClientRects();
		off = $bodyContent.offset();
		offTop = client[0].top;
		offLeft = off.left;
		offW = client[0].width;

		if ( wrapTop >= offTop ) {
			$header.addClass('uncode-sticky');
			$header[0].style.position = 'fixed';
			$header[0].style.top = wrapTop + 'px';
			$header[0].style.left = offLeft + 'px';
			$header[0].style.width = offW + 'px';
		} else {
			$header.removeClass('uncode-sticky');
			$header[0].style.position = 'absolute';
			$header[0].style.top = 0;
			$header[0].style.left = $('body').hasClass('rtl')? '0px' : '300px';
			$header[0].style.width = 'calc(100% - 300px)';
		}
	};

    stickyButtonRun();
    function onScroll(evt) {
        requestAnimFrame(stickyButtonRun);
    }

    window.requestAnimFrame = (function() {
        return window.requestAnimationFrame ||
            window.webkitRequestAnimationFrame ||
            window.mozRequestAnimationFrame ||
            window.oRequestAnimationFrame ||
            window.msRequestAnimationFrame ||
            function(callback) {
                window.setTimeout(callback, 1000 / 60);
            };
    })();

    window.addEventListener('scroll', onScroll, false);
    window.addEventListener('resize', onScroll, false);
    onScroll();

};

UNCODE.initTabs = function(){
	$(".wrap.settings-wrap .ui-tabs").tabs({
		fx: {
			opacity: "toggle",
			duration: "fast"
		}
	});
	$(".wrap.settings-wrap .ui-tabs a.ui-tabs-anchor").on("click", function(event, ui) {
		var obj = "input[name='_wp_http_referer']";
		if ( $(obj).length > 0 ) {
			var url = $(obj).val(),
					hash = $(this).attr('href');
			if ( url.indexOf("#") != -1 ) {
				var o = url.split("#")[1],
						n = hash.split("#")[1];
				url = url.replace(o, n);
			} else {
				url = url + hash;
			}
			$(obj).val(url);
		}
	});
	$('.page-options-header-section').on("click", function(event, ui) {
		$('li[aria-controls="setting__uncode_header_tab"] a').trigger('click');
	});
};

UNCODE.rowDividers = function(){
	$('body').on('change', '.uncode_radio_image', function(){
		var $parent = $(this).parents('.uncode-radio-image').eq(0),
			$input = $parent.find('input[type="hidden"]'),
			$label = $(this).parents('label').eq(0);
		$input.val($(this).val());

		if ( $(this).is(':checked') ) {
			$('label', $parent).not($label).removeClass('checked');
			$label.addClass('checked');
		}
	});
}

UNCODE.numericTextField = function(){
	var set;
	$('body').on('input', '.uncode_numeric_textfield', function(){
		var $this = $(this),
			val = $this.val();

		val = val.replace(/\D/g,'');
		$this.val(val);
	});
}

$(function(){
	UNCODE.dismissAdminNotices();
	UNCODE.messagePosition();
	UNCODE.managePageOptions();
	UNCODE.toggleDescription();
	UNCODE.stickyButton();
	UNCODE.initTabs();
	UNCODE.rowDividers();
	UNCODE.numericTextField();
	$( '#vc_ui-panel-edit-element' ).on( 'vcPanel.shown', function() {
		UNCODE.toggleDescription();
		UNCODE.toggleIconDetector();
		$( document ).on( 'vc.display.template vc.display.lists', function(e) {
			UNCODE.toggleDescription();
		});
	});
});

})(jQuery);
