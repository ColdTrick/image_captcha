<?php 
/**
 * Start file for the Image Captcha plugin
 */

/**
 * Initializes the captcha plugin
 * 
 * @return void
 */
function image_captcha_init(){
	elgg_define_js('s3capcha', array(
		'src' => '/mod/image_captcha/vendors/s3capcha/s3capcha.js',
		'exports' => 's3capcha',
	));
	
	// Register a function that provides some default override actions
	elgg_register_plugin_hook_handler('actionlist', 'captcha', 'image_captcha_actionlist_hook');
	
	// Register actions to intercept
	$actions = elgg_trigger_plugin_hook('actionlist', 'captcha', null, array());

	if (($actions) && (is_array($actions))) {
		foreach ($actions as $action) {
			elgg_register_plugin_hook_handler("action", $action, "image_captcha_verify_action_hook");
		}
	}
}
	
/**
 * Listen to the action plugin hook and check the captcha.
 *
 * @param string $hook        name of the hook
 * @param string $entity_type type of the hook
 * @param array  $returnvalue current returnvalue
 * @param array  $params      parameters
 * 
 * @return boolean
 */
function image_captcha_verify_action_hook($hook, $entity_type, $returnvalue, $params) {
	$token = get_input('image_captcha');
	if (($token) && ($token == $_SESSION["image_captcha"])) {
		return true;
	} else {
		register_error(elgg_echo('image_captcha:verify:fail'));
	}
	// forward to referrer or else action code sends to front page
	forward(REFERER);
}

/**
 * This function returns an array of actions the captcha will expect a captcha for, other plugins may
 * add their own to this list thereby extending the use.
 *
 * @param string $hook        name of the hook
 * @param string $entity_type type of the hook
 * @param array  $returnvalue current returnvalue
 * @param array  $params      parameters
 * 
 * @return array
 */
function image_captcha_actionlist_hook($hook, $entity_type, $returnvalue, $params) {
	if (!is_array($returnvalue)) {
		$returnvalue = array();
	}
		
	$returnvalue[] = 'register';
	$returnvalue[] = 'user/requestnewpassword';
		
	return $returnvalue;
}
	
// register default elgg events
elgg_register_event_handler("init", "system", "image_captcha_init");
	