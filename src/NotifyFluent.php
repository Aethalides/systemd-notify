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

		/** Fluent interface for interacting with the Systemd notify service
			All the setters return the object, but if a setting fails a
			NotifierError Exception is thrown */
		class NotifyFluent extends NotifyBase {

			public function __construct(?string $strSocket=null) {

				$this->setSocketLocation($strSocket);
			}

			public function setPid(?int $intPid=null) : self {

				if($this->setVariable('MAINPID',1<$intPid?$intPid:getmypid())) {

					return $this;
				}

				throw NotifyError::fluentFailureError('MAINPID');
			}

			public function setStatus(?string $strStatus=null) : self {

				if($this->setVariable('STATUS',$strStatus)) {

					return $this;
				}

				throw NotifyError::fluentFailureError('STATUS');
			}

			public function setHeartbeat(bool $blReset=false) : self {

				if($this->setVariable('WATCHDOG',$blReset?null:1)) {

					return $this;
				}

				throw NotifyError::fluentFailureError('STATUS');
			}

			public function setReady(bool $blReset=false) : self {

				if($this->setVariable('READY',$blReset?null:1)) {

					return $this;
				}

				throw NotifyError::fluentFailureError('READY');
			}

			public function setReloading(bool $blReset=false) : self {

				if($this->setVariable('RELOADING',$blReset?null:1)) {

					return $this;
				}

				throw NotifyError::fluentFailureError('RELOADING');
			}

			public function setStopping(bool $blReset=false) : self {

				if($this->setVariable('STOPPING',$blReset?null:1)) {

					return $this;
				}

				throw NotifyError::fluentFailureError('STOPPING');
			}
		}
	}
?>