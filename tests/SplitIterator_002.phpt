--TEST--
SplitIterator: Documentation example
--FILE--
<?php
require_once("SplitIterator.php");

// Inherit from SplitIterator
class SplitAtKeyDivisibleByThree extends SplitIterator {
  
  // Just add the split decision: It gets called with each
  // item's key and value -- a split takes place if this
  // method returns TRUE
  public function needsSplit($key, $item) {
    // Divide the key by three (modulus), and if there's no
    // remainder, split!
    return $key % 3 == 0;
  }
}

$origIterator = new ArrayIterator(array_combine(range(1, 26), range('a', 'z')));

$splitter = new SplitAtKeyDivisibleByThree($origIterator);

foreach($splitter as $group) {
  print "'";
  foreach($group as $letter) {
    print $letter;
  }
  print "'\n";
}

?>
--EXPECT--
'abc'
'def'
'ghi'
'jkl'
'mno'
'pqr'
'stu'
'vwx'
'yz'
