Lame Thor Clone is a Lame Clone of Thor, a popular toolkit for CLI applications written in Ruby.

Lame Thor Clone exposes an executable with a number of subcommands, like git or bundler.

To use it, first create a class that inherits from LameThorClone:

```
<?php

require "lame_thor_clone.class.php";

class HelloWorld extends LameThorClone {

};
```

For each command you want to define, you need to add a definition and a callback. Definitions go in the constructor. Callbacks should be public methods on your class:

```
class HelloWorld extends LameThorClone {

  public function commands() {
    $this->command('hello NAME', 'say hello to NAME');
  }

  public function hello($name) {
    echo "Hi, $name!\n";
  }
};
```

