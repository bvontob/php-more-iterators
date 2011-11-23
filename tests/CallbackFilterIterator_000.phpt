--TEST--
CallbackFilterIterator: Automatic smoke test (syntax, warnings, silence)
--FILE--
<?php
$source      = FALSE;
$output      = FALSE;
$global_vars = array();

$source = file_get_contents("CallbackFilterIterator.php");
if(preg_match('/\?>\s*$/', $source))
  echo "CallbackFilterIterator has closing PHP tag at end\n";
else
  echo "CallbackFilterIterator is missing closing PHP tag at end\n";

$global_vars = array_keys($GLOBALS);

ob_start();
require_once("CallbackFilterIterator.php");
$output = ob_get_contents();
ob_end_clean();
if($output === "")
  echo "Parsing of CallbackFilterIterator was silent\n";
else
  echo "Parsing of CallbackFilterIterator was not silent:\n$output";

// XXX: Currently, we have these exceptions of global variables being added...
$global_vars = array_merge($global_vars,
                           array('db_host', 'db_user', 'db_pass', 'db_name',
                                 'memcache_hostconfig',
                                 'global_script_resources_object'));

if(count(array_diff(array_keys($GLOBALS), $global_vars)) == 0)
  print "CallbackFilterIterator did not pollute global variable space\n";
else
  print "CallbackFilterIterator added these variables to global space: ".
        implode(", ", array_diff(array_keys($GLOBALS), $global_vars)).
        "\n";
?>
--EXPECT--
CallbackFilterIterator has closing PHP tag at end
Parsing of CallbackFilterIterator was silent
CallbackFilterIterator did not pollute global variable space
