<?php

function extend($object, $classes) {
    if (!is_array($classes)) {
        $classes = func_get_args();
        $object = array_shift($classes);
    }
    
    foreach (array_unique($classes) as $class) {
        $class_name = (is_object($class)) ? get_class($class) : $class;
        if (!class_exists($class_name)) trigger_error('Undefined class '.$class_name, E_USER_ERROR);
        
        $methods = get_class_methods($class_name);
        foreach ($methods as $method) extend_method($object, $class, $method);
        
        $properties = (is_object($class)) ? get_object_vars($class) : get_class_vars($class);
        foreach ($properties as $property => $value) extend_property($object, $property, $value);
        
        if (is_object($object)) {
            $object->instance_extended_parents[] = $class_name;
        } else {
            set_static_property($object, 'extended_parents', array_merge(get_static_property($object, 'extended_parents'), array($class_name)));
        }
        
        $arguments = array($object);
        if (method_exists($class_name, 'extended')) eval(build_static_method_call($class_name, 'extended', $arguments).';');
    }
}

function extend_method($object, $class, $method) {
    $class_name = (is_object($class)) ? get_class($class) : $class;
    if (is_object($object)) {
        if (!isset($object->instance_extended_methods[$method])) $object->instance_extended_methods[$method] = array();
        $object->instance_extended_methods[$method][] = $class_name;
    } else {
        $extended_methods = get_static_property($object, 'extended_methods');
        if (!isset($extended_methods[$method])) $extended_methods[$method] = array();
        $extended_methods[$method][] = $class_name;
        set_static_property($object, 'extended_methods', $extended_methods);
    }
}

function extend_property($object, $property, $value) {
    if (is_object($object)) {
        $object->instance_extended_properties[$property] = $value;
    } else {
        $class_name = (is_object($object)) ? get_class($object) : $object;
        set_static_property($class_name, 'extended_properties', array_merge(get_static_property($class_name, 'extended_properties'), array($property => $value)));
    }
}

function build_static_method_call($class, $method, $arguments = array(), $variable_name = 'arguments') {
    if (is_object($class)) $class = get_class($class);
    $method_call = $class.'::'.$method.'(';
    if (!empty($arguments)) {
        $method_call .= '$'.$variable_name.'[0]';
        for ($i = 1; $i < count($arguments); $i++) {
            $method_call .= ', $'.$variable_name.'['.$i.']';
        }
    }
    return $method_call .= ')';
}

function get_static_property($class, $property) {
    return eval('return '.$class.'::$'.$property.';');
}

function set_static_property($class, $property, $value) {
    eval($class.'::$'.$property.' = $value;');
}

?>