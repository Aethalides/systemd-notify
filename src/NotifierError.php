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

		class NotifierError extends \Exception {

			public static function socketNotFoundError(?string $strSocket) : self {

				return new static(

					strlen($strSocket)
					?"Socket not found `$strSocket'"
					:"Socket not specified, check environment variable NOTIFY_SOCKET"
				);
			}

			public static function fluentFailureError(string $strVariable) : self {

				return new static(

					"Unable to set variable `$strVariable' using fluent interface"
				);
			}

			public static function socketNotOpenError() : self {

				return new static("Socket connection not open");
			}

			public static function socketWriteError(int $intLength,int $intWritten) : self {

				return new static(

					"Error writing to socket. Wrote ${intWritten} byte(s) out of ${intLength}"
				);
			}

			public static function socketAlreadyOpenError() : self {

				return new static("Socket connection already open");
			}

			public static function socketCreateError($resSocket = null) : self {

				if($resSocket !== null) {

					$intSocketError=socket_last_error($resSocket);

				} else {

					$intSocketError=socket_last_error();
				}

				$strError=socket_strerror($intSocketError);

				return new static(

					"Error $intSocketError whilst trying to create socket : $strError"
				);

			}

			public static function socketConnectError(string $strLocation,$resSocket) : self {

				$intSocketError=socket_last_error($resSocket);

				$strError=socket_strerror($intSocketError);

				return new static(

					"Error $intSocketError whilst trying to connect to socket `$strLocation`: $strError"
				);

			}
		}
	}
?>