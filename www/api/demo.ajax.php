<?php
    require_once '../invoker.class.php';
    kult_engine\invoker::require_basics('api');
    kult_engine\membre::login_required();
    kult_engine\membre::dont_wait();

    $req = $_GET['req'];
    $args = $_GET['args'];

    switch ($req) {
        case 'JsCall':
            Anwser();
            break;
    }

    function Anwser()
    {
        echo '1';
    }
