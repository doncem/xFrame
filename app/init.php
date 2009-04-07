<?php

////////////////////////////////////////////////////////////////////////////////////
// Setup Registry settings here                                                   //
////////////////////////////////////////////////////////////////////////////////////

//General settings
Registry::set("ADMIN","linusnorton@gmail.com");
Registry::set("ERROR_XSL","app/view/error.xsl");

//Database settings
Registry::set("DATABASE_ENGINE","MySQL");
Registry::set("DATABASE_USERNAME", $_SERVER["DB_USER"]);
Registry::set("DATABASE_PASSWORD", $_SERVER["DB_PASS"]);
Registry::set("DATABASE_HOST", $_SERVER["DB_HOST"]);
Registry::set("DATABASE_NAME", $_SERVER["DB_NAME"]);

/* Memcache settings (optional)
Registry::set("CACHE", "on");
Memcache::mch()->addServer("localhost", "11211");
*/

////////////////////////////////////////////////////////////////////////////////////
// Setup dispatcher methods here                                                  //
////////////////////////////////////////////////////////////////////////////////////

//request name, class handler, method, [cache length], [param mapping]
$parameterMap = array("param1", "param2");
Dispatcher::addListener("home", "Index", "run", 60, $parameterMap);

//Simple version
//Dispatcher::addListener("home", "Index", "run");

////////////////////////////////////////////////////////////////////////////////////
// Include class mappings (don't change)                                          //
////////////////////////////////////////////////////////////////////////////////////
include(ROOT."app/.classes.php");

?>
