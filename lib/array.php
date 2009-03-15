<?php

abstract class ArrayMethods {
    
    function concat($arrays) {
        $arrays = func_get_args();
        foreach ($arrays as $array) {
            if ($array instanceof Enumerable) $array = $array->array;
            foreach ($array as $value) $this[] = $value;
        }
        return $this;
    }
    
    function compact() {
        return $this->reject('$value == null');
    }
    
    function flatten() {
        $result = $this->new_instance();
        foreach ($this as $value) {
            if (is_array($value)) $value = new A($value);
            if ($value instanceof A) {
                foreach ($value->flatten() as $flattened_value) $result[] = $flattened_value;
            } else {
                $result[] = $value;
            }
        }
        return $result;
    }
    
    function pack($format) {
        $arguments = array_merge(array($format), $this->array);
        return eval('return '.build_function_call('pack', $arguments).';');
    }
    
    function pop() {
        return array_pop($this->array);
    }
    
    function push($arguments) {
        $arguments = func_get_args();
        foreach ($arguments as $argument) $this[] = $argument;
        return $this;
    }
    
    function reverse() {
        return $this->new_instance(array_reverse($this->array));
    }
    
    function shift() {
        return array_shift($this->array);
    }
    
    function unique() {
        return $this->new_instance(array_unique($this->array));
    }
    
    function unshift($arguments) {
        $arguments = func_get_args();
        array_shift($arguments, &$this->array);
        return eval('return '.build_function_call('array_unshift', $arguments).';');
    }
    
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
alias_method('A', 'uniq', 'unique');

?>