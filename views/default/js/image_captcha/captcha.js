/**
 * Initialize the image captcha
 */
define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var s3capcha = require('s3capcha');

	var init = function() {
		$(document).ready(function(){
			$('#image_captcha').s3Capcha();
		});
	};

	elgg.register_hook_handler('init', 'system', init);
});
