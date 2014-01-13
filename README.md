PHPTor
======
This is a quick work PHP client for Tor.
With this class you can connect, disconnect and get new identity from Tor.
For more information please checkout the code, it's pretty simple.

# Example code
Connect to Tor and send request:
```php
$tor = new PHPTor();
$tor->torConnection();
$tor->request('http://google.com');
```
Get new identity and send request again, this time from an other IP:
```php
$tor->newIdentity();
$tor->request('http://google.com');
```

Close connection with Tor and send request from our real IP:
```php
$tor->torDisconnection();
$tor->request('http://google.com');
```
