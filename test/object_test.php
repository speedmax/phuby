<?php

require_once '../phuby.php';

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
    
    static function extended($object) {
        if (is_object($object)) $object = get_class($object);
        // echo $object.' extended Dude'."\n";
    }
    
    function super_test($name) {
        echo "I like the name $name\n";
        return $this->super($name);
    }
    
    function testing2() {
        echo 'totally';
        return 'this is a returned value';
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

$t = new Testing;

$t->testing();
echo "\n";

$t->testing2();
echo "\n";

echo $t->testing2();
echo "\n";

$t->send('testing');
echo "\n";

echo $t->respond_to('real_method');
echo "\n";

echo $t->respond_to('testing');
echo "\n";

echo $t->respond_to('protected_method');
echo "\n";

echo $t->respond_to('invalid');
echo "\n";

echo $t->super_test('sean');
echo "\n";

echo $t->is_a('Testing');
echo "\n";

echo $t->is_a('Invalid');
echo "\n";

// print_r($t);
$t->dup();

$t->super();

?>