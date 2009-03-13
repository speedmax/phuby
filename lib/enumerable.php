<?php

class Enumerator extends Object implements Iterator, ArrayAccess, Countable {
    
    protected $array;
    protected $valid = false;
    
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

class EnumerableMethods {
    
    function all($block) {
        $failed = false;
        foreach ($this as $key => $value) {
            eval('if (!('.$block.')) $failed = true;');
            if ($failed) return false;
        }
        return true;
    }
    
    function any($block) {
        $passed = false;
        foreach ($this as $key => $value) {
            eval('if ('.$block.') $passed = true;');
            if ($passed) return true;
        }
        return false;
    }
    
    function includes($object) {
        return in_array($object, $this->array);
    }
    
    function none($block) {
        $failed = false;
        foreach ($this as $key => $value) {
            eval('if ('.$block.') $failed = true;');
            if ($failed) return false;
        }
        return true;
    }
    
    function partition($block) {
        $passed = new Enumerable;
        $failed = new Enumerable;
        foreach ($this as $key => $value) {
            eval('if ('.$block.') { $passed[] = $value; } else { $failed[] = $value; }');
        }
        return new Enumerable(array($passed, $failed));
    }
    
    function reject($block) {
        $result = new Enumerable;
        foreach ($this as $key => $value) {
            eval('if (!('.$block.')) $result[] = $value;');
        }
        return $result;
    }
    
    function select($block) {
        $result = new Enumerable;
        foreach ($this as $key => $value) {
            eval('if ('.$block.') $result[] = $value;');
        }
        return $result;
    }
    
    function sort($block = null) {
        return new Enumerable(sort($this->array()));
    }
    
    function to_a() {
        return $this->array;
    }
    
}

class Enumerable extends Enumerator { }

extend('Enumerable', 'EnumerableMethods');

?>