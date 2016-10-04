<?php

class connector extends connectorFactory {
    public static $_TABLE_DEMO = "`demo`";
    public static $_DEMO_ID = "id";

    public static function demo_get($id)
    {

        $query="SELECT ".connector::$_TABLE_DEMO.
        "WHERE `".connector::$_GROUPE_NAME."` like :od";
        $query=connector::$_db->prepare($query);
        $query->execute(array(":id" => $id));
        $query = $query->fetchAll(PDO::FETCH_ASSOC);
        return $query;    
    }

}
