<?php
namespace kult_engine;
class config{
  #html folder's name.
  public static $webfolder = "www";
  
  #model & controller folder's name
  # www/model or www\model should be specified if the folder is in the webfolder
  #protip : they shouldnt
  public static $modelfolder = "model";
  public static $controllerfolder = "controller";

  # HTML root folder's name
  public static $htmlfolder = "/";

  #is config.php inside webfolder ?
  #protip : it shouldnt
  public static $config = 0;

  #Is the webserveur 'linux' or 'windows' ?
  #if neither, have in mind that windows means c:\user
  #and linux means /root/user
  #the question is SHOULD I USE A SLASH OR AN ANTI SLASH
  public static $systeme = "linux";

  #SQL IDs
  public static $host = '';
  public static $db = '';
  public static $user = '';
  public static $pass = '';

  #SHOULD THE WEBSITE BE IN DEBUG MODE ? 0/1
  public static $debug = 1;

  #CORE
  public static $file = __FILE__;
}

