--TEST--
CallbackFilterIterator: Basic use case
--FILE--
<?php
require_once("CallbackFilterIterator.php");

$innerIterator = new ArrayIterator(array('a' => "one",
                                         'B' => "two",
                                         'c' => "three"));

function rejectTwo($value, $key, $iterator) {
  return !($value == "two" || $value == 2);
}

foreach(new CallbackFilterIterator($innerIterator, 'rejectTwo')
        as $newValue)
  print "$newValue\n";

?>
--EXPECT--
one
three
