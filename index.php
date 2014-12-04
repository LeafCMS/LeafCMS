<?php
define('ROOT_DIR', dir(__FILE__));
define('TEMPLATE_DIR', ROOT_DIR.'templates/');
define('PLUGIN_DIR', ROOT_DIR.'plugins/');
define('INCLUDE_DIR', ROOT_DIR.'includes/');

include(INCLUDE_DIR.'leafcms.php');

$leaf = new LeafCMS;

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

$leaf->runPage($page);
$leaf->runExtensions();

$html = $leaf->run();

echo $html;
?>
