<?php

require_once '../phuby.php';

class Whoa {
    public function super_test($name) {
        echo "Hello $name\n";
    }
    
    public function testing() {
        echo 'cooool';
    }
}

class Dude {
    public function super_test($name) {
        $this->super($name);
        echo "I like the name $name\n";
    }
    
    public function testing2() {
        echo 'totally';
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

?>