<?php
/*
 * Copyright 2014 LeafCMS (Primary Developer(s): Matthew Gross)
 * Description **
 * Last Edit: December 11, 2014
 */

/* Standard Definitions
 * Description: We are defining all neccessary file paths for the script to use. These are defined in a global fashion
 */
define('ROOT_DIR', dir(__FILE__));
define('BRANCHES_DIR', ROOT_DIR.'/branches/');
define('ASSETS_DIR', ROOT_DIR.'/assets');

define('CONFIG_FILE', BRANCHES_DIR.'config.php');

/* Actual Class
 * This defines all logic for the CMS
 * Every request to the server uses this class!
 */
class LeafCMS {
  
  private $default_bindings = array(
     "template_dir" => BRANCHES_DIR.'templates/',
     "page_templates" => array(
         "home" => "home",
     ),
  );

  public $output;
  
  protected $config;
  
  public function __construct() {
    $this->config = include(CONFIG_FILE);
    // Set default bindings.
    $this->setDefaultBindings();
  }

  public function setDefaultBindings() {
    $default_bindings = $this->default_bindings;
    foreach($default_bindings as $k => $v) {
        if(!(isset($this->config[$k]))) {
		$this->config[$k] = $v;
    }
  }

  public function getTemplate($template) {
    $template_dir = $this->config['template_dir'];
    $template = $template_dir.$template.'.template.html';
    return file_get_contents($template);
  }

  public function setBinding($template, $setting, $binding) {
    return str_replace('>>'.$binding.'<<', $setting, $template);
  }

  /* returnError(code)
   * description = easily return an error (such as 404) to the user. helper function
   * code = The error code id.
   */
  public function returnError($code) {
    $message = 'Unknown Error'; // by default.
    switch($code) {
      case 404:
        // Not Found.
        $message = "404 Error. The page could not be found!"; // @todo: set to config value later;
        break;
      case 403:
        // Access Denied.
        $message = "403 Access Denied / Forbidden.";
        break;
    }
    $template = $this->getTemplate("error");
    $template = $this->setBinding("message", $message, $template);
    return $template;
  }
  
  public function runPage($page) {
    $config = $this->config;
    $output = '';
    if(isset($config['page_templates'][$page])) {
      $this->output = $this->getTemplate($config['page_templates'][$page]);
    }
    else {
      // return 404.
      $this->output = $this->returnError('404');
      return false; // error.
    }
    return true;
  }
  
  public function runExtensions($extension='all') {
    $config = $this->config;
    if($extension == 'all') {
      // load all.
      foreach($config['extensions'] as $extension) {
        // include the file.
        include($extension['file']);
        // call the function.
        call_user_func($extension['function']);
      }
    }
    else {
      include($config['extensions'][$extension]['file']);
      call_user_func($config['extensions'][$extension]['function']);
    }
  }
  
}


$leaf = new LeafCMS;

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

$leaf->runPage($page);
$leaf->runExtensions();

$html = $leaf->output;

echo $html;
?>
