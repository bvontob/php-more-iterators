--TEST--
SplitIterator: Basic test
--FILE--
<?php
require_once("SplitIterator.php");

class SplitAtModulusIterator extends SplitIterator {
  private $modulus;

  public function __construct(Traversable $iterator, $modulus) {
    $this->modulus = (int)$modulus;
    parent::__construct($iterator);
  }

  public function needsSplit($key, $value) {
    return $value % $this->modulus == 0;
  }
}

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
  $it = TestHelpers::pnewdebug('SplitAtModulusIterator',
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
new SplitAtModulusIterator(object(ArrayIterator), '3') returns object(SplitAtModulusIterator)
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

new SplitAtModulusIterator(object(ArrayIterator), '5') returns object(SplitAtModulusIterator)
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

new SplitAtModulusIterator(object(ArrayIterator), '999') returns object(SplitAtModulusIterator)
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

new SplitAtModulusIterator(object(ArrayIterator), '5') returns object(SplitAtModulusIterator)
Done.

new SplitAtModulusIterator(object(ArrayIterator), '1') returns object(SplitAtModulusIterator)
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
