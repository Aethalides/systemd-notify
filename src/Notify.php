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

		/** Communicates with SystemD via a socket

			Any notify variable set with the setXYZ method can be
			unset by passing null as the value.
			Except for setters that set a boolean, such as
			setReady, setReloading, setHeartbeat, and setStopping; those
			can be reset by passing true as the $blReset parameter */
		class Notify extends NotifyBase {

			public function __construct(?string $strSocket=null) {

				$this->setSocketLocation($strSocket);
			}

			public function setPid(?int $intPid=null) : bool {

				return $this->setVariable('MAINPID',1<$intPid?$intPid:getmypid());
			}

			public function setStatus(?string $strStatus=null) : bool {

				return $this->setVariable('STATUS',$strStatus);
			}

			public function setHeartbeat(bool $blReset=false) : bool {

				return $this->setVariable('WATCHDOG',$blReset?null:1);
			}

			public function setReady(bool $blReset=false) : bool {

				return $this->setVariable('READY',$blReset?null:1);
			}

			public function setReloading(bool $blReset=false) : bool {

				return $this->setVariable('RELOADING',$blReset?null:1);
			}

			public function setStopping(bool $blReset=false) : bool {

				return $this->setVariable('STOPPING',$blReset?null:1);
			}
		}
	}
?>