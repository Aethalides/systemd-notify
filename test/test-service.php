<?php

	require_once __DIR__.'/../vendor/autoload.php';

	use Aethalides\Systemd\Notify\NotifyFluent;

	$objNotifier=new NotifyFluent;

	$objNotifier->open();

	/* first tell the notify daemon that we are started,
		what our PID is, and a brief status message, along
		with a heartbeat using the fluent interface */

	$objNotifier
		->setPid()
		->setReady()
		->setStatus("Ready and waiting")
		->setHeartbeat()
		->send();

	$objNotifier->clearVariables();

	$objNotifier->setHeartbeat();

	while(true) {

		// do stuff here

		$objNotifier->send();

		sleep(1);
	}

?>