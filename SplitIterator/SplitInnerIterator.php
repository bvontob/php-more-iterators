<?php
/**
 * This is the type of iterator that {@see SplitIterator} returns for
 * its split-off parts.
 *
 * This iterator can only be traversed once (it can't be rewound, as
 * it is actually just a "view" on parts of the outer, unsplit
 * iterator, and rewinding it would also mess with the state of the
 * outer iterator).
 *
 * @author     Beat Vontobel
 * @since      2015-05-20
 * @package    MeteoNews\phplib
 * @subpackage Iterators
 *
 * @see SplitIterator
 */
class SplitInnerIterator extends NoRewindIterator {
  private $outerIterator;

  private $valid = TRUE;

  final public function __construct(Traversable $iterator, SplitIterator $outerIterator) {
    $this->outerIterator = $outerIterator;
    parent::__construct($iterator);
  }

  final public function key() {
    return $this->valid() ? parent::key() : NULL;
  }

  final public function current() {
    return $this->valid() ? parent::current() : NULL;
  }

  final public function next() {
    if(!$this->valid)
      return;

    if($this->outerIterator->needsSplit($this->key(), $this->current())) {
      $this->valid = FALSE;
      return;
    }

    parent::next();
    $this->valid = parent::valid();
    return;
  }

  final public function valid() {
    return $this->valid;
  }

  /**
   * Immediately invalidates this iterator.
   *
   * The iterator will return NULL from its {@see key()} and {@see
   * current()} methods, and FALSE for {@see valid()} immediately
   * after this call (forever, as it can't be rewound through {@see
   * rewind()}.
   *
   * @return void
   *
   * @see valid()
   */
  final public function invalidate() {
    $this->valid = FALSE;
  }
}
?>