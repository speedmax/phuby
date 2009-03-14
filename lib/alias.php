<?php

abstract class AliasMethods {
    
    function alias_method($new_name, $old_name) {
        extend_method($this, get_class($this), $old_name, $new_name);
    }
    
}

extend('Object', 'AliasMethods');

?>