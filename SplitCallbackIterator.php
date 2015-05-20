<?php
require_once("SplitIterator.php");

/**
 * An {@see IteratorIterator} that splits another iterator into
 * multiple iterators based on the decision of a callback.
 *
 * This class is a concrete implementation of a {@see SplitIterator}
 * that only differs in the way the decision on the split is called:
 * While a {@see SplitIterator} needs to be subclassed, this version
 * takes a callback function from the outside.
 *
 * Otherwise the documentation for {@see SplitIterator} applies.
 *
 * A code example that splits a text into paragraphs at empty lines
 * and formats them as simple HTML:
 *
 *   <code>
 *     // The input is an Iterator over text lines, with
 *     // empty lines as markers for paragraph boundaries
 *     $lines = new ArrayIterator(array(
 *         "This is the",
 *         "first paragraph.",
 *         "",
 *         "And this is",
 *         "the second",
 *         "one."
 *     ));
 *     
 *     // We just need a function that returns TRUE for empty lines
 *     $split = function ($key, $line) { return $line == ""; };
 *
 *     // And apply this function to split up the original Iterator
 *     // into multiple iterators (one per paragraph)
 *     $paragraphs = new SplitCallbackIterator($lines, $split);
 *     
 *     // Now we can iterate over paragraphs and their lines
 *     // already in two nested loops
 *     foreach($paragraphs as $paragraph) {
 *       print "<p>";
 *       foreach($paragraph as $line)
 *         print $line." ";
 *       print "<\\p>";
 *     }
 *   </code>
 *
 * @author     Beat Vontobel
 * @since      2015-05-20
 * @package    MeteoNews\phplib
 * @subpackage Iterators
 *
 * @see SplitIterator
 */
class SplitCallbackIterator extends SplitIterator {
  private $needsSplitCallback;

  /**
   * Create a new splitting iterator based on an inner iterator and a
   * callback for the split decision.
   *
   * The callback function gets called for every item in the inner
   * iterator, with each item's key and value as the two arguments. So
   * an example callback could look as follows:
   *
   *   <code>
   *     function needsSplit($key, $currentItem) {
   *       return ... ? TRUE : FALSE;
   *     }
   *   </code>
   *
   * If the callback returns TRUE, a split is initiated for the given
   * item.
   *
   * @param Traversable The iterator to be split into multiple
   *     iterators.
   *
   * @param callable The callback for the split decision.
   */
  public function __construct(Traversable $innerIterator, callable $needsSplitCallback) {
    $this->needsSplitCallback = $needsSplitCallback;
    parent::__construct($innerIterator);
  }

  final public function needsSplit($key, $value) {
    $callback = $this->needsSplitCallback;
    return $callback($key, $value);
  }
}
?>