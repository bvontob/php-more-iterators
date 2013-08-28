--TEST--
CallbackTransformIterator: Automatic smoke test (syntax, warnings, silence)
--FILE--
<?php
$source      = FALSE;
$output      = FALSE;
$global_vars = array();

$source = file_get_contents("CallbackTransformIterator.php");
if(preg_match('/\?>\s*$/', $source))
  echo "CallbackTransformIterator has closing PHP tag at end\n";
else
  echo "CallbackTransformIterator is missing closing PHP tag at end\n";

if(preg_match('/\x0d/', $source))
  echo "CallbackTransformIterator does contain CR characters (Windows or Mac line endings)\n";
else
  echo "CallbackTransformIterator does not contain CR characters (Windows or Mac line endings)\n";

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
CallbackTransformIterator does not contain CR characters (Windows or Mac line endings)
Parsing of CallbackTransformIterator was silent
CallbackTransformIterator did not pollute global variable space
