<?php

abstract class ArrayMethods {
    //
}

class A extends Enumerable {
    
    function offsetSet($offset, $value) {
        $this->super($this->count(), $value);
    }
    
}

extend('A', 'ArrayMethods');

?>