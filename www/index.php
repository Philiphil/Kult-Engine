<?php
include 'invoker.class.php';
invoker::require_basics();
page::standardpage_head();


page::standardpage_header();
page::standardpage_body_begin();


?>

<?php echo get_texte('hello'); ?>.

<?php 
page::standardpage_body_end();
page::standardpage_footer(); ?>
