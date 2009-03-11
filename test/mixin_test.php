<?php

require_once '../phby.php';

class Whoa {
    public function testing() {
        echo 'cooool';
    }
}

class Dude {
    public function testing2() {
        echo 'totally';
    }
}

class UhOh {
    public function testing() {
        $this->super();
    }
}

class Testing extends Mixin {
    
    public function __construct() {
        $this->mixin('Whoa', 'Dude');
        // $this->mixin('UhOh');
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

?>