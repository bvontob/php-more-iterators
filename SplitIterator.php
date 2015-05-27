<?php
require_once("SplitIterator/SplitInnerIterator.php");

/**
 * An {@see IteratorIterator} that splits another iterator ({@see
 * Traversable}) into multiple iterators.
 *
 * This is a template (an abstract base class) for generic splitting
 * iterators: You just need to subclass it and implement the method
 * {@see needsSplit()} that decides on where the inner iterator should
 * be split up.
 *
 * See {@see SplitCallbackIterator} for an alternative implementation,
 * in case you don't want to subclass, but rather just hand in a
 * callback to do the split decision in a ready-made class.
 *
 * A minimal example for a splitting iterator that splits any other
 * iterator with numeric keys (at each key position that divides by
 * three without remainder):
 *
 *   <code>
 *     // Inherit from SplitIterator
 *     class SplitAtKeyDivisibleByThree extends SplitIterator {
 *
 *       // Just add the split decision: It gets called with each
 *       // item's key and value -- a split takes place if this
 *       // method returns TRUE
 *       public function needsSplit($key, $item) {
 *         // Divide the key by three (modulus), and if there's no
 *         // remainder, split!
 *         return $key % 3 == 0;
 *       }
 *     }
 *   </code>
 *
 *   Let's apply this trivial example to an Iterator that returns the
 *   letters 'a' to 'z' with the keys 1 to 26:
 *
 *   <code>
 *     // Quick way to construct an iterator over 1..26 => 'a'..'z'
 *     $testArray = array_combine(range(1, 26), range('a', 'z'));
 *     $testIterator = new ArrayIterator($testArray);
 *     
 *     // Now apply our splitter to it
 *     $splitter = new SplitAtKeyDivisibleByThree($testIterator);
 *     
 *     // And we have the letters arranged into groups of three,
 *     // so the following prints:
 *     //   abc
 *     //   def
 *     //   ghi
 *     //   ...
 *     foreach($splitter as $group) {
 *       foreach($group as $letter)
 *         print $letter;
 *       print "\n";
 *     }
 *   </code> 
 *
 * The inner iterator is in fact not really "physically" split: This
 * class just generates a sort of dynamic "view" on top of it.  The
 * advantage of this being that no real additional memory overhead is
 * created, even if the items in the inner iterator are large.  A
 * possible disadvantage is, that the {@see SplitInnerIterator}s
 * returned for the individual splits can't be rewound and only one of
 * them can be iterated over at any given time (once you retrieve the
 * next one of the split-off iterators, the last one is invalidated).
 * This is due to the fact that jumping back and forward between
 * individual splits, and rewinding them alone, would actually mean to
 * seek randomly through the inner iterator.
 *
 * For most applications (that resemble two nested loops, as in the
 * example above) these restrictions however do not matter.  If they
 * do, you might want to just override {@see current()} and copy the
 * contents of the {@see SplitInnerIterator} into another type of
 * iterator that can live independently.
 *
 * @author     Beat Vontobel
 * @since      2015-05-20
 * @package    MeteoNews\phplib
 * @subpackage Iterators
 *
 * @see SplitCallbackIterator SplitInnerIterator
 */
abstract class SplitIterator extends IteratorIterator {
  /**
   * The current item (which is the split-off iterator) returned from
   * this class.
   *
   * @val NULL|SplitInnerIterator
   */
  private $currentSplitIterator;

  /**
   * The key for the current item returned by this class. We generate
   * them as integer indices, starting at 0.
   *
   * @val int
   */
  private $key = 0;

  /**
   * Decide if it's necessary to split-off a new iterator.
   *
   * Every child needs to implement this method: It is called for each
   * item returned from the inner iterator, with that item's key and
   * value.  The splitting iterator needs to return TRUE if this item
   * should split-off a new iterator.
   *
   * @param scalar The key of the current item as returned by the
   *     inner iterator.
   *
   * @param mixed The current value as returned by the inner iterator.
   *
   * @return bool TRUE if a new split-off iterator should be started
   *     for the current item returned from the inner iterator.
   */
  abstract public function needsSplit($key, $current);

  /**
   * The {@see SplitIterator} returns generated integer keys for the
   * split-off iterators it creates.
   *
   * The index starts at 0 for the first iterator returned.
   *
   * @return int
   */
  public function key() {
    return $this->key;
  }

  /**
   * Returns the currently active split-off iterator.
   *
   * @return SplitInnerIterator
   */
  public function current() {
    return $this->currentSplitIterator;
  }

  public function next() {
    // In case we get called while one of our split iterators
    // is still active (early break): Eat that one first
    while(isset($this->currentSplitIterator)
          && $this->currentSplitIterator->valid())
      $this->currentSplitIterator->next();

    parent::next();
    if($this->splitOff())
      $this->key++;
  }

  public function rewind() {
    parent::rewind();
    $this->key = 0;
    $this->splitOff();
  }

  public function valid() {
    return isset($this->currentSplitIterator);
  }

  /**
   * Splits off a new {@see SplitInnerIterator} and makes it our
   * current item.
   *
   * Invalidates a possibly earlier created {@see SplitInnerIterator},
   * and checks if we really have more items available before
   * splitting off.
   *
   * This internal method exists as the exact same code flow is needed
   * in {@see rewind()} as well as {@see next()}.
   *
   * @return bool TRUE if a new {@see SplitInnerIterator} could be
   *     split off, FALSE otherwise.
   */
  private function splitOff() {
    if(isset($this->currentSplitIterator)) {
      $this->currentSplitIterator->invalidate();
      $this->currentSplitIterator = NULL;
    }

    if(parent::valid()) {
      $this->currentSplitIterator
        = new SplitInnerIterator($this->getInnerIterator(), array($this, 'needsSplit'));
      return TRUE;
    } else {
      return FALSE;
    }
  }
}
?>