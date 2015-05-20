--TEST--
SplitCallbackIterator: Documentation example
--FILE--
<?php
require_once("SplitCallbackIterator.php");

$lines = new ArrayIterator(array("First",
                                 "and second line, and",
                                 "3rd in 1st paragraph.",
                                 "",
                                 "A 2nd paragraph.",
                                 "",
                                 "And the final one,",
                                 "again with two lines."));

$paragraphs = TestHelpers::pnewdebug("SplitCallbackIterator",
                                     $lines,
                                     function ($key, $line) {
                                       return $line == "";
                                     });

foreach($paragraphs as $paragraph) {
  print "<p>\n  ";
  foreach($paragraph as $line)
    print $line." ";
  print "\n<\\p>\n";
}
?>
--EXPECT--
new SplitCallbackIterator(object(ArrayIterator), object(Closure)) returns object(SplitCallbackIterator)
<p>
  First and second line, and 3rd in 1st paragraph.  
<\p>
<p>
  A 2nd paragraph.  
<\p>
<p>
  And the final one, again with two lines. 
<\p>