<?php
	$demo = new kult_engine\global_route('/*', function(){echo 'il y a forcement un /';});
	$demo = new kult_engine\global_route('*', function(){echo 'je m\'applique tout le temps';});