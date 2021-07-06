<?php
ob_start();
try {

    $baseDeDonnee = new PDO('mysql:host=localhost;dbname=iob', 'root', 'root');
    $baseDeDonnee->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $baseDeDonnee->exec('SET NAMES utf8');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}