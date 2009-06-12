<?php
require 'spec_helper.php';

class Describe_Enumerable_Array extends SimpleSpec {
    
    function should_instantiate_a_array_object() {
        $e = new Arr(array('ing', 'cool', 'wow'));
        expect( $e )->should_be_a('Arr');
        expect( $e )->should_be_a('Enumerable');
        expect( $e )->should_be_a('Object');
        
        expect($e->array)->should_be(array('ing', 'cool', 'wow'));
    }
    
    function should_be_iteratable_with_foreach() {
        $e = a('ing', 'cool', 'wow');
        $results = array();
        foreach ($e as $k => $v) {
            $results[] = $v;
        }
        expect($results)->should_be(array('ing', 'cool', 'wow'));
    }
    
    function should_provide_functional_iterators() {
        $e = a('ing', 'cool', 'wow');
        
        expect($e->any('$value == "ing";'))->should_be(true);
        expect($e->any('$value == "invalid";'))->should_be(false);
        
        expect($e->map('strlen($value);')->array)->should_be(array(3,4,3));
        
        $rs = $e->inject('', '$object .= $value;$object;');
        expect($rs)->should_be('ingcoolwow');

    }
    
    function should_provide_enumeration_methods() {
        //FIXME: uses asort which maintain index association, make sense here?
        # Enumerable#sort 
        expect(array_values(a(3,2,1)->sort()->array))->should_be(array(1,2,3));
        
        $e = a(1, 2, 3, a(4, 5, 6, a(7, 8, 9)));
        
        expect($e->flatten()->array)->should_be(array(1,2,3,4,5,6,7,8,9));
    }
}

