<?php

class Enumerator extends Object implements Iterator, ArrayAccess, Countable {
    
    public $array;
    public $valid = false;
    
    function initialize($array = array()) {
        $this->array = $array;
    }
    
    function count() {
        return count($this->array);
    }
    
    function current() {
        return current($this->array);
    }
    
    function getIterator() {
        return $this;
    }
    
    function key() {
        return key($this->array);
    }
    
    function offsetExists($offset) {
        return isset($this->array[$offset]);
    }
    
    function offsetGet($offset) {
        return $this->array[$offset];
    }
    
    function offsetSet($offset, $value) {
        if (empty($offset)) $offset = $this->count();
        $this->array[$offset] = $value;
    }
    
    function offsetUnset($offset) {
        unset($this->array[$offset]);
    }
    
    function next() {
        $this->valid = (next($this->array) !== false);
    }
    
    function rewind() {
        $this->valid = (reset($this->array) !== false);
    }
    
    function valid() {
        return $this->valid;
    }

}

abstract class EnumerableMethods {
    
    function all($block) {
        foreach ($this as $key => $value) if (!evaluate_block($block, get_defined_vars())) return false;
        return true;
    }
    
    function any($block) {
        foreach ($this as $key => $value) if (evaluate_block($block, get_defined_vars())) return true;
        return false;
    }
    
    function collect($block) {
        $result = new Enumerable;
        foreach ($this as $key => $value) $result[] = evaluate_block($block, get_defined_vars());
        return $result;
    }
    
    function includes($object) {
        return in_array($object, $this->to_a());
    }
    
    function inject($object, $block) {
        foreach ($this as $key => $value) $object = evaluate_block($block, get_defined_vars());
        return $object;
    }
    
    function none($block) {
        foreach ($this as $key => $value) if (evaluate_block($block, get_defined_vars())) return false;
        return true;
    }
    
    function partition($block) {
        $passed = new Enumerable;
        $failed = new Enumerable;
        foreach ($this as $key => $value) {
            if (evaluate_block($block, get_defined_vars())) {
                $passed[] = $value;
            } else {
                $failed[] = $value;
            }
        }
        return new Enumerable(array($passed, $failed));
    }
    
    function reject($block) {
        $result = new Enumerable;
        foreach ($this as $key => $value) if (!evaluate_block($block, get_defined_vars())) $result[] = $value;
        return $result;
    }
    
    function select($block) {
        $result = new Enumerable;
        foreach ($this as $key => $value) if (evaluate_block($block, get_defined_vars())) $result[] = $value;
        return $result;
    }
    
    function sort($sort_flags = null) {
        if (is_null($sort_flags)) $sort_flags = SORT_REGULAR;
        $array = $this->to_a();
        asort($array, $sort_flags);
        return new Enumerable($array);
    }
    
    function sort_by($block, $sort_flags = null) {
        $sorted = $this->inject(new Enumerable, '$object[$key] = evaluate_block(\''.$block.'\', get_defined_vars()); return $object;')->sort($sort_flags);
        $result = new Enumerable;
        foreach ($sorted as $key => $value) $result[$key] = $this[$key];
        return $result;
    }
    
    function to_a() {
        return $this->array;
    }
    
}

class Enumerable extends Enumerator { }

extend('Enumerable', 'EnumerableMethods');

?>