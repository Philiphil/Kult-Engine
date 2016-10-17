<?php
namespace kult_engine;
use kult_engine\config;
use kult_engine\debuger;
use kult_engine\invokee;
use kult_engine\router;
use kult_engine\connector;
use kult_engine\membre;
use kult_engine as k;

class invoker{
 public static function require_basics($fnord="",$ext=null)
 {
  switch ($fnord) {
   case '':
   $fnord='../';
   break;
   case 'api':
   $fnord='../../';
   break;
 }

 include_once($fnord.'config.php');
 invoker::setter();
 spl_autoload_register(__NAMESPACE__ .'\invoker::loader');

 require_once(constant('corepath').'fonction.php');
 require_once(constant('corepath').'debugable.trait.php');
 require_once(constant('corepath').'singleton.trait.php');
 require_once(constant('corepath').'debuger.class.php');
 debuger::setter();

 require_once(constant('imptpath').'invokee.class.php');
 invokee::setter();

 require_once(constant('corepath').'logger.class.php');

 require_once(constant('imptpath').'lang.php');
 require_once(constant('corepath').'texte.script.php');

 require_once(constant('corepath').'connector.factory.php');
 require_once(constant('imptpath').'connector.class.php');
 connector::setter();

 require_once(constant('corepath').'router.php');
 require_once(constant('imptpath').'route.php');
 router::setter();

require_once(constant('imptpath').'page.class.php');

require_once(constant('corepath').'membre.class.php');
 
 session_start();
 membre::setter();
 invoker::require_mods($ext);
}

public static function require_lightweight($fnord="",$core=null,$ext=null)
{
  switch ($fnord) {
    case '':
    $fnord='../';
    break;
    case 'api':
    $fnord='../../';
    break;
  }
  include_once($fnord.'config.php');
  invoker::setter();
  spl_autoload_register(__NAMESPACE__ .'\invoker::loader');
  require_once(constant('corepath').'fonction.php');
  require_once(constant('corepath').'debugable.trait.php');
  require_once(constant('corepath').'singleton.trait.php');
  require_once(constant('imptpath').'invokee.class.php');
  invokee::setter();
  if(is_array($core))
  {
    if(\in_array("debuger",$core))
    {
      require_once(constant('corepath').'debuger.class.php');
      debuger::setter();
    }
    if(\in_array("logger",$core))
    {
      require_once(constant('corepath').'logger.class.php');
    }

    if(\in_array("lang",$core))
    {
      require_once(constant('imptpath').'lang.php');
      require_once(constant('corepath').'texte.script.php');
    }

    if(\in_array("connector",$core))
    {
      require_once(constant('corepath').'connector.factory.php');
      require_once(constant('imptpath').'connector.class.php');
      connector::setter();
    }

    if(\in_array("router",$core))
    {
      require_once(constant('corepath').'router.php');
      require_once(constant('imptpath').'route.php');
      router::setter();
    }

    if(\in_array("page",$core))
    {
      require_once(constant('imptpath').'page.class.php');
    }

    if(\in_array("session",$core))
    {
      require_once(constant('imptpath').'membre.class.php');
      session_start();
      membre::setter();
    }else{
      session_start();
    }
  }else{
    session_start();
  }
  if(is_array($ext))
  {
    invoker::require_mods($ext);
  }
}


public static function require_mods($mods=null)
{
  if(is_null($mods))
  {
    return 0;
  }
  foreach ($mods as $key) {
   require_once(constant('modpath').$key.constant('filespace').$key.'.handler.php');
 }
}

public static function config_autoload()
{
  $array[0] = '../';
  $array[1] = './';
  $array[2] = '../../';
  $array[3] = '../../../';
  $i=0;
  while(!class_exists(config::class,false) || $i < count($array))
  {
    include_once($array[$i].'config.php');
    $i++;
  }
}

public static function setter()
{
  if(!class_exists(config::class,false))
  {
    invoker::config_autoload();
  }

  if(!class_exists(config::class))
  {
    trigger_error('config file not found', E_USER_ERROR);
  }
  $filespace="/";
  switch (config::$systeme) {
    case "windows":
    $filespace="\\";
    break;
    case "linux":
    $filespace="/";
    break;
  }
  config::$config = config::$config ==1 ? config::$webfolder.$filespace : "";
  $base= substr(config::$file, 0, -strlen($filespace.config::$config."config.php"));
  define('viewpath', $base.$filespace.config::$webfolder.$filespace);
  define('modelpath', $base.$filespace.config::$modelfolder.$filespace);
  define('controllerpath', $base.$filespace.config::$controllerfolder.$filespace);
  define('vendorpath', constant('controllerpath').'vendor'.$filespace);
  define('modpath', constant('controllerpath').'mods'.$filespace);

  define('corepath', constant('controllerpath').'core'.$filespace);
  define('imptpath', constant('controllerpath').'impt'.$filespace);

  define('htmlpath', config::$htmlfolder);
  define('filespace', $filespace);
  define('contentpath', constant('htmlpath').'content/');
  define('imagepath', constant('contentpath').'images/');
  define('debug', config::$debug);
}

public static function loader($className) {
  $className = substr($className, strripos($className, '\\')+1);
  $prefix[0] = constant('corepath');
  $prefix[1] = constant('imptpath');
  $prefix[2] = constant('vendorpath');
  $prefix[3] = constant('controllerpath');
  $prefix[4] = constant('modpath').$className.constant('filespace');
  $sufix[0] = '';
  $sufix[1] = '.class';
  $sufix[2] = '.trait';   
  $sufix[3] = '.interface';
  $sufix[4] = '.handler';
  $sufix[5] = '.factory';
  foreach ($prefix as $a) {
    foreach ($sufix as $b) {
      if(file_exists($a.$className.$b.'.php'))
      {
         include_once($a.$className.$b.'.php');
         return true; 
      }
    }
  }
  return false;
}




}