<?php
/**
 * Class CallbackTransformIterator implements an Iterator that transforms
 * the values of another Iterator through a callback provided from the
 * outside.
 */

class CallbackTransformIterator extends IteratorIterator
implements Iterator, Traversable, OuterIterator {

  private $transformCallback;


  /**
   * In addition to a plain IteratorIterator we take a second argument with
   * a callback function to transform the values returned by the inner Iterator.
   * The callback will be called with three arguments: The current value, the
   * current key and the inner Iterator. Most of the time it will probably
   * only use the first of the three arguments (the value). It must return
   * the transformed value.
   *
   * XXX: Replace internal type check with a typehint for $callback as of
   *      PHP 5.4, when this should be added as a new feature...
   */
  public function __construct(Traversable $iterator, $callback = NULL) {
    if(isset($callback) && !is_callable($callback))
      throw new Exception(__CLASS__.": Second argument must be a callback");
    $this->transformCallback = $callback;
    parent::__construct($iterator);
  }


  /**
   * current() will return the current value of the inner Iterator transformed
   * by the callback provided to the constructor.
   */
  public function current() {
    if(isset($this->transformCallback))
      return call_user_func($this->transformCallback,
                            parent::current(),
                            $this->key(),
                            $this->getInnerIterator());
    return parent::current();
  }
}

?>