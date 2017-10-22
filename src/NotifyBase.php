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

		/** Communicates with Systemd via a socket */
		abstract class NotifyBase implements SystemdNotifierInterface {

			protected $arrVariables=array();

			protected $strSocketLocation=null;

			private $resSocket=null;

			public function setVariable(string $strVariable,$mxdValue=null) : bool {

				if(! (0===strpos($strVariable,'X_') || in_array($strVariable,NOTIFY_VARIABLES))) {

					return false;
				}

				if(is_null($mxdValue)) {

					unset($this->arrVariables[$strVariable]);

					return true;

				}

				if(is_scalar($mxdValue)) {

					$this->arrVariables[$strVariable]=(string) $mxdValue;

					return true;
				}

				return false;
			}

			protected function setSocketLocation(?string $strSocket) {

				// Systemd passes notification socket via environment

				$strSocket=$strSocket??getenv('NOTIFY_SOCKET');

				if(strlen($strSocket) && file_exists($strSocket)) {

					$this->strSocketLocation=$strSocket;

				} else {

					throw NotifierError::socketNotFoundError($strSocket);
				}
			}

			private function formatForSending(array $arrVariables) : ?string {

				return array_reduce(

					array_keys($arrVariables),

					function(?string $strCarry,string $strKey) use($arrVariables) : string {

						return sprintf(

							'%s%s=%s%s',

							$strCarry,$strKey,

							$arrVariables[$strKey],PHP_EOL
						);
					}
				);
			}

			public function clearVariables() {

				$this->arrVariables=array();
			}

			public function getVariables() : array {

				return $this->arrVariables;
			}

			private function setVariables(array $arrVariables) {

				$this->arrVariables=$arrVariables;
			}

			public function open() {

				if(is_resource($this->resSocket)) {

					throw NotifierError::socketAlreadyOpenError();
				}

				$resSocket=@ socket_create(

					NOTIFY_SOCKET_DOMAIN,

					NOTIFY_SOCKET_TYPE,

					NOTIFY_SOCKET_PROTOCOL
				);

				if(!is_resource($resSocket)) {

					throw NotifierError::socketCreateError();
				}

				if(@ socket_connect($resSocket,$this->strSocketLocation)) {

					$this->resSocket=$resSocket;

				} else {

					throw NotifierError::socketConnectError($this->strSocketLocation,$resSocket);
				}
			}

			public function close() {

				if(!is_resource($this->resSocket)) {

					throw NotifierError::socketNotOpenError();
				}

				@ socket_close($this->resSocket);

				$this->resSocket=null;
			}

			public function isOpened() : bool {

				return is_resource($this->resSocket);
			}

			public function isClosed() : bool {

				return !$this->isOpened();
			}

			public function openIfClosed() {

				if($this->isClosed()) {

					$this->open();
				}
			}

			public function closeIfOpened() {

				if($this->isOpened()) {

					$this->close();
				}
			}

			public function send() {

				if(!$this->arrVariables) {

					return;
				}

				if(!is_resource($this->resSocket)) {

					throw NotifierError::socketNotOpenError();
				}

				$strContents=$this->formatForSending($this->arrVariables);

				$intContentLength=strlen($strContents);

				$intWritten=socket_write($this->resSocket,$strContents,$intContentLength);

				if($intContentLength<>$intWritten) {

					throw NotifierError::socketWriteError($intContentLength,$intWritten);
				}
			}
		}
	}
?>