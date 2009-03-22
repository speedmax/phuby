<?php
require 'spec_helper.php';

class Describe_Enumerable_Array extends SimpleSpec {
    
    function should_instantiate_a_array_object() {
        $e = new A(array('ing', 'cool', 'wow'));
        expects( $e )->should_be_a('A');
        expects( $e )->should_be_a('Enumerable');
        expects( $e )->should_be_a('Object');
        
        expects($e->array)->should_be(array('ing', 'cool', 'wow'));
    }
    
    function should_be_iteratable_with_foreach() {
        $e = a('ing', 'cool', 'wow');
        $results = array();
        foreach ($e as $k => $v) {
            $results[] = $v;
        }
        expects($results)->should_be(array('ing', 'cool', 'wow'));
    }
    
    function should_provide_functional_iterators() {
        $e = a('ing', 'cool', 'wow');
        expects($e->any('$value == "ing";'))->should_be(true);
        expects($e->any('$value == "invalid";'))->should_be(false);
        
        expects($e->map('strlen($value);')->array)->should_be(array(3,4,3));
        
        $rs = $e->reduce('', '$object .= $value;$object;');
        expects($rs)->should_be('ingcoolwow');

    }
    
    function should_provide_enumeration_methods() {
        # Enumerable#sort uses asort which maintain index association, make sense here?
        expects(a(3,2,1)->sort()->array)->should_be(array(1,2,3));
        
        $e = a(1, 2, 3, a(4, 5, 6, a(7, 8, 9)));
        expects($e->flatten()->array)->should_be(array(1,2,3,4,5,6,7,8,9));
    }
}

