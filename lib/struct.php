<?php

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
        $instance = $this->new_instance($this->members);
        $values = func_get_args();
        foreach ($this->members as $key => $member) extend_property($instance, $member, $values[$key]);
        return $instance;
    }
    
    function select($block) {
        return $this->to_a()->select($block);
    }
    
    function to_a() {
        $result = new A;
        foreach ($this->members as $member) $result[] = $this->$member;
        return $result;
    }
    
    function to_h() {
        $result = new H;
        foreach ($this->members as $member) $result[$member] = $this->$member;
        return $result;
    }
    
    function values_at($keys) {
        $keys = func_get_args();
        return $this->to_a()->call('values_at', $keys);
    }
    
}

class Struct extends Object { }

extend('Struct', 'StructMethods');
alias_method('Struct', 'length', 'count');
alias_method('Struct', 'size', 'count');
alias_method('Struct', 'values', 'to_a');

?>