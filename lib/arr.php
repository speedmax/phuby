<?php

class Arr extends Enumerable {
    
    function offsetSet($offset, $value) {
        if (empty($offset)) $offset = $this->count();
        $this->super($offset, $value);
    }
    
    function unshift($value) {
        return array_unshift($this->array, $value);
    }
    
}

abstract class ArrMethods {
    
    function assoc($object) {
        foreach ($this as $value) if ((is_array($value) || $value instanceof Arr) && $value[0] == $object) return $value;
        return null;
    }
    
    function chunk($size) {
        if ($size < 1) {
            trigger_error('The first argument in '.$this->class.'::chunk() must be greater than 0', E_WARNING);
            return null;
        } else {
            $result = call_class_method($this->class, 'new_instance');
            $index = 0;
            foreach ($this as $value) {
                if ($index++ % $size == 0) {
                    $result[] = call_class_method($this->class, 'new_instance');
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
        $result = call_class_method($this->class, 'new_instance');
        foreach ($this as $value) {
            if (is_array($value)) $value = new Arr($value);
            if ($value instanceof Arr) {
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
        return call_class_method($this->class, 'new_instance', array(array_rand($this->array, $quantity)));
    }
    
    function rassoc($object) {
        foreach ($this as $value) if ((is_array($value) || $value instanceof Arr) && $value[1] == $object) return $value;
        return null;
    }
    
    function reverse() {
        return call_class_method($this->class, 'new_instance', array(array_reverse($this->array)));
    }
    
    function shift() {
        return array_shift($this->array);
    }
    
    function shuffle() {
        $array = $this->array;
        shuffle($array);
        return call_class_method($this->class, 'new_instance', array($array));
    }
    
    function slice($offset, $length) {
        return call_class_method($this->class, 'new_instance', array(array_slice($this->array, $offset, $length)));
    }
    
    function splice($offset, $length = 0, $replacement = array()) {
        array_splice($this->array, $offset, $length, $replacement);
        return $this;
    }
    
    function to_h() {
        return $this->chunk(2)->inject(new Hash, '$object[$value[0]] = $value[1]; return $object;');
    }
    
    function transpose() {
        $size = $this->count();
    }
    
    function unique() {
        return call_class_method($this->class, 'new_instance', array(array_unique($this->array)));
    }
    
    function unshift($arguments) {
        $arguments = func_get_args();
        array_shift($arguments, &$this->array);
        return eval('return '.build_function_call('array_unshift', $arguments).';');
    }
    
}

Arr::extend('ArrMethods');

Arr::alias_method('implode', 'join');
Arr::alias_method('in_groups_of', 'chunk');
Arr::alias_method('uniq', 'unique');

# Convenience function
function a () {
  $args = func_get_args();
  return new Arr($args);
}