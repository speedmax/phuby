<?php
require 'spec_helper.php';

class Describe_Object extends SimpleSpec {

    function should_be_able_to_invoke_overloaded_methods() {
        $t = new Testing;
        
        expects($t->testing())->should_be('Whoa::testing');
        
        expects($t->testing2())->should_be('Dude::testing2');
    }
    
    function should_dispatch_message_passing_using_method_send() {
        $t = new Testing;
        expects($t->send('testing'))->should_be('Whoa::testing');
        
    }
    
    function should_respond_to_a_implemented_method() {
        $t = new Testing;
        
        expects($t->respond_to('real_method'))->should_be(true);

        expects($t->respond_to('testing'))->should_be(true);
        
        expects($t->respond_to('protected_method'))->should_be(true);

        expects($t->respond_to('protected_method'))->should_be(true);
        
        expects($t->respond_to('invalid'))->should_be_false();
    }
    
    function should_be_instance_of_a_class(){
        $t = new Testing;
        
        expects($t->is_a('Testing'))->should_be(true);
        
        expects($t->is_a('Invalid'))->should_be(false);
    }
    
    function should_call_parent_method_using_method_super() {
        $t = new Testing;
        expects($t->super_test('sean'))->should_match('/Hello sean from Whoa::super_test/');
    }
    
    function should_raise_error_when_caller_does_not_exists_for_method_super() {
        $t = new Testing;
        // fixme: this is not working yet
        // $t->super();
        // $this->should_expect_error();
    }
}


class Whoa {
    function super_test($name) {
        return "Hello {$name} from ". __METHOD__;
    }
    
    function testing() {
        return __METHOD__;
    }
}

class Dude {
    public $test_property = 'cool';
    
    static function extended($object) {
        if (is_object($object)) $object = get_class($object);
        // echo $object.' extended Dude'."\n";
    }
    
    function super_test($name) {
        return __METHOD__ . ' ' . $this->super($name);
    }
    
    function testing2() {
        return __METHOD__;
    }
}

class UhOh {
    function testing() {
        $this->super();
    }
}

class Testing extends Object {
    
    function initialize() {
        extend($this, 'Whoa', 'Dude');
    }
    
    function real_method() {
        return 'real_method';
    }
    
    protected function protected_method() {
        return 'protected_method';
    }
    
}
