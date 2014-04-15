<?php

require "rubbish_thor_clone.class.php";

class HelloWorld extends RubbishThorClone {

  public function commands() {
    $this->command('hello NAME', 'say hello to NAME', function($option_parser) {
      $option_parser->addHead("Says hello to a person.\n");
      $option_parser->addRule('b::', "Congratulates NAME on their bigness");
    });
  }

  public function hello($name) {
    echo "Hi, $name!\n";
    if(isset($this->options->big)) {
      echo "Aren't you a big fella?\n";
    }
  }
};

$hello = new HelloWorld();
$hello->start($argv);