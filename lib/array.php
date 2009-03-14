<?php

abstract class ArrayMethods {
    //
}

class A extends Enumerable {
    
    function offsetSet($offset, $value) {
        $this->super($this->count(), $value);
    }
    
    function unshift($value) {
        return array_unshift($this->array, $value);
    }
    
}

extend('A', 'ArrayMethods');

?>