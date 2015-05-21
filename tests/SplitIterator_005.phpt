--TEST--
SplitIterator: More "out-of-sequence" behaviour
--FILE--
<?php
require_once("SplitIterator.php");
require_once("test_inc/SplitIterator_inc.php");

$splitter = TestHelpers::pnewdebug("SplitIfDivisibleIterator",
                                   new ArrayIterator(range(1, 10)),
                                   3);
print "\n";
TestHelpers::pcatchdebug($splitter, "valid");
TestHelpers::pcatchdebug($splitter, "key");
TestHelpers::pcatchdebug($splitter, "current");
print "\n";
TestHelpers::pcatchdebug($splitter, "rewind");
print "\n";
TestHelpers::pcatchdebug($splitter, "valid");
TestHelpers::pcatchdebug($splitter, "key");
$inner = TestHelpers::pcatchdebug($splitter, "current");
print "\n";
print "TESTING INNER ITERATOR:\n";
print "\n";
TestHelpers::pcatchdebug($inner, "valid");
TestHelpers::pcatchdebug($inner, "key");
TestHelpers::pcatchdebug($inner, "current");
print "\n";
TestHelpers::pcatchdebug($inner, "next");
print "\n";
TestHelpers::pcatchdebug($inner, "valid");
TestHelpers::pcatchdebug($inner, "key");
TestHelpers::pcatchdebug($inner, "current");
print "\n";
print "rewind() MUST BE IGNORED:\n";
print "\n";
TestHelpers::pcatchdebug($inner, "rewind");
print "\n";
TestHelpers::pcatchdebug($inner, "valid");
TestHelpers::pcatchdebug($inner, "key");
TestHelpers::pcatchdebug($inner, "current");
print "\n";
print "GET INNER ITERATOR AGAIN AND EVERYTHING SHOULD STILL BE IN SAME STATE:\n";
print "\n";
TestHelpers::pcatchdebug($splitter, "valid");
TestHelpers::pcatchdebug($splitter, "key");
$inner = TestHelpers::pcatchdebug($splitter, "current");
print "\n";
TestHelpers::pcatchdebug($inner, "valid");
TestHelpers::pcatchdebug($inner, "key");
TestHelpers::pcatchdebug($inner, "current");
print "\n";
print "ADVANCE OUTER ITERATOR (PREMATURELY):\n";
print "\n";
TestHelpers::pcatchdebug($splitter, "next");
print "\n";
TestHelpers::pcatchdebug($splitter, "valid");
TestHelpers::pcatchdebug($splitter, "key");
$inner = TestHelpers::pcatchdebug($splitter, "current");
print "\n";
TestHelpers::pcatchdebug($inner, "valid");
TestHelpers::pcatchdebug($inner, "key");
TestHelpers::pcatchdebug($inner, "current");
print "\n";
print "REWIND OUTER ITERATOR (PREMATURELY):\n";
print "\n";
TestHelpers::pcatchdebug($splitter, "rewind");
print "\n";
print "OLD INNER ITERATOR SHOULD BE INVALIDATED:\n";
print "\n";
TestHelpers::pcatchdebug($inner, "valid");
TestHelpers::pcatchdebug($inner, "key");
TestHelpers::pcatchdebug($inner, "current");
print "\n";
print "BUT A NEW ONE PROPERLY AVAILABLE AT THE BEGINNING:\n";
print "\n";
TestHelpers::pcatchdebug($splitter, "valid");
TestHelpers::pcatchdebug($splitter, "key");
$inner = TestHelpers::pcatchdebug($splitter, "current");
print "\n";
TestHelpers::pcatchdebug($inner, "valid");
TestHelpers::pcatchdebug($inner, "key");
TestHelpers::pcatchdebug($inner, "current");

?>
--EXPECT--
new SplitIfDivisibleIterator(object(ArrayIterator), '3') returns object(SplitIfDivisibleIterator)

SplitIfDivisibleIterator->valid() returns FALSE
SplitIfDivisibleIterator->key() returns '0'
SplitIfDivisibleIterator->current() returns NULL

SplitIfDivisibleIterator->rewind() returns NULL

SplitIfDivisibleIterator->valid() returns TRUE
SplitIfDivisibleIterator->key() returns '0'
SplitIfDivisibleIterator->current() returns object(SplitInnerIterator)

TESTING INNER ITERATOR:

SplitInnerIterator->valid() returns TRUE
SplitInnerIterator->key() returns '0'
SplitInnerIterator->current() returns '1'

SplitInnerIterator->next() returns NULL

SplitInnerIterator->valid() returns TRUE
SplitInnerIterator->key() returns '1'
SplitInnerIterator->current() returns '2'

rewind() MUST BE IGNORED:

SplitInnerIterator->rewind() returns NULL

SplitInnerIterator->valid() returns TRUE
SplitInnerIterator->key() returns '1'
SplitInnerIterator->current() returns '2'

GET INNER ITERATOR AGAIN AND EVERYTHING SHOULD STILL BE IN SAME STATE:

SplitIfDivisibleIterator->valid() returns TRUE
SplitIfDivisibleIterator->key() returns '0'
SplitIfDivisibleIterator->current() returns object(SplitInnerIterator)

SplitInnerIterator->valid() returns TRUE
SplitInnerIterator->key() returns '1'
SplitInnerIterator->current() returns '2'

ADVANCE OUTER ITERATOR (PREMATURELY):

SplitIfDivisibleIterator->next() returns NULL

SplitIfDivisibleIterator->valid() returns TRUE
SplitIfDivisibleIterator->key() returns '1'
SplitIfDivisibleIterator->current() returns object(SplitInnerIterator)

SplitInnerIterator->valid() returns TRUE
SplitInnerIterator->key() returns '3'
SplitInnerIterator->current() returns '4'

REWIND OUTER ITERATOR (PREMATURELY):

SplitIfDivisibleIterator->rewind() returns NULL

OLD INNER ITERATOR SHOULD BE INVALIDATED:

SplitInnerIterator->valid() returns FALSE
SplitInnerIterator->key() returns NULL
SplitInnerIterator->current() returns NULL

BUT A NEW ONE PROPERLY AVAILABLE AT THE BEGINNING:

SplitIfDivisibleIterator->valid() returns TRUE
SplitIfDivisibleIterator->key() returns '0'
SplitIfDivisibleIterator->current() returns object(SplitInnerIterator)

SplitInnerIterator->valid() returns TRUE
SplitInnerIterator->key() returns '0'
SplitInnerIterator->current() returns '1'