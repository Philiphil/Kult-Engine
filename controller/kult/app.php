<?php
#Entry point for non-web things.
namespace kult_engine;
require_once("../../config.php");

invoker::setter();
$array = getopt();
cli_set_process_title("Kult Engine");
