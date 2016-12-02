# Kult Engine
Kult-Engine is a full-stack framework.
Its goal is to be lightweight, easy to configure/install/run, fit every website and provide sets of powerful tools for common task.

>PHP : 7  
>Version : BETA

##Why would someone choose Kult-Engine over CodeIgniter/Laravel/Symphony 2/Slim/Cake ... ?
Dont.
I've made Kult-Engine for myself, because i didnt want to use those frameworks.
- I didnt want to have to learn everything about a complex software in order to be able to "hello world", I mean : Come on ; The whole purpose of a framework is to help me to code quickly
- I didnt want a ton of heavy and useless helpers that i wont use unless i'm forced to.
- I didnt want to have to write 'use legit_namespace\somewhat_legit_sub_namespace\useless_sub_sub_namespace\classname\classname;' 8 times in every file, no matter what PSR XXVIII told
- I didnt want something too far from vanilla PHP, I mean, if you hate PHP this much, dont use it
- I didnt want to be required to use linux, install 3 softwares and  run 13 commands in order to get a folder full of .php files, what is wrong with you people ?


Since this list goes on and on;  
Since this list applies almost everywhere;  
Since no one seems to complain about it;  
You probably should not use the Kult Engine.  

##Features
- Easy to use, to learn, to install and to run.
- Lightweight and Fast.
- MVC
- REST class 
- DB connector class
- SQL Helper (i hate to write sql)
- ORM classes
- Logger class 
- Debugger class 
- Multi-lang class and functions
- Caching for html inputs. 
- Ajax Class 
- Injector class 
- Templating 
- and more.

##QUICKSTART :

###How to install ?
Just download it.
###How to make it run ?
Extract it to your site's root folder and edit config.php
###How to make it work ?
Well, in your files add 
```
  require('invoker.class.php');
  kult_engine\invoker::require_basics();
```
###How ...
Well, take a look at the wiki.
