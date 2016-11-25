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
- REST class 
- DB connector class 
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
Well, take a look at the wiki. (although its not done yet)
(since its not done yet here's what old readme.md used to say : )

###So what can i do ?  
first,
```
    use kult_engine as k;
```
not required, but if you want to have to type kult_engine\ everytime you got something to do, that's not my problem.

you can
```
  k\get_text('text');
```
in order to get the most appropriate for the user's langage string which have "text" for key in controller/impt/lang.php
```
  k\page::standardpage_head();
```
in order to display html `<head>`
```
  k\router::set_route('*', function(){echo 'helloworld';}, 'GET');
```
in order to display helloworld everytime somebody is accessing the page.
```
  k\membre::login_required();
```
in order to redirect not log'd users to your login page.
```
  k\inquisitor::add_deny();
```
in order to prevent the user from accessing your site
```
  k\inquisitor::add_tmp();
```
in order to prevent your site from bruteforce.

then when you get bored with php, you can
```
  <input type='text' k-caching='uniquekey'>
```
in order to cache this input.

then when you remember that you cant do anything with html, you can
```
  var sender = new ReqAjax('req', 'param');
  sender.send(UrlAjax.WhateverYouDefined, function(callback){ ... });
```
in order to send an ajax request to WhateverYouDefined in UrlAjax with "req" and "param" as parametter.
"req" should be the key that will define the function to launch, and param should be parametters of this functions.

Then when you remember that JS sucks, you can learn c++; listen to port 80 and build your own webserver.
