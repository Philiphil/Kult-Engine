<?php
namespace kult_engine;
use kult_engine\connectorFactory;
use kult_engine\singleton;
use kult_engine\debugable;

class connector extends connectorFactory {
    use singleton;
    use debugable;
    public static $_TABLE_DEMO = "`demo`";
    public static $_DEMO_ID = "id";

    public static function demo_get($id)
    {

        $query="SELECT ".connector::$_TABLE_DEMO.
        "WHERE `".connector::$_GROUPE_NAME."` = :id";
        $query=connector::$_db->prepare($query);
        $query->execute(array(":id" => $id));
        $query = $query->fetchAll(PDO::FETCH_ASSOC);
        return $query;    
    }

}
