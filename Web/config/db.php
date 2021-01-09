<?php
ob_start();
try {

    $baseDeDonnee = new PDO('mysql:host=localhost;dbname=c1146011c_iob', 'c1146011c_iob', 'IOB@2020');
    $baseDeDonnee->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $baseDeDonnee->exec('SET NAMES utf8');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}