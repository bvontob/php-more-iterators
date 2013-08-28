--TEST--
CallbackTransformIterator: Automatic smoke test (syntax, warnings, silence)
--FILE--
<?php
$source      = FALSE;
$output      = FALSE;
$global_vars = array();
$cr_okay     = FALSE;

$source = file_get_contents("CallbackTransformIterator.php");
if(preg_match('/\?>\s*$/', $source))
  echo "CallbackTransformIterator has closing PHP tag at end\n";
else
  echo "CallbackTransformIterator is missing closing PHP tag at end\n";

if(file_exists(preg_replace("|[^/]+$|", "DOS_LINE_ENDINGS", "CallbackTransformIterator"))) {
  // Check for a consistent use of only CRLF if a "DOS_LINE_ENDINGS" marker
  // file exists in the containing folder
  $cr_okay = (preg_match_all('/\x0d\x0a/', $source) == preg_match_all('/\x0d/', $source)
              && preg_match_all('/\x0d\x0a/', $source) == preg_match_all('/\x0a/', $source));
} else {
  // Otherwise (normal situation) any CR character is treated as a bug
  $cr_okay = !preg_match('/\x0d/', $source);
}

if($cr_okay)
  echo "CallbackTransformIterator does not contain CR characters (or is consistent and in a folder marked with DOS_LINE_ENDINGS)\n";
else
  echo "CallbackTransformIterator does contain CR characters (and is not consistent or not in a folder marked with DOS_LINE_ENDINGS)\n";

$global_vars = array_keys($GLOBALS);

ob_start();
require_once("CallbackTransformIterator.php");
$output = ob_get_contents();
ob_end_clean();
if($output === "")
  echo "Parsing of CallbackTransformIterator was silent\n";
else
  echo "Parsing of CallbackTransformIterator was not silent:\n$output";

// XXX: Currently, we have these exceptions of global variables being added...
$global_vars = array_merge($global_vars,
                           array('db_host', 'db_user', 'db_pass', 'db_name',
                                 'memcache_hostconfig',
                                 'global_script_resources_object'));

if(count(array_diff(array_keys($GLOBALS), $global_vars)) == 0)
  print "CallbackTransformIterator did not pollute global variable space\n";
else
  print "CallbackTransformIterator added these variables to global space: ".
        implode(", ", array_diff(array_keys($GLOBALS), $global_vars)).
        "\n";
?>
--EXPECT--
CallbackTransformIterator has closing PHP tag at end
CallbackTransformIterator does not contain CR characters (or is consistent and in a folder marked with DOS_LINE_ENDINGS)
Parsing of CallbackTransformIterator was silent
CallbackTransformIterator did not pollute global variable space
