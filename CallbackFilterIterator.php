<?php
/**
 * Class CallbackFilterIterator seems to exist only from PHP 5.4, so
 * we added our own here.
 */

class CallbackFilterIterator extends FilterIterator
implements Iterator, Traversable, OuterIterator {

  private $filterCallback;

  public function __construct(Traversable $iterator, $callback = NULL) {
    if(isset($callback) && !is_callable($callback))
      throw new Exception(__CLASS__.": Second argument must be a callback");
    $this->filterCallback = $callback;
    parent::__construct($iterator);
  }

  public function accept() {
    return call_user_func($this->filterCallback,
                          $this->current(),
                          $this->key(),
                          $this->getInnerIterator());
  }
}

?>