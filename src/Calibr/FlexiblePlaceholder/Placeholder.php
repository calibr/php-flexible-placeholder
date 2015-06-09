<?php

namespace Calibr\FlexiblePlaceholder;

class Placeholder
{
  protected $leftEdge = "{%";
  protected $rightEdge = "%}";
  protected $tagName = "";

  /**
   * this function should be overrided by user placeholder class
   * @param  [array] $options - parsed options
   * @return [string]
   */
  protected function getText($options) {
  }

  public function __construct() {
  }

  private function _pregCallback($matches) {
    $options = array();
    if(isset($matches[1])) {
      // parse options
      $tmp = explode(",", $matches[1]);
      foreach($tmp as $opt) {
        $tmp2 = explode("=", $opt);
        if(count($tmp2) !== 2) {
          continue;
        }
        $key = $tmp2[0];
        $value = $tmp2[1];
        if($value === "true") {
          $value = true;
        }
        if($value === "false") {
          $value = false;
        }
        $options[$key] = $value;
      }
    }
    return $this->getText($options);
  }

  public function process($text) {
    $regExp = "@".preg_quote($this->leftEdge).
              preg_quote($this->tagName)."(?:\[([a-z0-9_=,]+)\])?".
              preg_quote($this->rightEdge)."@i";
    return preg_replace_callback($regExp, array($this, "_pregCallback"), $text);
  }
}