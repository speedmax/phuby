<?php

abstract class ArrayMethods {
    
    function chunk($size) {
        if ($size < 1) {
            trigger_error('The first argument in '.$this->class.'::chunk() must be greater than 0', E_WARNING);
            return null;
        } else {
            $result = $this->new_instance();
            $index = 0;
            foreach ($this as $value) {
                if ($index++ % $size == 0) {
                    $result[] = $this->new_instance();
                    $result_index = $result->count() - 1;
                }
                $result[$result_index][] = $value;
            }
            return $result;
        }
    }
    
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
    
    function fill($start, $length, $value) {
        $this->array = array_fill($start, $length, $value);
        return $this;
    }
    
    function first() {
        return $this[0];
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
    
    function join($glue) {
        return join($glue, $this->array);
    }
    
    function last() {
        return $this[$this->count() - 1];
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
    
    function rand($quantity = 1) {
        return $this->new_instance(array_rand($this->array, $quantity));
    }
    
    function reverse() {
        return $this->new_instance(array_reverse($this->array));
    }
    
    function shift() {
        return array_shift($this->array);
    }
    
    function shuffle() {
        $array = $this->array;
        shuffle($array);
        return $this->new_instance($array);
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
        if (empty($offset)) $offset = $this->count();
        $this->super($offset, $value);
    }
    
    function unshift($value) {
        return array_unshift($this->array, $value);
    }
    
}

extend('A', 'ArrayMethods');
alias_method('A', 'implode', 'join');
alias_method('A', 'in_groups_of', 'chunk');
alias_method('A', 'uniq', 'unique');

?>