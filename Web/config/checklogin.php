<?php
require("db.php");

if (isset($_GET['login'])) {
    $query = $baseDeDonnee->prepare("SELECT * FROM TbleUsers WHERE login = '" . $_GET['login'] . "' ");
    $query->execute();
    $data = $query->fetch();
    echo json_encode($data['login']);
}