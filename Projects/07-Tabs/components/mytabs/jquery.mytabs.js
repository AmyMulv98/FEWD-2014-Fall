;(function ($, window) {

	var pub = {
		activate: function(index) {
			return $(this).each(function() {
				var data = $(this).data("myTabs");

				if (data) {	
				activateTab(data, index);
				}	
			});
		}
	};

	var options = {};

	function init(opts) {
		var $target = $(this);

		opts = $.extend({}, options, opts);

		$target.each (function() { 
			var $instance = $(this);

			var data = {
				$links: $instance.find(".tab-nav .tab"),
				$content: $instance.find(".tab-content .tab")
			};

			$instance.on ("click", ".tab-nav .tab", data, onTabClick)
					.data("myTabs", data);
	});

	}

	function onTabClick(e) {
		e.preventDefault();

		var data = e.data;
		var $target = $(e.target);
		var index = data.$links.index($target);

		activateTab(data, index);
	}


	function activateTab(data, index) {
		data.$links.removeClass("active")
						.eq(index)
						.addClass("active"); 
		data.$content.removeClass("active")
						.eq(index)
						.addClass("active");

	}

	$.fn.myTabs = function (method) {
		if (pub[method]) {
			return pub[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method ==="object" || !method) { 
			return init.apply(this, arguments);		
		}
		return this;
	};

})(jQuery, this);

$(".tabbed_interface").myTabs 