<?php
    require_once '../invoker.class.php';
    kult_engine\invoker::require_basics('api');
    $_SESSION['cache'][$_GET['args'][0]] = $_GET['args'][1];
