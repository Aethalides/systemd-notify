# systemd-notify #
## Introduction ##
This package allows scripts launched by systemd to send information and heartbeats (watchdog events)
to the systemd notification system.

## Use this library if... ##

You have a script that is started as a service by systemd, and you want systemd to
restart the service when it develops an error, or

You have a long running script and want to provide a status description viewable 
by the `systemctl status myservice` command.

## Do not use this library if... ##
* The system is not run by SystemD (e.g. FreeBSD, Windows, ...)
* Your service is not going to be started by Systemd

## In this library ##

Three classes are provided to interact with the notification service:

1. *Notify* is the general purpose implementation.
2. *NotifyHeartbeat* is ideal for applications that only send heartbeats
3. *NotifyFluent*  has the same methods as Notify but all the setters are
fluent.

## Examples ##
See the examples folder
