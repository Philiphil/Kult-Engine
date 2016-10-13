<?php
include("invoker.class.php");
use kult_engine as k;
k\invoker::require_basics();
k\page::standardpage_head();
k\page::standardpage_header(); 
k\page::standardpage_body_begin();

echo k\get_text("hello");

k\page::standardpage_body_end();
k\page::standardpage_footer();