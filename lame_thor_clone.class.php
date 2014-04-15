<?php

require dirname(__FILE__) . "/optionparser/lib/OptionParser.php";

abstract class LameThorClone {
  private $executable;
  private $command;

  private $command_defs;
  private $option_parser;

  public $options;

  function __construct() {
    $this->option_parser = new OptionParser;
    $this->commands();

    $this->command('help COMMAND', 'get help on a COMMAND');
  }

  public abstract function commands();

  public function start($argv) {
    $this->executable = $argv[0];

    if(count($argv) > 1) {
      $this->command      = $argv[1];
      $this->command_def  = $this->command_defs[$this->command];
    }
    else {
      $this->usage();
      exit(1);
    }

    # Find a command with this name
    if(!isset($this->command_defs[$this->command])) {
      $this->die_with_usage_error("unknown command");
    }

    # Make sure we've got the method
    if(!method_exists($this, $this->command)) {
      $this->die_with_usage_error("undefined command (you probably need to add a callback for it)");
    }

    if($this->command_def->options_callback) {
      $arguments = $this->parse_options($this->command_def->options_callback);
    }
    else {
      $arguments = array_slice($argv, 2);
    }

    # Get the arguments and check there are enough
    if(count($arguments) < count($this->command_def->arguments)) {
      $this->die_with_usage_error("wrong number of arguments; expected: {$this->command_def->definition}");
    }

    call_user_func_array(array($this, $this->command), $arguments);
  }

  protected function help($command) {
    $help_command = $this->command_defs[$command];

    echo "{$this->executable} {$this->command} ";

    foreach($help_command->arguments as $argument) {
      echo "{$argument} ";
    }

    echo "    # {$help_command->description}\n\n";

    if($help_command->options_callback) {
      call_user_func($help_command->options_callback, $this->option_parser);
      echo $this->option_parser->getUsage();
    }
  }

  private function parse_options($options_callack) {
    call_user_func($options_callback, $this->option_parser);

    try {
      $args = $this->option_parser->parse();
    }
    catch(Exception $e) {
      $this->die_with_usage_error("bad options. Run `{$this->executable} help {$this->command}' for more information");
    }

    $this->options = (object)$this->option_parser->getAllOptions();

    return $args;
  }

  private function die_with_usage_error($message, $usage = true) {
    echo "Error: {$message}\n";
    if($usage) {
      $this->usage();
    }

    exit(1);
  }

  # TODO: nice column alignment
  public function usage() {
    echo "Tasks:\n\n";

    foreach($this->command_defs as $command => $def) {
      echo "  {$this->executable} {$this->command} ";

      foreach($def->arguments as $argument) {
        echo "{$argument} ";
      }

      echo "    # {$def->description}\n";
    }
  }

  protected function command($definition, $description, $options_callback = false, $help_callback = false) {
    $command_bits = explode(' ', $definition);

    $name = $command_bits[0];
    array_shift($command_bits);
    $arguments = $command_bits;

    $this->command_defs[$name] = (object)array(
      'arguments'        => $arguments,
      'description'      => $description,
      'definition'       => $definition,
      'options_callback' => $options_callback,
    );
  }
};