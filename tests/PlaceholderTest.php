<?php

require __DIR__."/../src/Calibr/FlexiblePlaceholder/Placeholder.php";

use Calibr\FlexiblePlaceholder\Placeholder;

class SimplePlaceholder extends Placeholder {
  protected $tagName = "simple";
  public $text = "replaced";
}

class PlaceholderWithOptions extends Placeholder {
  protected $tagName = "withoptions";
  protected function getText($options) {
    return http_build_query($options);
  }
}

class PlaceholderWithAliases extends Placeholder {
  protected $tagName = array("name", "alias1", "alias2");
  public $text = "sampletext";
}

class PlaceholderTest extends PHPUnit_Framework_TestCase
{
  public function testSimpleReplace() {
    $text = "Replace me {%simple%}";
    $ph = new SimplePlaceholder();
    $text = $ph->process($text);
    $this->assertEquals($text, "Replace me replaced");
  }

  public function testSimpleReplaceTwice() {
    $text = "Replace me {%simple%} {%simple%}";
    $ph = new SimplePlaceholder();
    $text = $ph->process($text);
    $this->assertEquals($text, "Replace me replaced replaced");
  }

  public function testReplaceWithOptions() {
    $text = "Replace me {%withoptions[key=value,key2=value2]%} {%withoptions[key3=value3]%}";
    $ph = new PlaceholderWithOptions();
    $text = $ph->process($text);
    $this->assertEquals($text, "Replace me key=value&key2=value2 key3=value3");
  }

  public function testPlaceholderWithAliases() {
    $text = "text {%name%} {%alias1%} {%alias2%} end";
    $ph = new PlaceholderWithAliases();
    $text = $ph->process($text);
    $this->assertEquals($text, "text sampletext sampletext sampletext end");
  }
}