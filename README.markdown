# phuby

rubyisms in php

(pronounced foo-bee)


## Installation

	git clone git://github.com/shuber/phuby.git


## Features

INCOMPLETE

* mixins - methods and properties can be added to an existing class OR instance of an object
* alias_method and alias_method_chain (also works at the class OR instance level)
* Object class (all classes should inherit from this)
* Enumerable, A (array), and H (hash) classes
* blocks (as strings)


## Usage

	require_once 'phuby/phuby.php';

Make sure your classes extend `Object`

	class User extends Object {
	    # ...
	}

More coming soon...


## Testing
Phuby uses SimpleSpec BDD extension to SimpleTest, just download simple test and 
put it as part of your php include_path or 'phuby/spec/simpletest'

 - SimpleTest
 - SimpleSpec class 

please run spec/runner.php


## Contact

Problems, comments, and suggestions all welcome: [shuber@huberry.com](mailto:shuber@huberry.com)