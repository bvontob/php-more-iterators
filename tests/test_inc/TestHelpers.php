<?php
/**
 * A collection of helpful functions to be used in test cases.
 */
class TestHelpers {
  /**
   * We always keep the "untreated" plain result of the last debugged value,
   * method, or whatever in here. It can be accessed by getLastResult().
   */
  protected static $lastResult;


  /**
   * valdebug($value) returns a string representation of the PHP value $value.
   * The format is more compact than the one used by print_r() or var_dump()
   * and comes in handy e.g. in printf() statements in test cases, where you
   * want to see if a value is e.g. NULL or the empty string.
   */
  public static function valdebug($value) {
    if(!isset($value))
      $out = "NULL";
    elseif($value === TRUE)
      $out = "TRUE";
    elseif($value === FALSE)
      $out = "FALSE";
    elseif(is_array($value)
           && array_keys($value) === range(0, count($value) - 1))
      // Consecutive, zero-based, numeric-only arrays
      $out = sprintf('array(%s)',
                     implode(", ", array_map(__METHOD__, $value)));
    elseif(is_array($value))
      // Associative and sparse arrays
      $out = sprintf('array(%s)',
                     implode(", ", array_map(function ($k, $v) {
                           return sprintf("%s => %s",
                                          TestHelpers::valdebug($k),
                                          TestHelpers::valdebug($v));
                         },
                         array_keys($value),
                         array_values($value))));
    elseif(is_a($value, 'Exception'))
      $out = sprintf("Exception(%s('%s', %s))",
                     get_class($value),
                     $value->getMessage(),
                     $value->getCode());
    elseif(is_object($value))
      $out = sprintf('object(%s)', get_class($value));
    elseif(is_callable($value, FALSE, $callable_name))
      $out = sprintf('function(%s)', $callable_name);
    else
      $out = "'$value'";

    self::$lastResult = $value;
    return $out;
  }


  /**
   * pvaldebug() acts like valdebug() but prints out the result with an appended
   * newline instead of returning it as a string. The return value is instead
   * the "untreated" input.
   */
  public static function pvaldebug($value) {
    printf("%s\n", call_user_func('static::valdebug', $value));
    return static::getLastResult();
  }


  /**
   * Returns the name and the contents of a variable (given by name),
   * separated by an equals sign.
   *
   * @param string The name of a variable.
   *
   * @return string
   *
   * @todo This is just hacked together and should be able to deal
   *     with many more cases. Note that we don't want to use {@see
   *     eval()} though, as it might be disabled on many
   *     installations.
   */
  public static function vardebug($var) {
    $isObjProp = FALSE;
    $props = $GLOBALS;
    foreach(preg_split('/(::|->)/',
                       $var,
                       NULL,
                       PREG_SPLIT_DELIM_CAPTURE)
            as $token) {
      if($token == '::') {
        $props = (new ReflectionClass($val))->getStaticProperties();
        $isObjProp = FALSE;
      } elseif($token == '->') {
        $props = $val;
        $isObjProp = TRUE;
      } elseif(substr($token, 0, 1) == '$') {
        $val = $props[substr($token, 1)];
      } elseif($isObjProp) {
        $val = $props->$token;
      } else {
        $val = $token;
      }
    }
    return sprintf('%s = %s', $var, static::valdebug($val));
  }


  /**
   * Prints the name and the contents of a variable (given by name),
   * separated by an equals sign.
   *
   * @param string The name of a variable.
   *
   * @return mixed The contents of the variable.
   */
  public static function pvardebug($var) {
    printf("%s\n", static::vardebug($var));
    return static::getLastResult();
  }


  /**
   * calldebug($obj, $method, $args...) returns a string representation of the
   * method call and its result, all parts treated with valdebug().
   */
  public static function calldebug($obj, $method) {
    $args = array_slice(func_get_args(), 2);
    return sprintf("%s returns %s",
                   static::callstring(func_get_args()),
                   // valdebug() will set $lastResult for us as well
                   static::valdebug(call_user_func_array(array($obj, $method),
                                                         $args)));
  }


  /**
   * Like calldebug(), the only difference being that the result is printed out
   * with an appended newline instead of returned and that the real result
   * (untreated) of the method call is returned instead.
   */
  public static function pcalldebug($obj, $method) {
    printf("%s\n", call_user_func_array('static::calldebug', func_get_args()));
    return static::getLastResult();
  }


