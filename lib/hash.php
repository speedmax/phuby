<?php

abstract class HashMethods {
    
    function invert() {
        return $this->new_instance(array_flip($this->array));
    }
    
    function merge($hash) {
        if (is_a($hash, 'Enumerable')) $hash = $hash->array;
        return $this->new_instance(array_merge($this->array, $hash));
    }
    
    function update($hash) {
        if (is_a($hash, 'Enumerable')) $hash = $hash->array;
        $this->array = array_merge($this->array, $hash);
        return $this;
    }
    
}

class H extends Enumerable { }

extend('H', 'HashMethods');

?>