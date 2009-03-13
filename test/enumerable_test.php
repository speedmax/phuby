<?php

require_once '../phuby.php';

$e = new Enumerable;

$e[] = 'ing';
$e[] = 'cool';
$e[] = 'wow';

foreach ($e as $k => $v) {
    echo $k.' => '.$v."\n";
}
// echo $e['test'];

if ($e->any('$key == "ing"')) echo "true\n";
if ($e->any('$key == "invalid"')) echo "true\n";

?>