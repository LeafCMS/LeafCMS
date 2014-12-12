<?php
/*
 * --------------------*
 * Primary Config File *
 * --------------------*
 * ----------------------------------------------------------------------*
 * This is the primary config file for your website                      *
 * All pages / templates / extensions are defined in this file           *
 * Your website IS BASICALLY run off this file! It is VERY important.    *
 * Be careful, treat it like a twig (pun intended).                      *
 * We setup some dummy values to start a main page up for you            *
 * Feel free to replace the dummy values!                                *
 * Enjoy LeafCMS; branch out, create what you want!                      *
 * ----------------------------------------------------------------------*
 *
 */
return array(
	"page_templates" => array(
		"home" => array(
			"template" => "start", // Start template (this is a dummy value)
			"bindings" => array(
				"title" => "LeafCMS - WELCOME!",
				"logo" => ASSETS_DIR.'img/logo.png',
			),
			"extensions" => array(
				'startExtension',
			),
		),
	),
	"extensions" => array(
		// all files for extensions SHOULD be placed in /branches/extensions/.
		// the file by default is set to PHP. You don't need to add the extension
		'startExtension' => array(
			'file' => 'start',
			'function' => 'generateStart',
		),
	),
);
