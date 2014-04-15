## Lame Thor Clone

Lame Thor Clone is a Lame Clone of Thor, a popular toolkit for CLI applications written in Ruby.

Lame Thor Clone exposes an executable with a number of subcommands, like git or bundler.

To install:

```bash
$ cd somewhere_sensible
$ git clone git@github.com:dxw/LameThorClone.git
$ git submodule update --init
```

To use, first create a class that inherits from LameThorClone:

```php
<?php

require "lame_thor_clone.class.php";

class HelloWorld extends LameThorClone {

};
```

For each command you want to define, you need to add a definition and a callback. Definitions go in a method called commands. Callbacks should be public methods on your class:

```php
class HelloWorld extends LameThorClone {
  public function commands() {
    $this->command('hello NAME', 'say hello to NAME');
  }

  public function hello($name) {
    echo "Hi, $name!\n";
  }
};
```

You can also add options to commands. To define options, pass a callback in when you define your command:

```php
public function commands() {
  $this->command('hello NAME', 'say hello to NAME', function($option_parser) {
    $option_parser->addHead("Says hello to a person.\n");
    $option_parser->addRule('s|sexy', "Congratulates NAME on their sexy face");
  });
}
```

After you've added options, you can access them via $this->options:

```php
public function hello($name) {
  echo "Hi, $name!\n";
  if(isset($this->options->sexy)) {
    echo "You have a very sexy face. Congratumalations.\n";
  }
}
```

Lame Thor Clone uses the OptionParser library to parse command lines, so [check out its API](https://github.com/mjijackson/optionparser) for more.

After you've done all that, you just need to instantiate your class and call start:

```php
$hello = new HelloWorld();
$hello->start($argv);
```

For the full code, check hello_world.class.php in the examples directory.

## Lame Thor Clone's API

### LameThorClone::commands

An abstract function your class should implement. It's expected to call LameThorClone::command to define all your subcommands.

### LameThorClone::command

Call this to define your subcommands. It takes three arguments: $definition, $description and (optionally) $options_callback.

#### $definition

Your subcommand's definition. Consists of the subcommand name followed by a number of mandatory arguments:

```
subcommand ARG1 ARG2 [...] ARGn
```

A method with the same name as your subcommand must exist in your class. It must accept an equal number of parameters as the subcommand has arguments. They will be passed into your method in the order they appear on the command line.

### $description

A human-friendly short description of the subcommand, used to generate usage help.

### $options_callback

A callable that defines the options your subcommand accepts, using [OptionParser's API](https://github.com/mjijackson/optionparser). LameThorClone has a built in "help" command, whose output is also generated from the information you provide here (using OptionParser::addHead and soforth).

## LameThorClone::usage

Emits a list of the subcommands you have defined, along with their short descriptions.

## LameThorClone::start

Takes one argument ($argv), figures out what to do with it , and calls your callbacks as necessary.


