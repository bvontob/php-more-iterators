--TEST--
SplitIterator: Basic test
--FILE--
<?php
require_once("SplitIterator.php");
require_once("test_inc/SplitIterator_inc.php");

foreach(array(array('range'   => range(1, 10),
                    'modulus' => 3),
              array('range'   => range(1, 10),
                    'modulus' => 5),
              array('range'   => range(1, 10),
                    'modulus' => 999),
              array('range'   => array(),
                    'modulus' => 5),
              array('range'   => range(1, 10),
                    'modulus' => 1),
              ) as $test) {
  $it = TestHelpers::pnewdebug('SplitIfDivisibleIterator',
                               new ArrayIterator($test['range']),
                               $test['modulus']);
  foreach($it as $outerKey => $split) {
    printf("  New split iterator %s => %s:\n",
           TestHelpers::valdebug($outerKey),
           TestHelpers::valdebug($split));
    foreach($split as $key => $entry) {
      printf("    %s => %s\n",
             TestHelpers::valdebug($key),
             TestHelpers::valdebug($entry));
    }
  }
  print "Done.\n\n";
}
?>
--EXPECT--
new SplitIfDivisibleIterator(object(ArrayIterator), '3') returns object(SplitIfDivisibleIterator)
  New split iterator '0' => object(SplitInnerIterator):
    '0' => '1'
    '1' => '2'
    '2' => '3'
  New split iterator '1' => object(SplitInnerIterator):
    '3' => '4'
    '4' => '5'
    '5' => '6'
  New split iterator '2' => object(SplitInnerIterator):
    '6' => '7'
    '7' => '8'
    '8' => '9'
  New split iterator '3' => object(SplitInnerIterator):
    '9' => '10'
Done.

new SplitIfDivisibleIterator(object(ArrayIterator), '5') returns object(SplitIfDivisibleIterator)
  New split iterator '0' => object(SplitInnerIterator):
    '0' => '1'
    '1' => '2'
    '2' => '3'
    '3' => '4'
    '4' => '5'
  New split iterator '1' => object(SplitInnerIterator):
    '5' => '6'
    '6' => '7'
    '7' => '8'
    '8' => '9'
    '9' => '10'
Done.

new SplitIfDivisibleIterator(object(ArrayIterator), '999') returns object(SplitIfDivisibleIterator)
  New split iterator '0' => object(SplitInnerIterator):
    '0' => '1'
    '1' => '2'
    '2' => '3'
    '3' => '4'
    '4' => '5'
    '5' => '6'
    '6' => '7'
    '7' => '8'
    '8' => '9'
    '9' => '10'
Done.

new SplitIfDivisibleIterator(object(ArrayIterator), '5') returns object(SplitIfDivisibleIterator)
Done.

new SplitIfDivisibleIterator(object(ArrayIterator), '1') returns object(SplitIfDivisibleIterator)
  New split iterator '0' => object(SplitInnerIterator):
    '0' => '1'
  New split iterator '1' => object(SplitInnerIterator):
    '1' => '2'
  New split iterator '2' => object(SplitInnerIterator):
    '2' => '3'
  New split iterator '3' => object(SplitInnerIterator):
    '3' => '4'
  New split iterator '4' => object(SplitInnerIterator):
    '4' => '5'
  New split iterator '5' => object(SplitInnerIterator):
    '5' => '6'
  New split iterator '6' => object(SplitInnerIterator):
    '6' => '7'
  New split iterator '7' => object(SplitInnerIterator):
    '7' => '8'
  New split iterator '8' => object(SplitInnerIterator):
    '8' => '9'
  New split iterator '9' => object(SplitInnerIterator):
    '9' => '10'
Done.
