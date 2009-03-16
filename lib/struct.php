<?php

class Struct extends Object {
    
    public $members;
    
    function initialize($members) {
        if (!is_array($members)) $members = func_get_args();
        $this->members = $members;
    }
    
    function new_instance($values) {
        $instance = $this->super($this->members);
        $values = func_get_args();
        foreach ($this->members as $key => $member) extend_property($instance, $member, $values[$key]);
        return $instance;
    }
    
}

?>