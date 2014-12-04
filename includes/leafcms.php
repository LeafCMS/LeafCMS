<?php

class Leaf {
  
  public function returnError($code) {
    switch($code) {
      case 404:
        $message = "404 Error. The page could not be found!"; // @todo: set to config value later
        $template = $this->getTemplate("error")->setBinding("message", $message);
        return $template;
    }
  }
  
  public function runPage($page) {
    $config = include(CONFIG_DIR.'pages.php');
    $output = '';
    if(isset($config['page_templates'][$page])) {
      $output = $this->getTemplate($config['page_templates'][$page];
    }
    else {
      // return 404.
      $html = $this->returnError('404');
      return false; // error.
    }
    $this->output = $output;
    return true;
  }
  
  public function runExtensions($extension='all') {
  
  }
  
}
