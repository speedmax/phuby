<?php

if (!function_exists('get_called_class')) {
    function get_called_class() { 
        $backtrace = debug_backtrace();
        if (preg_match('/eval\(\)\'d code$/', $backtrace[1]['file'])) {
            return $backtrace[3]['args'][0];
        } else {
            $lines = file($backtrace[1]['file']);
            preg_match('/([a-zA-Z0-9\_]+)::'.$backtrace[1]['function'].'/', $lines[$backtrace[1]['line']-1], $matches);
            return $matches[1];
        }
    }
}

function build_function_call($function, $arguments = array(), $variable_name = 'arguments') {
    if (!is_array($function)) $function = array($function);
    if (is_object($function[0])) $function[0] = get_class($function[0]);
    return join('::', $function).'('.splat($arguments, $variable_name).')';
}

function &call_class_method($class, $method, $arguments = array()) {
    eval('$result = &'.build_function_call(array($class, $method), $arguments).';');
    return $result;
}

function evaluate_block($block, $binding = array()) {
    $parameters = array_merge(array_keys($binding), array($block));
    eval('$proc = '.build_function_call('proc', $parameters, 'parameters').';');
    return $proc->call_array(array_values($binding));
}

function get_class_variable($class, $variable) {
    return eval('return '.$class.'::$'.$variable.';');
}

function phuby_autoload($class) {
    $namespaces = split('::', $class);
    $file = '..'.DS.'lib';
    foreach ($namespaces as $namespace) {
        $file .= DS.strtolower(preg_replace('/[^A-Z^a-z^0-9]+/', '_', preg_replace('/([a-z\d])([A-Z])/', '\1_\2', preg_replace('/([A-Z]+)([A-Z][a-z])/', '\1_\2', $namespace))));
    }
    require_once $file.'.php';
}

function proc($block) {
    $arguments = func_get_args();
    return eval('return '.build_function_call('new Proc', $arguments).';');
}

function set_class_variable($class, $variable, $value) {
    eval($class.'::$'.$variable.' = $value;');
}

function splat($arguments, $variable_name = 'arguments') {
    $result = '';
    if (!empty($arguments)) {
        $result .= '$'.$variable_name.'[0]';
        for ($i = 1; $i < count($arguments); $i++) {
            $result .= ', $'.$variable_name.'['.$i.']';
        }
    }
    return $result;
}