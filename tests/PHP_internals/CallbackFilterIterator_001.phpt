--TEST--
CallbackFilterIterator: Basic use case
--DESCRIPTION--
Before PHP 5.4 we had our own version of CallbackFilterIterator, as PHP
did not provide one. When we dropped support for PHP 5.3, we also did
drop our own version. This test case for it remains here though, so we
can confirm that the new PHP built-in works the same, and we do really
have a version of PHP that supports it.
--FILE--
<?php
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
