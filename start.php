<?php 

	function image_captcha_init(){
		
		// Register a function that provides some default override actions
		register_plugin_hook('actionlist', 'captcha', 'image_captcha_actionlist_hook');
		
		// Register actions to intercept
		$actions = array();
		$actions = trigger_plugin_hook('actionlist', 'captcha', null, $actions);
		
		if (($actions) && (is_array($actions))) {
			foreach ($actions as $action){
				register_plugin_hook("action", $action, "image_captcha_verify_action_hook");
			}
		}
	}
	
	/**
	 * Listen to the action plugin hook and check the captcha.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 */
	function image_captcha_verify_action_hook($hook, $entity_type, $returnvalue, $params)
	{
		$token = get_input('image_captcha');
		if (($token) && ($token == $_SESSION["image_captcha"])){
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
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 */
	function image_captcha_actionlist_hook($hook, $entity_type, $returnvalue, $params){
		if (!is_array($returnvalue)){
			$returnvalue = array();
		}
			
		$returnvalue[] = 'register';
		$returnvalue[] = 'user/requestnewpassword';
			
		return $returnvalue;
	}
	
	
		
	// register default elgg events
	register_elgg_event_handler("init", "system", "image_captcha_init");
	
?>