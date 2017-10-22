<?php
	require_once __DIR__.'/../vendor/autoload.php';
	use Aethalides\Systemd\Notify\Notify;
		$x=new Notify;
		$x->open();
		$x->setVariable('X_YOURMOM',13);
		$x->setPid();
		$x->close();
		$x->open();
		$x->send();
		print_r($x);
?>
