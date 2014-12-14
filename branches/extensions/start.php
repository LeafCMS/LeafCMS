<?php
/* This is a dummy extensions. It generates random sayings for the front page...
 * BTW: We apologize for the really corny sayings; we have to generate comedic value somewhere... */

return function() {
	global $leaf;

	$sayings = array(
		"Thanks for branching out!",
		"Our CMS won't stump you!",
		"Put a leaf on it!",
		"Get Squirrely!",
		"Don't sway away...",
	);
	$random = array_rand($sayings, 1);
	$saying = $sayings[$random];
	$leaf->setBinding('saying', $saying);
	return true;
}