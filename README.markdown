# phuby

rubyisms in php


## Installation

	git clone git://github.com/shuber/phuby.git


## Features

INCOMPLETE

* `extend($class_or_object, $classes)`                 - Injects the methods/properties of each member of `$classes` into `$class_or_object`
* `build_static_method_call($class, $method, $arguments = array(), $variable_name = 'arguments')` - Returns a string representing a method call that can be eval'd
* `get_static_property($class, $property)`             - Returns the static `$property` of `$class`
* `set_static_property($class, $property, $value)`     - Sets the static `$property` of `$class` to `$value`
* `Object::extended($class_or_object)`                 - Called after an object extends the current class
* `Object#call($method, $array_of_args)`               - Calls `$method` with each member of `$array_of_args` as an argument (like ruby's splat `*` operator)
* `Object#call_extended_method($method, $array_of_args)` - Calls the extended `$method` with each member of `$array_of_args` as an argument
* `Object#extend($classes_or_objects)`                 - Accepts a list of classes or objects to mixin to the current class
* `Object#initialize($optional_args)`                  - Replaces `Object#__construct` (so that it works with `super`)
* `Object#is_a($class_name)`                           - Checks if the current object or one of its parents is a `$class_name`
* `Object#finalize()`                                  - Replaces `Object#__destruct` (so that it works with `super`)
* `Object#respond_to($method_name)`                    - Checks if the current object has `$method_name` defined and returns a boolean
* `Object#send($method_name, $optional_args)`          - Calls `$method_name` in the current object with the arguments passed to it
* `Object#super($optional_args)`                       - Calls the method that the current method overwrote (must be called inside of a method)


## Usage

	require_once 'phuby/phuby.php';

Make sure your classes extend `Object`

	class User extends Object {
	    # ...
	}

More coming soon...


## Contact

Problems, comments, and suggestions all welcome: [shuber@huberry.com](mailto:shuber@huberry.com)