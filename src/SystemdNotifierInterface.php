<?php
	/* Copyright(c) 2017 Aethalides@AndyPieters.me.uk

		This file is part of the aethalides/systemd-notify package

		aethalides/systemd-notify is free software: you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation, either version 3 of the License, or
		(at your option) any later version.

		aethalides/systemd-notify is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with aethalides/systemd-notify.  If not, see <http://www.gnu.org/licenses/>. */
	namespace Aethalides\Systemd\Notify {

		/** These are the variables recognised by the Systemd notify system
			It is possible to send variables not in this list as long as you
			prefix them with X_
			@see <a href="https://www.freedesktop.org/software/systemd/man/sd_notify.html">sd_notify man page</a> */
		const NOTIFY_VARIABLES=array(

			'STATUS','READY','RELOADING',

			'STOPPING','ERRNO','BUSERROR','MAINPID',

			'WATCHDOG','FDSTORE','FDNAME','WATCHDOG_USEC'
		);

		const NOTIFY_SOCKET_DOMAIN=\AF_UNIX;

		const NOTIFY_SOCKET_TYPE=\SOCK_DGRAM;

		const NOTIFY_SOCKET_PROTOCOL=0;

		interface SystemdNotifierInterface {

			public function __construct(

				?string $strSocketLocation=null
			);

			/** Return values:
				TRUE if $strVariable is a recognised variable, or starts with X_
					AND
						if $mxdValue is null (which should unset the variable)
							OR
						if $mxdValue is a scalar value
				FALSE for any other case */
			public function setVariable(string $strVariable,$mxdValue=null) : bool;

			/** Remove all previously set variables */
			public function clearVariables();

			/** Opens the socket connection
				Should either succeed or throw a NotifierError Exception.
				Opening an already opened connection is an error */
			public function open();

			/** Closes the socket connection
				Should either succeed or throw a NotifierError Exception
				Closing an already closed connection is an error */
			public function close();

			/** Send the variables over the socket connection.
				If no variables are set, do nothing
				Check if connection is open before sending
				Should either succeed or throw a NotifierError for things like:
				- socket not open
				- writing wrote less bytes than content length */
			public function send();
		}
	}
?>