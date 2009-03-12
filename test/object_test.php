<?php

require_once '../phuby.php';

class Whoa {
    public function super_test($name) {
        echo "Hello $name\n";
        return 'returned from super';
    }
    
    public function testing() {
        echo 'cooool';
    }
}

class Dude {
    public $test_property = 'cool';
    
    public static function extended($object) {
        if (is_object($object)) $object = get_class($object);
        echo $object.' extended Dude'."\n";
    }
    
    public function super_test($name) {
        echo "I like the name $name\n";
        return $this->super($name);
    }
    
    public function testing2() {
        echo 'totally';
        return 'this is a returned value';
    }
}

class UhOh {
    public function testing() {
        $this->super();
    }
}

class Testing extends Object {
    
    public function initialize() {
        $this->extend('Whoa', 'Dude');
    }
    
    public function real_method() {
        echo 'real_method';
    }
    
    protected function protected_method() {
        echo 'protected_method';
    }
    
}

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

$t->super();

?>