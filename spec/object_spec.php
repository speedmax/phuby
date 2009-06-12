<?php
require 'spec_helper.php';

class Describe_Object extends SimpleSpec {

    function should_be_able_to_invoke_overloaded_methods() {
        $t = new Testing;
        
        expects($t->testing())->should_be('cooool');
        
        expects($t->testing2())->should_be('this is a returned value');
    }
    
    function should_dispatch_message_passing_using_method_send() {
        $t = new Testing;
        expects($t->send('testing'))->should_be('cooool');
        
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
        expects($t->super_test('sean'))->should_match('/Hello sean from super/');
    }
    
    function should_raise_error_when_caller_does_not_exists_for_method_super() {
        $t = new Testing;
        //fixme: this is not working yet
        // $t->super();
        // $this->should_expect_error();
    }
}


class Whoa {
    function super_test($name) {
        return "Hello {$name} from super";
    }
    
    function testing() {
        return 'cooool';
    }
}

class Dude {
    public $test_property = 'cool';
    
    function super_test($name) {
        return $this->super($name);
    }
    
    function testing2() {
        return 'this is a returned value';
    }
    
    function delegated() {
        return 'delegated from Dude'."\n";
    }
}

class UhOh {
    function testing() {
        $this->super();
    }
}

class Testing extends Object {
    
    function real_method() {
        return 'real_method';
    }
    
    protected function protected_method() {
        return 'protected_method';
    }
    
}
Testing::extend('Whoa', 'Dude');
