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
 *
 * @todo Instead of having a specific SplitInnerIterator, this could
 *       actually be something like a {@see LimitCallbackIterator}, a
 *       form of an {@see LimitIterator} that determines its starting
 *       and stopping item not from a fixed index position, but from a
 *       respective callback.  For this iterator here we just need the
 *       stop callback (always starting from the current position,
 *       ignoring the start callback), and need to add the no-rewind
 *       feature (as we wouldn't inherit from {@see NoRewindIterator}
 *       anymore).  Don't have the time to implement the full-fledged
 *       limiting callback iterator right now, so we have just a
 *       beginning (what's actually needed) here in the inner iterator
 *       for the {@see SplitIterator} right now.
 */
class SplitInnerIterator extends NoRewindIterator {
  private $stopCallback;

  private $valid = TRUE;

  final public function __construct(Traversable $iterator, callable $stopCallback) {
    $this->stopCallback = $stopCallback;
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

    $stopCallback = $this->stopCallback; // Due to PHP's syntax restrictions
    if($stopCallback($this->key(), $this->current())) {
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