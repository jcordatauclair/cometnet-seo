
<?php

$client = stream_socket_client("tcp://localhost:2009", $errno, $errorMessage);

if ($client === false) {
    throw new UnexpectedValueException("Failed to connect : $errorMessage");
}
echo "Votre message: ";
$chaine=fgets(STDIN);
fflush(STDIN);
echo $chaine;
fwrite($client,$chaine);
fflush($client);
echo stream_get_contents($client);
fclose($client);

?>
