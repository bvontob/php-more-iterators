<?php
/**
 * This is the type of iterator that {@see SplitIterator} returns for
 * its split-off parts.
 *
 * It takes a callback that decides when to stop, possibly
 * "prematurely".  A flag tells if it should stop before or after the
 * item on which the condition holds true.
 *
 * Note that the position of the inner iterator will always be
 * advanced to the next position after this iterator stops it
 * prematurely.
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
  /**
   * Flag to denote stopping after the element that fired on the stop
   * callback (i.e. this very element will still be returned from the
   * iterator).
   *
   * @see __construct()
   */
  const STOP_AFTER  = 0;

  /**
   * Flag to denote stopping before the element that fired on the stop
   * callback (i.e. this very element will not be returned anymore
   * from the iterator).
   *
   * @see __construct()
   */
  const STOP_BEFORE = 1;

  private $stopCallback;

  private $valid = TRUE;

  /**
   * When this iterator should stop, one of the constants {@see
   * STOP_BEFORE} or {@see STOP_AFTER}.
   *
   * @val integer
   */
  private $when;

  /**
   * The constructor takes the callback/configuration on when to stop
   * the inner iterator prematurely.
   *
   * @param Traversable The inner iterator, that should possibly being
   *   stopped prematurely.
   *
   * @param callable A callback (called with two arguments, the key
   *     and the item returned by the inner iterator) to decide if the
   *     inner iterator should be stopped prematurely (TRUE means
   *     stop, FALSE continue).
   *
   * @param integer One of the constants {@see STOP_AFTER} (the
   *     default) or {@see STOP_BEFORE}, deciding if a TRUE value
   *     returned from the callback should stop the iterator before or
   *     after the item that triggered the callback.
   *
   * @return SplitInnerIterator
   */
  final public function __construct(Traversable $iterator,
                                    callable $stopCallback,
                                    $when = self::STOP_AFTER) {
    $this->stopCallback = $stopCallback;
    $this->when = ($when == self::STOP_BEFORE
                   ? self::STOP_BEFORE
                   : self::STOP_AFTER);
    parent::__construct($iterator);
  }

  final public function key() {
    return $this->valid() ? parent::key() : NULL;
  }

  final public function current() {
    return $this->valid() ? parent::current() : NULL;
  }

  final public function next() {
    if(!$this->valid) {
      return;
    }

    if($this->when == self::STOP_BEFORE) {
      parent::next();
      if(!parent::valid()) {
        $this->valid = FALSE;
        return;
      }
    }

    $stopCallback = $this->stopCallback; // Due to PHP's syntax restrictions
    if($stopCallback($this->key(), $this->current())) {
      $this->valid = FALSE;
      if($this->when == self::STOP_AFTER && parent::valid())
        parent::next();
      return;
    }

    if($this->when == self::STOP_AFTER) {
      if(parent::valid())
        parent::next();
      $this->valid = parent::valid();
    }
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