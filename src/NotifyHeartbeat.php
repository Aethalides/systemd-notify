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

		/** Convenience class for just sending heartbeats, aka watchdog events */
		class NotifyHeartbeat extends NotifyBase {

			/** @param $strSocket the notify socket path.
				NOTE if $strSocket is null, the value of the environment variable NOTIFY_SOCKET will be used*/
			public function __construct(?string $strSocket=null) {

				$this->setSocketLocation($strSocket);
			}

			public function __destruct() {

				$this->closeIfOpened();
			}

			/** Send a watchdog/heartbeat event to the notification socket
				Open will be called automatically */
			public function sendHeartbeat() {

				$this->openIfClosed();

				$this->setVariable('WATCHDOG',1);

				$this->send();
			}
		}
	}
?>