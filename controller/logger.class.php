<?php

class logger{
    public static $_max=200;

    public static function write($fnord)
    {
        $line = logger::get_standard_header();
        logger::$_max -= strlen($line);

        $number = round((strlen($fnord)/logger::$_max), 0, PHP_ROUND_HALF_UP)+1;
        $array = array();

        for ($i=0; $i < $number ; $i++)
        { 
            $y = 1 +$i;
            $array[$i] = utf8_to_safe(substr($fnord, 0, logger::$_max));
            $fnord = substr($fnord, logger::$_max);
        }

        if($array[count($array)-1]=="")
        {
            unset($array[count($array)-1]);
        }

        $i=0;
        foreach ($array as $key) {
            $i++;
            $msg = $line.':'.'['.$i.'/'.count($array).']'.$key;
            $syslog = new Syslog();
            $syslog->SetMsg($msg);
            $retour = $syslog->Send();
        }
    }

    public static function get_standard_header()
    {
        $line = "";
        $line .='['.$_SERVER['HTTP_USER_AGENT'];
        $line .= ']['.$_SERVER['REMOTE_ADDR'];
        $line .=']';
        $line .='['.date('d/m/Y-H:i:s',time()).']';
        $line .='['.$_SERVER['REQUEST_METHOD'].']';
        $line .='['.$_SERVER['REQUEST_URI'].']';
        return $line;
    }

    public static function write_local($fnord)
    {
        $file = constant('controllerpath').'log.kult';
        $line = logger::get_standard_header();
        $line .=':'.$fnord;
        $line .= "\n";
        file_put_contents($file, $line, FILE_APPEND);
    }



}