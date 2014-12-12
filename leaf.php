<?php
/*
 * Copyright 2014 LeafCMS (Primary Developer(s): Matthew Gross, Kirk Morris)
 * Description **
 * Last Edit: December 11, 2014
 * License: MIT (see LICENSE file)
 */

/* Standard Definitions
 * Description: We are defining all neccessary file paths for the script to use. These are defined in a global fashion
 * You can change these, but be careful!
 */
define('ROOT_DIR', dirname(__FILE__));
define('BRANCHES_DIR', ROOT_DIR.'/branches/');
define('WEB_DIR', '/');
define('ASSETS_DIR', 'assets/');

define('CONFIG_FILE', BRANCHES_DIR.'config.php');

/* Actual Class
 * This defines all logic for the CMS
 * Every request to the server uses this class!
 */
class LeafCMS {
  
  private $default_bindings;

  public $output;
  
  protected $config;
  
  public function __construct() {
    $this->default_bindings = array(
     "template_dir" => BRANCHES_DIR . 'templates/',
     "page_templates" => array(
         "home" => "home",
      ),
    );
    $this->config = include(CONFIG_FILE);
    // Set default bindings.
    $this->setDefaultConfigValues();
  }

  public function setDefaultConfigValues() {
    $default_config_values = $this->default_bindings;
    foreach($default_config_values as $k => $v) {
        if(!(isset($this->config[$k]))) {
          // wasn't set
		      $this->config[$k] = $v;
        }
    }
  }

  public function getTemplate($template) {
    $template_dir = $this->config['template_dir'];
    $template = $template_dir.$template.'.template.html';
    return file_get_contents($template);
  }

  public function setBinding($binding, $setting, $template = null) {
    $binded = ($template === null) ? $this->output : $template;
    $binded = str_replace('~{'.$binding.'}~', $setting, $binded);
    if($template === null) {
      $this->output = $binded;
      return true;
    }
    else {
      return $binded;
    }
    return false;
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
        http_response_code(404);
        $message = "404 Error. The page could not be found!"; // @todo: set to config value later;
        break;
      case 403:
        // Access Denied.
        http_response_code(403);
        $message = "403 Access Denied / Forbidden.";
        break;
    }
    $template = $this->getTemplate("error");
    $template = $this->setBinding("message", $message, $template);
    $template = $this->setBinding("code", $code, $template);
    return $template;
  }
  
  public function runPage($page) {
    $config = $this->config;
    $output = '';
    if(isset($config['page_templates'][$page])) {
      $page = $config['page_templates'][$page];
      $this->output = $this->getTemplate($page['template']);
      foreach($page['bindings'] as $k => $v) {
        $this->setBinding($k, $v);
      }
      foreach($page['extensions'] as $extension) {
        $this->runExtension($extension);
      }
    }
    else {
      // return 404.
      $this->output = $this->returnError('404');
      return false; // error.
    }
    return true;
  }
  
  public function runExtension($extension='all') {
    $extension_dir = BRANCHES_DIR.'extensions/';
    $config = $this->config;
    if($extension == 'all') {
      if(isset($config['extensions'])) { // if extensions exist. if not; do nothing.
        // load all.
        foreach($config['extensions'] as $extension) {
          // remove .php if the user added it by mistake and include the file.
          include(str_replace('.php', '', $extension_dir.$extension['file']).'.php');
          // call the function.
          // also, remove () if added in config by mistake.
          call_user_func(str_replace('()', '', $extension['function']));
          return true;
        }
      }
    }
    else {
      include($extension_dir.$config['extensions'][$extension]['file'].'.php');
      call_user_func(str_replace('()', '', $config['extensions'][$extension]['function']));
      return true;
    }
    return false;
  }
  
}


$leaf = new LeafCMS;

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

$leaf->runPage($page);

$html = $leaf->output;

echo $html;
?>
