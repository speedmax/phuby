<?php

require_once '../phuby.php';

$e = new A;

$e[] = 'ing';
$e[] = 'cool';
$e[] = 'wow';

echo $e[99];
$e->default = 'TEST DEFAULT';
echo $e[99]."\n";

foreach ($e as $k => $v) {
    echo $k.' => '.$v."\n";
}

echo "***COLLECT***\n";
print_r($e->collect('return $key;')->array);

echo "***ALIAS METHOD MAP***\n";
print_r($e->map('return $key;')->array);

if ($e->any('return $key == 1;')) echo "true\n";
if ($e->any('return $key == 4;')) echo "true\n";

echo "***INJECT***\n";
print_r($e->inject(array(), '$object["injected_$key"] = $value; return $object;'));

$e = new H;
$e['short'] = 4;
$e['this is a longer one'] = 12;
$e['this is long'] = 2;

echo "***ARRAY***\n";
print_r($e->array);

echo "***SORT***\n";
print_r($e->sort()->array);

echo "***SORT_BY***\n";
print_r($e->sort_by('return strlen($key);')->array);

?>