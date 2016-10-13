<?php
namespace kult_engine;
use kult_engine\singleton;
use kult_engine\debugable;

class membre{
    use singleton;
    use debugable;
    public static $_id;
    public static $_log;
    public static $_token_1;
    public static $_token_2;

    public static function setter(){
        if(isset($_SESSION['log']) && password_verify($_SERVER['HTTP_USER_AGENT'], $_SESSION['token_1']) && password_verify("$_S7aTic_:p=rm@tK3y;", $_SESSION['token_2']))
        {
            membre::$_id = intval($_SESSION['id']);
            membre::$_log =intval($_SESSION["log"]);
            membre::$_token_1 = $_SESSION['token_1'];
            membre::$_token_2 = $_SESSION['token_2'];
        }else{
            membre::destroy();
        }
    }

    public static function destroy(){
        unset($_SESSION['id']);
        unset($_SESSION['log']);
        unset($_SESSION['token_1']);
        unset($_SESSION['token_2']);

    }

    public static function login_required()
    {
        if(!isset($_SESSION['log'])){

            if(!membre::is_on_login_page())
            {
                redirect(constant('htmlpath')."connexion.php",0);
                die;
            }
        }
    }

    public static function is_on_login_page()
    {
        $page_de_connexion = "connexion.php";
        return substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], constant("filespace"))+1) === $page_de_connexion;
    }


    public static function connexion($id){
        $_SESSION['id']=$id;
        $_SESSION['log']=1;
        $_SESSION['token_1'] = password_hash($_SERVER['HTTP_USER_AGENT'], PASSWORD_BCRYPT);
        $_SESSION['token_2'] = password_hash("$_S7aTic_:p=rm@tK3y;", PASSWORD_BCRYPT);
        membre::$_id = intval($_SESSION['id']);   
        membre::$_log =1;
        membre::$_token_1 = password_hash($_SERVER['HTTP_USER_AGENT'], PASSWORD_BCRYPT);
        membre::$_token_2 = password_hash("$_S7aTic_:p=rm@tK3y;", PASSWORD_BCRYPT);
        return 1;
    }

    public static function dont_wait()
    {
        session_write_close();
    }


}