  /**
   * Like calldebug(), but catches exceptions from the called method and uses
   * those instead of a returned value automatically for debugging.
   */
  public static function catchdebug($obj, $method) {
    try {
      return call_user_func_array('static::calldebug', func_get_args());
    } catch(Exception $e) {
      return sprintf("%s throws %s",
                     static::callstring(func_get_args()),
                     // valdebug() will set $lastResult to the Exception for
                     // us as well
                     static::valdebug($e));
    }
  }


  /**
   * Like pcalldebug(), but catches exceptions from the called method and uses
   * those instead of a returned value automatically for debugging.
   */
  public static function pcatchdebug($obj, $method) {
    printf("%s\n", call_user_func_array('static::catchdebug', func_get_args()));
    return static::getLastResult();
  }


  /**
   * newdebug() tries to instantiate a new object of the class named in the first
   * argument with the following arguments. Exceptions are caught and treated
   * like with catchdebug().
   */
  public static function newdebug($class) {
    $args = array_slice(func_get_args(), 1);
    try {
      $refl = new ReflectionClass($class);
      return sprintf("new %s(%s) returns %s",
                     $class,
                     implode(", ", array_map('static::valdebug', $args)),
                     // valdebug() will set $lastResult for us as well
                     static::valdebug($refl->newInstanceArgs($args)));
    } catch(Exception $e) {
      if(is_a($e, "ReflectionException"))
        return $e->getMessage();
      else
        return sprintf("new %s(%s) throws %s",
                       $class,
                       implode(", ", array_map('static::valdebug', $args)),
                       // valdebug() will set $lastResult to the Exception for
                       // us as well
                       static::valdebug($e));
    }
  }


  /**
   * Like newdebug(), but prints out the debugging message instead of returning
   * it and returns the newly created object or the caught Exception instead.
   */
  public static function pnewdebug($class) {
    printf("%s\n", call_user_func_array('static::newdebug', func_get_args()));
    return static::getLastResult();
  }


  /**
   * Returns the "untreated" plain value of the last value that was debugged
   * with one of the other methods (e.g. valdebug() or calldebug()). Comes in
   * handy if you need the real value for some more tests.
   */
  public static function getLastResult() {
    return self::$lastResult;
  }


  /**
   * Internal helper to prepare a "call string" used by other "call debug"
   * methods. Creates something like "CLASS::method(args, ...)". Takes all
   * the arguments to the "outer" functions in a single array, to ease calling
   * with func_get_args().
   */
  protected static function callstring($args) {
    $obj    = array_shift($args);
    $method = array_shift($args);
    return sprintf("%s%s%s(%s)",
                   is_object($obj) ? get_class($obj) : $obj,
                   is_object($obj) ? "->" : "::",
                   $method,
                   implode(", ", array_map('static::valdebug', $args)));
  }


  /**
   * Helper for --SKIPIF-- sections for tests executed by PHP's run-tests
   * script.
   *
   * Will skip a test unconditionally. Use like so:
   *
   * <code>
   *     --SKIPIF--
   *     <?php
   *     if($condition)
   *       TestHelpers::skiptest("Test skipped because of...");
   *     ?>
   * </code>
   */
  public static function skiptest($message = NULL) {
    die(sprintf("skip %s",
                isset($message) && strlen($message)
                ? $message
                : sprintf("Test skipped by %s without message", __METHOD__)));
  }


  /**
   * Helper for --SKIPIF-- sections for tests executed by PHP's
   * run-tests script to skip a test on certain operating systems.
   *
   * Provide a string (to skip test on a single operating system) or
   * an array of strings (to skip on multiple systems) to the method.
   *
   * The given string is tested case insensitively for a sub-match of
   * the OS string returned by php_uname("s").  Recommended strings to
   * use are e.g. "linux", "windows" (don't use "win", as it would
   * also match "Darwin"), or "darwin" (for OS X).  Example:
   *
   * <code>
   *     --SKIPIF--
   *     <?php
   *     TestHelpers::skipon("linux");
   *     ?>
   * </code>
   *
   * @param string|array Operating systems to skip test on
   */
  public static function skipon($operating_systems, $message = NULL) {
    if(!isset($operating_systems))
      return;
    if(!is_array($operating_systems))
      $operating_systems = array($operating_systems);
    $running_os = strtolower(php_uname("s"));
    foreach($operating_systems as $skip_os) {
      if(strpos($running_os, strtolower($skip_os)) !== FALSE)
        static::skiptest(isset($message) && strlen($message)
                         ? $message
                         : sprintf("Running on %s, and '%s' should be skipped",
                                   php_uname("s"),
                                   $skip_os));
    }
  }


