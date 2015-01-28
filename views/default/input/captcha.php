<?php

elgg_require_js('image_captcha/captcha');

$icon_types = elgg_get_plugin_setting("icon_types", "image_captcha", "general");

$imageW = '35';

if ($icon_types == "fruit") {
	$values = array(
		'apple',
		'strawberry',
		'lemon',
		'cherry',
		'pear'
	);
	$imageH = '33';
} else {
	$values = array(
		'house',
		'folder',
		'monitor',
		'man',
		'woman',
		'lock',
		'rss'
	);
	$imageH = '35';
}

$imagePath = elgg_get_site_url() . 'mod/image_captcha/_graphics/icons/' . $icon_types . "/";

$rand = mt_rand(0, (sizeof($values) - 1));

shuffle($values); // randomize icons on every reload

$label = elgg_echo("image_captcha:label", array(
	elgg_echo("image_captcha:icon_types:" . $icon_types . ":" . $values[$rand])
));

$captcha = '';

$options = array();

for ($i = 0; $i < sizeof($values); $i++) {
	$name = $values[$i];

	$options[$i] = mt_rand();
	$image_url = $imagePath . $name . '.jpg';

	$radio = elgg_view('input/radio', array(
		'name' => 'image_captcha',
		'value' => $options[$i],
		'options' => array($name => $options[$i]),
	));

	$icon = "<span style=\"background: url($image_url) bottom left no-repeat;\" class=\"img\"></span>";

	$captcha .= "$icon<span>$radio</span>";
}

// save in session
$_SESSION['image_captcha'] = $options[$rand];

echo <<<FORM
	<div id="image_captcha">
		<label>$label</label><br />
		<div>$captcha</div>
		<div class="clearfloat"></div>
	</div>
FORM;

?>

<style type="text/css">
	#image_captcha {
		margin: 15px 0;
	}

	#image_captcha .img {
		float: left;
		display: none;
		cursor: pointer;
		width: <?php echo $imageW; ?>px;
		height: <?php echo $imageH; ?>px;
	}
</style>
