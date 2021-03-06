## Rubbish Thor Clone

Rubbish Thor Clone is a rubbish clone of [Thor](http://whatisthor.com/), a popular toolkit for CLI applications written in Ruby.

Rubbish Thor Clone exposes an executable with a number of subcommands, like git or bundler.

To install:

```bash
$ cd somewhere_sensible
$ composer require dxw/RubbishThorClone dev-master
```

To use, first create a class that inherits from RubbishThorClone:

```php
<?php

require __DIR__."/vendor/autoload.php";

class HelloWorld extends RubbishThorClone {

};
```

For each subcommand you want to define, you need to add a definition and a callback. Definitions go in a method called commands. Callbacks should be public methods on your class, with the same name as the subcommand:

```php
class HelloWorld extends RubbishThorClone {
  public function commands() {
    $this->command('hello NAME', 'say hello to NAME');
  }

  public function hello($name) {
    echo "Hi, $name!\n";
  }
};
```

You can also add options to subcommands. To define options, pass a callback in when you define your subcommand:

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

Rubbish Thor Clone uses the OptionParser library to parse command lines, so [check out its API](https://github.com/mjijackson/optionparser) for more.

After you've done all that, you just need to instantiate your class and call start:

```php
$hello = new HelloWorld();
$hello->start($argv);
```

For the full code, check hello_world.class.php in the examples directory.

## Rubbish Thor Clone's API

### RubbishThorClone::commands

An abstract function your class should implement. It's expected to call RubbishThorClone::command to define all your subcommands.

### RubbishThorClone::command

Call this to define your subcommands. It takes three arguments: $definition, $description and (optionally) $options_callback.

#### $definition

Your subcommand's definition. Consists of the subcommand name followed by a number of mandatory arguments:

```
subcommand ARG1 ARG2 [...] ARGn
```

A method with the same name as your subcommand must exist in your class. It must accept an equal number of parameters as the subcommand has arguments. They will be passed into your method in the order they appear on the command line.

You can specify an optional argument by wrapping it in [brackets]:

```
subcommand REQ_1 REQ_2 [OPTIONAL]
```

if a command has an optional argument, there must only be one, and it must be the last one.

You can also bypass RubbishThorClone's argument parsing by prepending the argument with an asterisk. This helpful where arguments can contain spaces, or where you just want to parse things for yourself:

```
subcommand *ARGUMENT
```

A command can only have one argument if it's a pass-through argument. If you use this notation, RubbishThorClone will pass the whole contents of the command line (after the command name) as one argument.


### $description

A human-friendly short description of the subcommand, used to generate usage help.

### $options_callback

A callable that defines the options your subcommand accepts, using [OptionParser's API](https://github.com/mjijackson/optionparser). RubbishThorClone has a built in "help" command, whose output is also generated from the information you provide here (using OptionParser::addHead and soforth).

## RubbishThorClone::usage

Emits a list of the subcommands you have defined, along with their short descriptions.

## RubbishThorClone::start

Takes one argument ($argv), figures out what to do with it , and calls your callbacks as necessary.


