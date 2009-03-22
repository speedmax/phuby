<?php

abstract class HashMethods {
    
    function invert() {
        return $this->new_instance(array_flip($this->array));
    }
    
    function merge($hash) {
        if ($hash instanceof Enumerable) $hash = $hash->array;
        return $this->new_instance(array_merge($this->array, $hash));
    }
    
    function shift() {
        return (empty($this->array)) ? $this->super() : new A(array($this->keys()->shift(), $this->super()));
    }
    
    function to_a() {
        return $this->inject(new A, '$object[] = new A(array($key, $value)); return $object;');
    }
    
    function update($hash) {
        if ($hash instanceof Enumerable) $hash = $hash->array;
        $this->array = array_merge($this->array, $hash);
        return $this;
    }
    
}

class H extends Enumerable {
    static $extended_methods = array();
    static $extended_parents = array();
    static $extended_properties = array();
}

extend('H', 'HashMethods');
alias_method('H', 'flip', 'invert');

?>