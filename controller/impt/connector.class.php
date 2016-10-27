<?php

namespace kult_engine;

abstract class connector extends connectorFactory
{
    use singleton;
    use settable;
    use debuggable;
    use injectable;

    public static $_TABLE_DEMO = '`demo`';
    public static $_DEMO_ID = 'id';

    public static function demo_get($id)
    {
        $query = 'SELECT '.self::$_TABLE_DEMO.
        'WHERE `'.self::$_GROUPE_NAME.'` = :id';
        $query = self::$_db->prepare($query);
        $query->execute([':id' => $id]);
        $query = $query->fetchAll(PDO::FETCH_ASSOC);

        return $query;
    }
}
