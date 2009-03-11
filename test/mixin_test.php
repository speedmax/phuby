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
    
}

$t = new Testing;

$t->testing();
$t->testing2();

?>
