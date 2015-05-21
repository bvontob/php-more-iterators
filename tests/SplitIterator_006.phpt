--TEST--
SplitIterator: Empty iterator
--FILE--
<?php
require_once("SplitIterator.php");
require_once("test_inc/SplitIterator_inc.php");

$splitter = TestHelpers::pnewdebug("SplitIfDivisibleIterator",
                                   new ArrayIterator(array()),
                                   3);
print "\n";
TestHelpers::pcatchdebug($splitter, "valid");
TestHelpers::pcatchdebug($splitter, "key");
$inner = TestHelpers::pcatchdebug($splitter, "current");
print "\n";
TestHelpers::pcatchdebug($splitter, "rewind");
print "\n";
TestHelpers::pcatchdebug($splitter, "valid");
TestHelpers::pcatchdebug($splitter, "key");
$inner = TestHelpers::pcatchdebug($splitter, "current");
print "\n";
TestHelpers::pcatchdebug($splitter, "next");
print "\n";
TestHelpers::pcatchdebug($splitter, "valid");
TestHelpers::pcatchdebug($splitter, "key");
$inner = TestHelpers::pcatchdebug($splitter, "current");

?>
--EXPECT--
new SplitIfDivisibleIterator(object(ArrayIterator), '3') returns object(SplitIfDivisibleIterator)

SplitIfDivisibleIterator->valid() returns FALSE
SplitIfDivisibleIterator->key() returns '0'
SplitIfDivisibleIterator->current() returns NULL

SplitIfDivisibleIterator->rewind() returns NULL

SplitIfDivisibleIterator->valid() returns FALSE
SplitIfDivisibleIterator->key() returns '0'
SplitIfDivisibleIterator->current() returns NULL

SplitIfDivisibleIterator->next() returns NULL

SplitIfDivisibleIterator->valid() returns FALSE
SplitIfDivisibleIterator->key() returns '0'
SplitIfDivisibleIterator->current() returns NULL