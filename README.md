# Kult-Engine
Kult-Engine is a full-stack framework
Its goal is to be a base design to fit every website.
Lightweight yet powerful; Kult-engine provides sets of tools for a website's common task.

PHP : 7
Version : BETA

Why would someone choose Kult-Engine over CodeIgniter/Laravel/Symphony 2/Slim/Cake ... ?
Dont.
I've made Kult-Engine for myself, because i needed a lightweight framework with only the necessary ordinary tools for a website
I didnt want to have to learn everything about a complex architecture in order to be able to 'hello world'
I didnt want a ton of helper that i wont use anyway
I didnt want 'use legit_namespace\somewhat_legit_sub_namespace\useless_sub_sub_namespace\classname\classname;' 8 times in every file
I didnt want something too different than vanilla PHP.
I didnt want to have to use linux, have to install 3 software on it, run 3 commands and then install the framework

I just wanted a set of simple tools that fits common use, how hard is it ?

Since the list of things that i dont want goes on and on;
Since this list applies almost everywhere;
Since no one is complaining about it;
You probably shouldn't use Kult-Engine.

Features
Easy to use
Easy to learn
Fast
Light Weight
Easily extendible
REST class
DB connector class
Logger class
Debugger class
Multi-lang
Caching for html inputs.
Ajax Class
Injector class
Templating
More to come.

Features to come :
Sanitizer.
Better page class
Better db connector class
Beter session classes.

QUICKSTART :

So, how does it work ?
in order to use the framework, all you have to do is.
require('invoker.class.php');
kult_engine\invoker::require_basics();

Then, you can use it.

So what can i do ?
first,
use kult_engine as k;
not required, but if you want to have to type kult_engine\ everytime you got something to do, that's not my problem.

you can
k\get_text('text');
in order to get the most appropriate for the user's langage string which have "text" for key in controller/impt/lang.php

k\page::standardpage_head();
in order to display html <head>

k\router::set_route('*', function(){echo 'helloworld';}, 'GET');
in order to display helloworld everytime somebody is accessing the page.

k\membre::login_required();
in order to redirect not log'd users to your login page.

k\inquisitor::add_deny();
in order to prevent the user from accessing your site

k\inquisitor::add_tmp();
in order to prevent your site from bruteforce.

then when you get bored with php, you can
<input type='text' k-caching='uniquekey'>
in order to cache this input.

then when you remember that you cant do anything with html, you can
var sender = new ReqAjax('req', 'param');
sender.send(UrlAjax.WhateverYouDefined, function(callback){ ... });
in order to send an ajax request to WhateverYouDefined in UrlAjax with "req" and "param" as parametter.
"req" should be the key that will define the function to launch, and param should be parametters of this functions.

Then when you remember that JS sucks, you can learn c++; listen to port 80 and build your own webserver.
