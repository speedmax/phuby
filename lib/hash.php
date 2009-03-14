<?php

abstract class HashMethods {
    
    function merge($hash) {
        if (is_a($hash, 'H')) $hash = $hash->array;
        return $this->new_instance(array_merge($this->array, $hash));
    }
    
    function update($hash) {
        if (is_a($hash, 'H')) $hash = $hash->array;
        $this->array = array_merge($this->array, $hash);
        return $this;
    }
    
}

class H extends Enumerable { }

extend('H', 'HashMethods');

?>