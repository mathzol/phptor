# Example code:

$tor = new PHPTor();
$tor->torConnection();
$tor->torRequest('http://ip.pappco.hu');
$tor->newIdentity();
$tor->torRequest('http://ip.pappco.hu');
$tor->torDisconnection();
$tor->torRequest('http://ip.pappco.hu');
