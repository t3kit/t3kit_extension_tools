/**
 *
 */
define(['jquery'], function (jQuery) {

	var Customizer = {};

	Customizer.initialize = function() {
		Customizer.bindCustomizeThemeEvent();
		// Get Constant Configuration To Save
		Customizer.bindSaveCustomizedThemeEvent();
	};

	Customizer.bindCustomizeThemeEvent = function () {
		// get url for iframe
		// get from localstorage if any
		// or use sitedomain
		var customizerIframeWrapper = jQuery('#customizerIframeWrapper');
		var localCustomizerUrl = window.localStorage.getItem('customizerUrl');
		var customizerUrl = jQuery('#customizerUrl');

		if (localCustomizerUrl != null || localCustomizerUrl != '') {
			customizerUrl.val(localCustomizerUrl);
		} else {
			customizerUrl.val(jQuery('#siteDomain').val());
		}
		var customizerIframe = '<iframe id="customizerIframe" src="'+customizerUrl.val()+'" style="height:670px"></iframe>';
		customizerIframeWrapper.append(customizerIframe);
		var a = jQuery(customizerIframeWrapper.html());
		// a = jQuery(a);
		// jQuery(a[0]).on("message", function(e) {
		// 	console.log(e.origin);
		// });

		// save url to localstorage when URL changes
		// and reload
		customizerUrl.change(function () {
			window.localStorage.setItem('customizerUrl', this.value);
			window.location.reload();
		});
	};

	/**
	 * Get Constants from Theme Customizer in iframe
	 * Set Those Values to the Form
	 */
	Customizer.bindSaveCustomizedThemeEvent = function () {
		jQuery('#saveIcon').click(function() {

			// get values
			// dummy data
			var items = '{"@footer-link-hover-color":"#0D4C62","@border-color":"#0B79A3","@main-text-color":"#B35837"}';

			// TODO: to solve origin problem to get content in localstorage or have customizer work on real site
			// permission problem with cross-origin
			// var customizerIframe = document.getElementById("customizerIframe");
			// customizerIframe = (customizerIframe.contentWindow || customizerIframe.contentDocument);
			// items = customizerIframe.localStorage.getItem('lessObj');

			if(items == null || items == '') {
				alert('No values to save');
			} else {
				// set changed values to form
				items = items.replace(/\@|\"|\{|\}/g,'');
				items = items.split(',');
				for(var i=0; i<items.length; i++) {
					var item = items[i].split(':');
					var elId = item[0].replace(/\-|\_/g,'');
					item = jQuery('#'+ elId).val(item[1]);
				}

				//submit form
				jQuery('#saveableForm').submit();
			}
		});
	};

	/**
	 * initialize function
	 * */
	return function () {
		Customizer.initialize();
		return Customizer;
	}();
});
