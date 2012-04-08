/*
 * jQuery Pines Notify (pnotify) Plugin 1.0
 *
 * Copyright (c) 2009 Hunter Perrin
 *
 * Licensed (along with all of Pines) under the GNU Affero GPL:
 *	  http://www.gnu.org/licenses/agpl.html
 */

(function($) {
	var first_top, first_right, history_handle_top, timer;
	var body;
	var jwindow;
	$.extend({
		pnotify_remove_all: function () {
			var body_data = body.data("pnotify");
			/* POA: Added null-check */
			if (body_data && body_data.length) {
				$.each(body_data, function(){
					if (this.pnotify_remove)
						this.pnotify_remove();
				});
			}
		},
		pnotify_position_all: function () {
			if (timer)
				clearTimeout(timer);
			timer = null;
			var nexttop = first_top;
			var nextright = first_right;
			var addwidth = 0;
			var body_data = body.data("pnotify");
			if (!body_data || !body_data.length)
				return;
			$.each(body_data, function(){
				if (this.css("display") != "none") {
					var postop, posright;
					var animate = {};
					// Calculate the top value, disregarding the scroll, since position=fixed.
					postop = this.offset().top - jwindow.scrollTop();
					// Remember the topmost position, so the first visible notice goes there.
					if (typeof first_top == "undefined") {
						first_top = postop;
						nexttop = first_top;
					}
					if (typeof window.innerHeight != "undefined") {
						posright = parseInt(this.css("right"));
						if (isNaN(posright))
							posright = 18;
						// Remember the rightmost position, so the first visible notice goes there.
						if (typeof first_right == "undefined") {
							first_right = posright;
							nextright = first_right;
						}
						// Check that we're not below the bottom of the page.
						if (nexttop + this.height() > window.innerHeight) {
							// If we are, we need to go back to the top, and over to the left.
							nexttop = first_top;
							nextright += addwidth + 10;
							addwidth = 0;
						}
						// Animate if we're moving to the right.
						if (nextright < posright) {
							animate.right = nextright+"px";
						} else {
							this.css("right", nextright+"px");
						}
						// Keep track of the widest notice in the column, so we can push the next column.
						if (this.outerWidth(true) > addwidth)
							addwidth = this.width();
					}
					if (nexttop) {
						// Animate if we're moving up or to the right.
						if (postop > nexttop || animate.right) {
							animate.top = nexttop+"px";
						} else {
							this.css("top", nexttop+"px");
						}
					}
					if (animate.top || animate.right)
						this.animate(animate, {duration: 500, queue: false});
					nexttop += this.height() + 10;
				}
			});
		},
		pnotify: function(options) {
			if (!body)
				body = $("body");
			if (!jwindow)
				jwindow = $(window);

			var animating;
			
			// Build main options.
			var opts;
			if (typeof options == "string") {
				opts = $.extend({}, $.pnotify.defaults);
				opts.pnotify_text = options;
			} else {
				opts = $.extend({}, $.pnotify.defaults, options);
			}

			if (opts.pnotify_before_init) {
				if (opts.pnotify_before_init(opts) === false)
					return null;
			}

			// Create our widget.
			// Stop animation, reset the removal timer, and show the close
			// button when the user mouses over.
			var pnotify = $("<div />", {
				"class": "ui-widget ui-helper-clearfix ui-pnotify "+opts.pnotify_addclass,
				"css": {"display": "none"},
				"mouseenter": function(){
					// If it's animating out, animate back in really quick.
					if (animating == "out") {
						pnotify.stop(true);
						pnotify.css("height", "auto").animate({"width": opts.pnotify_width, "opacity": opts.pnotify_opacity}, "fast");
					}
					if (opts.pnotify_hide) pnotify.pnotify_cancel_remove();
					if (opts.pnotify_closer) pnotify.closer.show();
				},
				"mouseleave": function(){
					if (opts.pnotify_hide) pnotify.pnotify_queue_remove();
					pnotify.closer.hide();
					$.pnotify_position_all();
				}
			});
			pnotify.opts = opts;
			// Create a container for the notice contents.
			pnotify.container = $("<div />", {"class": "ui-corner-all ui-pnotify-container "+(opts.pnotify_type == "error" ? "ui-state-error" : "ui-state-highlight")})
			.appendTo(pnotify);

			pnotify.pnotify_version = "1.0.0";

			// This function is for updating the notice.
			pnotify.pnotify = function(options) {
				// Update the notice.
				var old_opts = opts;
				if (typeof options == "string") {
					opts.pnotify_text = options;
				} else {
					opts = $.extend({}, opts, options);
				}
				pnotify.opts = opts;
				// Update the additional classes.
				if (opts.pnotify_addclass === false) {
					pnotify.removeClass(old_opts.pnotify_addclass);
				} else if (opts.pnotify_addclass !== old_opts.pnotify_addclass) {
					pnotify.removeClass(old_opts.pnotify_addclass).addClass(opts.pnotify_addclass);
				}
				// Update the title.
				if (opts.pnotify_title === false) {
					pnotify.title_container.hide("fast");
				} else if (opts.pnotify_title !== old_opts.pnotify_title) {
					pnotify.title_container.html(opts.pnotify_title).show("fast");
				}
				// Update the text.
				if (opts.pnotify_text === false) {
					pnotify.text_container.hide("fast");
				} else if (opts.pnotify_text !== old_opts.pnotify_text) {
					if (opts.pnotify_insert_brs)
						opts.pnotify_text = opts.pnotify_text.replace("\n", "<br />");
					pnotify.text_container.html(opts.pnotify_text).show("fast");
				}
				pnotify.pnotify_history = opts.pnotify_history;
				// Change the notice type.
				if (opts.pnotify_type != old_opts.pnotify_type)
					pnotify.container.toggleClass("ui-state-error ui-state-highlight");
				if ((opts.pnotify_notice_icon != old_opts.pnotify_notice_icon && opts.pnotify_type == "notice") ||
					(opts.pnotify_error_icon != old_opts.pnotify_error_icon && opts.pnotify_type == "error") ||
					(opts.pnotify_type != old_opts.pnotify_type)) {
					// Remove any old icon.
					pnotify.container.find("div.ui-pnotify-icon").remove();
					if ((opts.pnotify_error_icon && opts.pnotify_type == "error") || (opts.pnotify_notice_icon)) {
						// Build the new icon.
						$("<div />", {"class": "ui-pnotify-icon"})
						.append($("<span />", {"class": opts.pnotify_type == "error" ? opts.pnotify_error_icon : opts.pnotify_notice_icon}))
						.prependTo(pnotify.container);
					}
				}
				// Update the width.
				if (opts.pnotify_width !== old_opts.pnotify_width)
					pnotify.animate({width: opts.pnotify_width});
				// Update the minimum height.
				if (opts.pnotify_min_height !== old_opts.pnotify_min_height)
					pnotify.container.animate({minHeight: opts.pnotify_min_height});
				// Update the opacity.
				if (opts.pnotify_opacity !== old_opts.pnotify_opacity)
					pnotify.fadeTo(opts.pnotify_animate_speed, opts.pnotify_opacity);
				if (!opts.pnotify_hide) {
					pnotify.pnotify_cancel_remove();
				} else if (!old_opts.pnotify_hide) {
					pnotify.pnotify_queue_remove();
				}
				pnotify.pnotify_queue_position();
				return pnotify;
			};

			// Queue the position function so it doesn't run repeatedly and use
			// up resources.
			pnotify.pnotify_queue_position = function() {
				if (timer)
					clearTimeout(timer);
				timer = setTimeout($.pnotify_position_all, 10);
			};

			// Display the notice.
			pnotify.pnotify_display = function() {
				// If the notice is not in the DOM, append it.
				if (!pnotify.parent().length)
					pnotify.appendTo(body);
				// Run callback.
				if (opts.pnotify_before_open) {
					if (opts.pnotify_before_open(pnotify) === false)
						return;
				}
				pnotify.pnotify_queue_position();
				// First show it, then set its opacity, then hide it.
				if (opts.pnotify_animation == "fade" || opts.pnotify_animation.effect_in == "fade") {
					// If it's fading in, it should start at 0.
					pnotify.show().fadeTo(0, 0).hide();
				} else {
					// Or else it should be set to the opacity.
					if (opts.pnotify_opacity != 1)
						pnotify.show().fadeTo(0, opts.pnotify_opacity).hide();
				}
				pnotify.animate_in(function(){
					if (opts.pnotify_after_open)
						opts.pnotify_after_open(pnotify);

					pnotify.pnotify_queue_position();

					// Now set it to hide.
					if (opts.pnotify_hide)
						pnotify.pnotify_queue_remove();
				});
			};

			// Remove the notice.
			pnotify.pnotify_remove = function() {
				if (pnotify.timer) {
					window.clearTimeout(pnotify.timer);
					pnotify.timer = null;
				}
				// Run callback.
				if (opts.pnotify_before_close) {
					if (opts.pnotify_before_close(pnotify) === false)
						return;
				}
				pnotify.animate_out(function(){
					if (opts.pnotify_after_close) {
						if (opts.pnotify_after_close(pnotify) === false)
							return;
					}
					pnotify.pnotify_queue_position();
					// If we're supposed to remove the notice from the DOM, do it.
					if (opts.pnotify_remove)
						pnotify.detach();
				});
			};

			// Animate the notice in.
			pnotify.animate_in = function(callback){
				// Declare that the notice is animating in. (Or has completed animating in.)
				animating = "in";
				var animation;
				if (typeof opts.pnotify_animation.effect_in != "undefined") {
					animation = opts.pnotify_animation.effect_in;
				} else {
					animation = opts.pnotify_animation;
				}
				if (animation == "none") {
					pnotify.show();
					callback();
				} else if (animation == "show") {
					pnotify.show(opts.pnotify_animate_speed, callback);
				} else if (animation == "fade") {
					pnotify.show().fadeTo(opts.pnotify_animate_speed, opts.pnotify_opacity, callback);
				} else if (animation == "slide") {
					pnotify.slideDown(opts.pnotify_animate_speed, callback);
				} else if (typeof animation == "function") {
					animation("in", callback, pnotify);
				} else {
					if (pnotify.effect)
						pnotify.effect(animation, {}, opts.pnotify_animate_speed, callback);
				}
			};

			// Animate the notice out.
			pnotify.animate_out = function(callback){
				// Declare that the notice is animating out. (Or has completed animating out.)
				animating = "out";
				var animation;
				if (typeof opts.pnotify_animation.effect_out != "undefined") {
					animation = opts.pnotify_animation.effect_out;
				} else {
					animation = opts.pnotify_animation;
				}
				if (animation == "none") {
					pnotify.hide();
					callback();
				} else if (animation == "show") {
					pnotify.hide(opts.pnotify_animate_speed, callback);
				} else if (animation == "fade") {
					pnotify.fadeOut(opts.pnotify_animate_speed, callback);
				} else if (animation == "slide") {
					pnotify.slideUp(opts.pnotify_animate_speed, callback);
				} else if (typeof animation == "function") {
					animation("out", callback, pnotify);
				} else {
					if (pnotify.effect)
						pnotify.effect(animation, {}, opts.pnotify_animate_speed, callback);
				}
			};

			// Cancel any pending removal timer.
			pnotify.pnotify_cancel_remove = function() {
				if (pnotify.timer)
					window.clearTimeout(pnotify.timer);
			};

			// Queue a removal timer.
			pnotify.pnotify_queue_remove = function() {
				// Cancel any current removal timer.
				pnotify.pnotify_cancel_remove();
				pnotify.timer = window.setTimeout(function(){
					pnotify.pnotify_remove();
				}, (isNaN(opts.pnotify_delay) ? 0 : opts.pnotify_delay));
			};

			// Provide a button to close the notice.
			pnotify.closer = $("<div />", {
				"class": "ui-pnotify-closer",
				"css": {"cursor": "pointer", "display": "none"},
				"click": function(){
					pnotify.pnotify_remove();
					pnotify.closer.hide();
				}
			})
			.append($("<span />", {"class": "ui-icon ui-icon-circle-close"}))
			.appendTo(pnotify.container);

			// Add the appropriate icon.
			if ((opts.pnotify_error_icon && opts.pnotify_type == "error") || (opts.pnotify_notice_icon)) {
				$("<div />", {"class": "ui-pnotify-icon"})
				.append($("<span />", {"class": opts.pnotify_type == "error" ? opts.pnotify_error_icon : opts.pnotify_notice_icon}))
				.appendTo(pnotify.container);
			}

			// Add a title.
			pnotify.title_container = $("<span />", {
				"class": "ui-pnotify-title",
				"html": opts.pnotify_title
			})
			.appendTo(pnotify.container);
			if (typeof opts.pnotify_title != "string")
				pnotify.title_container.hide();

			// Replace new lines with HTML line breaks.
			if (opts.pnotify_insert_brs && typeof opts.pnotify_text == "string")
				opts.pnotify_text = opts.pnotify_text.replace("\n", "<br />");
			// Add text.
			pnotify.text_container = $("<span />", {
				"class": "ui-pnotify-text",
				"html": opts.pnotify_text
			})
			.appendTo(pnotify.container);
			if (typeof opts.pnotify_text != "string")
				pnotify.text_container.hide();

			// Set width and min height.
			if (typeof opts.pnotify_width == "string")
				pnotify.css("width", opts.pnotify_width);
			if (typeof opts.pnotify_min_height == "string")
				pnotify.container.css("min-height", opts.pnotify_min_height);

			// The history variable controls whether the notice gets redisplayed
			// by the history pull down.
			pnotify.pnotify_history = opts.pnotify_history;

			// Add the notice to the notice array.
			var body_data = body.data("pnotify");
			if (typeof body_data != "object")
				body_data = [];
			body_data = $.merge(body_data, [pnotify]);
			body.data("pnotify", body_data);

			// Run callback.
			if (opts.pnotify_after_init)
				opts.pnotify_after_init(pnotify);

			if (opts.pnotify_history) {
				// If there isn't a history pull down, create one.
				var body_history = body.data("pnotify_history");
				if (typeof body_history == "undefined") {
					body_history = $("<div />", {
						"class": "ui-pnotify-history-container ui-state-default ui-corner-bottom",
						"mouseleave": function(){
							body_history.animate({top: "-"+history_handle_top+"px"}, {duration: 100, queue: false});
						}
					})
					.append($("<div />", {"class": "ui-pnotify-history-header", "text": "Redisplay"}))
					.append($("<button />", {
							"class": "ui-pnotify-history-all ui-state-default ui-corner-all",
							"text": "All",
							"hover": function(){
								$(this).toggleClass("ui-state-hover");
							},
							"click": function(){
								// Display all notices. (Disregarding non-history notices.)
								$.each(body_data, function(){
									if (this.pnotify_history && this.pnotify_display)
										this.pnotify_display();
								});
								return false;
							}
					}))
					.append($("<button />", {
							"class": "ui-pnotify-history-last ui-state-default ui-corner-all",
							"text": "Last",
							"hover": function(){
								$(this).toggleClass("ui-state-hover");
							},
							"click": function(){
								// Look up the last history notice, and display it.
								var i = 1;
								while (!body_data[body_data.length - i] || !body_data[body_data.length - i].pnotify_history) {
									if (body_data.length - i === 0)
										return false;
									i++;
								}
								if (body_data[body_data.length - i].pnotify_display)
									body_data[body_data.length - i].pnotify_display();
								return false;
							}
					}))
					.appendTo(body);

					// Make a handle so the user can pull down the history pull down.
					var handle = $("<span />", {
						"class": "ui-pnotify-history-pulldown ui-icon ui-icon-grip-dotted-horizontal",
						"mouseenter": function(){
							body_history.animate({top: "0"}, {duration: 100, queue: false});
						}
					})
					.appendTo(body_history);

					// Get the top of the handle.
					history_handle_top = handle.offset().top + 2;
					// Hide the history pull down up to the top of the handle.
					body_history.css({top: "-"+history_handle_top+"px"});
					// Save the history pull down.
					body.data("pnotify_history", body_history);
				}
			}

			// Display the notice.
			pnotify.pnotify_display();

			return pnotify;
		}
	});

	$.pnotify.defaults = {
		// Additional classes to be added to the notice. (For custom styling.)
		pnotify_addclass: "",
		// Display a pull down menu to redisplay previous notices, and place the notice in the history.
		pnotify_history: true,
		// Width of the notice.
		pnotify_width: "300px",
		// Minimum height of the notice. It will expand to fit content.
		pnotify_min_height: "16px",
		// Type of the notice. "notice" or "error".
		pnotify_type: "notice",
		// The icon class to use if type is notice.
		pnotify_notice_icon: "ui-icon ui-icon-info",
		// The icon class to use if type is error.
		pnotify_error_icon: "ui-icon ui-icon-alert",
		// The animation to use when displaying and hiding the notice. "none", "show", "fade", and "slide" are built in to jQuery. Others require jQuery UI. Use an object with effect_in and effect_out to use different effects.
		pnotify_animation: "fade",
		// Speed at which the notice animates in and out. "slow", "def" or "normal", "fast" or number of milliseconds.
		pnotify_animate_speed: "slow",
		// Opacity of the notice.
		pnotify_opacity: 1,
		// Provide a button for the user to manually close the notice.
		pnotify_closer: true,
		// After a delay, remove the notice.
		pnotify_hide: true,
		// Delay in milliseconds before the notice is removed.
		pnotify_delay: 8000,
		// Remove the notice's elements from the DOM after it is removed.
		pnotify_remove: true,
		// Change new lines to br tags.
		pnotify_insert_brs: true
	};
})(jQuery);