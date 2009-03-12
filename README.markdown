# phuby

rubyisms in php


## Installation

	git clone git://github.com/shuber/phuby.git


## Features

INCOMPLETE

* `Object#extend($classes_or_objects)`                 - Accepts a list of classes or objects to mixin to the current class
* `Object#extended()`                                  - Called after an object extends the current class
* `Object#initialize($optional_args)`                  - Replaces `Object#__construct` (so that it works with `super`)
* `Object#finalize()`                                  - Replaces `Object#__destruct` (so that it works with `super`)
* `Object#respond_to($method_name)`                    - Checks if the current object has `$method_name` defined and returns a boolean
* `Object#send($method_name, $array_of_optional_args)` - Calls `$method_name` in the current object with the arguments passed to it
* `Object#super`                                       - Calls the method that the current method overwrote (must be called inside of a method)


## Usage

	require_once 'phuby/phuby.php';

Make sure your classes extend `Object`

	class User extends Object {
	    # ...
	}

More coming soon...


## Contact

Problems, comments, and suggestions all welcome: [shuber@huberry.com](mailto:shuber@huberry.com)