  /**
   * Helper for --SKIPIF-- sections for tests executed by PHP's run-tests
   * script, to skip tests based on an environment variable TEST_FLAGS.
   *
   * The environment variable TEST_FLAGS must contain a comma delimited list
   * of flags for conditional tests to be executed, or the special flag "ALL"
   * to execute all tests controlled by this method.
   *
   * In your test, just add something like this:
   *
   * <code>
   *     --SKIPIF--
   *     <?php
   *     TestHelpers::skipunless("MYTAG");
   *     ?>
   * </code>
   *
   * And this test will only be executed if something like TEST_FLAGS=MYTAG
   * exists in the environment.
   */
  public static function skipunless($test_flag, $message = NULL) {
    $test_flag = strtoupper($test_flag);

    if(!static::testflag($test_flag))
      static::skiptest(isset($message) && strlen($message)
                       ? $message
                       : sprintf("'%s' not set in TEST_FLAGS", $test_flag));
  }


  /**
   * Tests if a given test flag is set.
   *
   * Test flags are case insensitive string tags (containing no white
   * space), provided to the test environment in the environment
   * variable TEST_FLAGS as a comma separated list.  The flag ALL
   * activates all possible flags (aka this method will always return
   * TRUE as soon as the tag ALL is found in the environment).
   *
   * @return bool
   */
  public static function testflag($test_flag) {
    $test_flag  = strtoupper($test_flag);
    $test_flags = isset($_ENV['TEST_FLAGS'])
      ? array_map("strtoupper",
                  array_map("trim",
                            explode(",", $_ENV['TEST_FLAGS'])))
      : array();
    
    return in_array($test_flag, $test_flags) || in_array("ALL", $test_flags);
  }


  /**
   * assert_suppress($regexp) allows you to suppress just some assertion
   * warnings based on a regular expression against the assertion string.
   *
   * Only one $regexp may be active at any given time, the method does
   * not "nest" well. It works by turning ASSERT_WARNING off and registering
   * an ASSERT_CALLBACK instead, that mimics PHP's ASSERT_WARNING for all
   * but the suppressed assertions.
   *
   * Returns a structure of the current assert_options() that may be given
   * to assert_restore() to restore the previous settings.
   */
  public static function assert_suppress($regexp) {
    $assert_state = array();
    foreach(array(ASSERT_ACTIVE, ASSERT_WARNING, ASSERT_BAIL,
                  ASSERT_QUIET_EVAL, ASSERT_CALLBACK) as $option)
      $assert_state[$option] = assert_options($option);

    if($assert_state[ASSERT_WARNING]) {
      assert_options(ASSERT_CALLBACK,
                     function($script, $line, $assertion, $message = NULL)
                     use ($regexp) {
                       if(preg_match($regexp, $assertion))
                         return;
                       // Try to mimic PHP's built in assertion warning
                       printf("\nWarning: assert(): %s\"%s\" ".
                              "failed in %s on line %d\n",
                              isset($message) ? $message.": " : "Assertion ",
                              $assertion, $script, $line);
                     });
      assert_options(ASSERT_WARNING, FALSE);
    }

    return $assert_state;
  }

  
  /**
   * Restores the assert_options() settings to a state returned by method
   * assert_suppress().
   */
  public static function assert_restore(array $assert_state) {
    foreach($assert_state as $option => $value)
      if($option == ASSERT_CALLBACK && !is_callable($value))
        // To work around a PHP bug: ASSERT_CALLBACK does not accept NULL,
        // FALSE, or anything meaningful to _deactivate_ the callback again...
        assert_options($option, function () { });
      else
        assert_options($option, $value);
  }


  /**
   * We're a static class only (actually just a collection of functions), so
   * prevent object creation.
   */
  final private function __construct() { }
}




/* 
 * Uncomment the following line to enable unit test coverage.
 * This will only work if your php binary was built with XDebug enabled.
   require_once("tests/test_inc/XCodeTestCoverage.php");
 *
 */


?>
