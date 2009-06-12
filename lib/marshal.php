<?php

class Marshal extends Object { }

abstract class MarshalMethods {
    
    function dump($object) {
        return serialize($object);
    }
    
    function load($data) {
        return unserialize($object);
    }
    
}

Marshal::extend('MarshalMethods');

Marshal::alias_method('restore', 'load');