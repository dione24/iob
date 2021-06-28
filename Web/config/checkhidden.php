<?php
require("db.php");

if (isset($_GET['produit'])) {
    $query = $baseDeDonnee->prepare("SELECT * FROM TbleProduit  WHERE RefProduit=:RefProduit");
    $query->bindValue(':RefProduit', $_GET['produit'], PDO::PARAM_INT);
    $query->execute();
    $data = $query->fetch();
    if ($data['RefProduit'] == 1) {
        echo json_encode('1');
    } elseif ($data['RefProduit'] == 2) {
        echo json_encode('2');
    } elseif ($data['RefProduit'] == 3) {
        echo json_encode('3');
    }
}