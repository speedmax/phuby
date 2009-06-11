<?php

class Struct extends Object { }

abstract class StructMethods {
    
    public $members = array();
    
    function initialize($members) {
        if (!is_array($members)) $members = func_get_args();
        $this->members = $members;
    }
    
    function count() {
        return count($this->members);
    }
    
    function instance($values) {
        $instance = call_class_method($this->class, 'new_instance', array($this->members));
        $values = func_get_args();
        foreach ($this->members as $key => $member) $instance->$member = $values[$key];
        return $instance;
    }
    
    function select($block) {
        return $this->to_a()->select($block);
    }
    
    function to_a() {
        $result = new Arr;
        foreach ($this->members as $member) $result[] = $this->$member;
        return $result;
    }
    
    function to_h() {
        $result = new Hash;
        foreach ($this->members as $member) $result[$member] = $this->$member;
        return $result;
    }
    
    function values_at($keys) {
        $keys = func_get_args();
        return $this->to_a()->call('values_at', $keys);
    }
    
}

Struct::extend('StructMethods');

Struct::alias_method('length', 'count');
Struct::alias_method('size', 'count');
Struct::alias_method('values', 'to_a');