<?php

abstract class HashMethods {
    
    public function merge($hash) {
        if (is_a($hash, 'H')) $hash = $hash->array;
        return $this->new_instance(array_merge($this->array, $hash));
    }
    
}

class H extends Enumerable { }

extend('H', 'HashMethods');

?>