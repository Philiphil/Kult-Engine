<?php
namespace kult_engine;
use kult_engine\get_text;
class page{
    public static function standardpage_head($title=null){
        $title = get_text($title);
        if(!is_null($title))
        {
            $title .=" :: ";
        }
        echo ('<!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8" />
                <script>var timer = new Date();</script>
                <title>'.$title.get_text("main_title").'</title>
                <meta content="width=device-width, initial-scale=1" name="viewport" />

                <link href="'.constant('contentpath').'assets/main.css" rel="stylesheet" type="text/css" />
                <link rel="shortcut icon" href="favicon.ico" />');
        page::standardpage_api();
        page::standardpage_js();
        echo('</head>');
    }

    public static function standardpage_header(){
        echo('<body>');
    } 

    public static function standardpage_footer(){
        echo('
    </body>
    </html>
    ');

    }

    public static function standardpage_api(){
        echo '<script src="https://apis.google.com/js/platform.js" async defer></script>';
        echo '<meta name="google-signin-client_id" content="">';
    }

    public static function standardpage_js(){
        include(constant('imptpath').'javascript.class.php');
        echo'       
        <script src="'.constant('contentpath').'script/jquery.js"></script>
        <script src="'.constant('contentpath').'script/json.js"></script>
        <script src="'.constant('contentpath').'script/script.js"></script>
        <script src="'.constant('contentpath').'script/ajax.class.js"></script>
        <script src="'.constant('contentpath').'script/cache.class.js"></script>
        <script>
            $(document).ready(function(){
                $("#k_loading").addClass("k_invisible");
            });
            </script>
            ';


        }
        public static function standardpage_body_begin($arianne="")
        {
            echo (' <div id="k_loading">
                <br>
                <img src="'.constant("imagepath").'loader.gif" />
            </div>');
        }
        public static function standardpage_body_end()
        {
            echo('');
        }
    }