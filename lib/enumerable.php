<?php

class Enumerator extends Object implements Iterator, ArrayAccess, Countable {
    
    protected $array;
    protected $valid = false;
    
    public function initialize($array = array()) {
        $this->array = $array;
    }
    
    public function count() {
        return count($this->array);
    }
    
    public function current() {
        return current($this->array);
    }
    
    public function getIterator() {
        return $this;
    }
    
    public function key() {
        return key($this->array);
    }
    
    public function offsetExists($offset) {
        return isset($this->array[$offset]);
    }
    
    public function offsetGet($offset) {
        return $this->array[$offset];
    }
    
    public function offsetSet($offset, $value) {
        if (empty($offset)) $offset = $this->count();
        $this->array[$offset] = $value;
    }
    
    public function offsetUnset($offset) {
        unset($this->array[$offset]);
    }
    
    public function next() {
        $this->valid = (next($this->array) !== false);
    }
    
    public function rewind() {
        $this->valid = (reset($this->array) !== false);
    }
    
    public function valid() {
        return $this->valid;
    }
    
}

class EnumerableMethods {
    
    public function all($block) {
        $failed = false;
        foreach ($this as $key => $value) {
            eval('if (!('.$block.')) $failed = true;');
            if ($failed) return false;
        }
        return true;
    }
    
    public function any($block) {
        $passed = false;
        foreach ($this as $key => $value) {
            eval('if ('.$block.') $passed = true;');
            if ($passed) return true;
        }
        return false;
    }
    
    public function includes($object) {
        return in_array($object, $this->array);
    }
    
    public function none($block) {
        $failed = false;
        foreach ($this as $key => $value) {
            eval('if ('.$block.') $failed = true;');
            if ($failed) return false;
        }
        return true;
    }
    
    public function partition($block) {
        $passed = new Enumerable;
        $failed = new Enumerable;
        foreach ($this as $key => $value) {
            eval('if ('.$block.') { $passed[] = $value; } else { $failed[] = $value; }');
        }
        return new Enumerable(array($passed, $failed));
    }
    
    public function reject($block) {
        $result = new Enumerable;
        foreach ($this as $key => $value) {
            eval('if (!('.$block.')) $result[] = $value;');
        }
        return $result;
    }
    
    public function select($block) {
        $result = new Enumerable;
        foreach ($this as $key => $value) {
            eval('if ('.$block.') $result[] = $value;');
        }
        return $result;
    }
    
    public function sort($block = null) {
        return new Enumerable(sort($this->array()));
    }
    
    public function to_a() {
        return $this->array;
    }
    
}

class Enumerable extends Enumerator { }

extend('Enumerable', 'EnumerableMethods');

?>