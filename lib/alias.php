<?php

abstract class AliasMethods {
    
    function alias_method($new_name, $old_name) {
        extend_method($this, get_class($this), $old_name, $new_name);
    }
    
    function alias_method_chain($method, $with) {
        $this->instance_extended_methods[$method.'_without_'.$with] = $this->instance_extended_methods[$method];
        unset($this->instance_extended_methods[$method]);
        extend_method($this, get_class($this), $method.'_with_'.$with, $method);
    }
    
}

extend('Object', 'AliasMethods');

?>