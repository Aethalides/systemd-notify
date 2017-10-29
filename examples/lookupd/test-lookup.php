<?php

	if($c=fsockopen('localhost',7777)) {

		echo stream_get_contents($c).PHP_EOL;
	}
?>