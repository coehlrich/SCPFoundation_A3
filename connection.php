<?php
    $user = "{{username}}";
    $pw = "{{password}}";
    $db = "{{database}}";

    $connection = new mysqli('localhost', $user, $pw, $db) or die(mysqli_error($connection));
    $connection->set_charset('utf8');

    $items = $connection->query("select * from items") or die(mysqli_error($connection));
?>