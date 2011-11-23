--TEST--
CartesianProductIterator: Basic use
--FILE--
<?php
require_once("CartesianProductIterator.php");

$iterator = new CartesianProductIterator(new ArrayIterator(array('a', 'b', 'c')),
                                         new ArrayIterator(array(1, 2, 3)));

foreach($iterator as $key => $value)
  printf("%s => array(%s)\n",
         $key,
         implode(", ", $value));
?>
--EXPECT--
0 => array(a, 1)
1 => array(a, 2)
2 => array(a, 3)
3 => array(b, 1)
4 => array(b, 2)
5 => array(b, 3)
6 => array(c, 1)
7 => array(c, 2)
8 => array(c, 3)
