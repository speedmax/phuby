<?php

class Hash extends Enumerable { }

abstract class HashMethods {
    
    function invert() {
        return call_class_method($this->class, 'new_instance', array(array_flip($this->array)));
    }
    
    function merge($hash) {
        if ($hash instanceof Enumerable) $hash = $hash->array;
        return call_class_method($this->class, 'new_instance', array(array_merge($this->array, $hash)));
    }
    
    function shift() {
        return (empty($this->array)) ? $this->super() : new Arr(array($this->keys()->shift(), $this->super()));
    }
    
    function to_a() {
        return $this->inject(new Arr, '$object[] = new A(array($key, $value)); return $object;');
    }
    
    function update($hash) {
        if ($hash instanceof Enumerable) $hash = $hash->array;
        $this->array = array_merge($this->array, $hash);
        return $this;
    }
    
}

Hash::extend('HashMethods');

Hash::alias_method('flip', 'invert');