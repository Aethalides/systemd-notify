# systemd-notify example daemon #
## Installing the example ##

After downloading the example to the `/opt/systemd-notify/examples/lookupd` directory, cd to the directory and execute:

````bash
composer install
````

Edit the lookupd.service file and adjust the `User`, and `Group` entries according to your needs, then execute:

````bash
sudo install lookupd.service
````
  
## Running & testing the daemon ##

Starting the daemon is now as easy as just asking systemd: `sudo systemctl start lookupd` and if the service started correctly, no further output is produced.

### Getting the service status ###
We can now query the status of our service by asking systemd:

````bash
[Andy@Awesome lookupd]$ sudo systemctl status lookupd
● lookupd.service - Example service to lookup hostnames
   Loaded: loaded (/etc/systemd/system/lookupd.service; disabled; vendor preset: disabled)
   Active: active (running) since Sun 2017-10-29 15:00:01 GMT; 5s ago
 Main PID: 32033 (php)
   Status: "Listening on 127.0.0.1:7777; Served 0 client(s)"
    Tasks: 1 (limit: 4915)
   CGroup: /system.slice/lookupd.service
           └─32033 /usr/bin/php /opt/systemd-notify/examples/lookupd/lookupd-start.php
````

This output tells us several things; it shows that the service is running properly and sending 
heartbeat (watchdog) events, and a status message.

A script has also been provided to test the daemon, let's run that a couple of times:

````bash
[Andy@Awesome lookupd]$ for counter in {1..10}; do php test-lookup.php; done
localhost.localdomain
localhost.localdomain
localhost.localdomain
localhost.localdomain
localhost.localdomain
localhost.localdomain
localhost.localdomain
localhost.localdomain
localhost.localdomain
localhost.localdomain
````

Now check the status again

````bash
[Andy@Awesome lookupd]$ sudo systemctl status lookupd
● lookupd.service - Example service to lookup hostnames
   Loaded: loaded (/etc/systemd/system/lookupd.service; disabled; vendor preset: disabled)
   Active: active (running) since Sun 2017-10-29 15:09:05 GMT; 9min ago
 Main PID: 32033 (php)
   Status: "Listening on 127.0.0.1:7777; Served 10 client(s)"
    Tasks: 1 (limit: 4915)
   CGroup: /system.slice/lookupd.service
           └─32033 /usr/bin/php /opt/systemd-notify/examples/lookupd/lookupd-start.php
````

Of course this is a contrived example but it clearly demonstrates how this package can be used in your own services.

