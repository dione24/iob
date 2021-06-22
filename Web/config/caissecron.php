<?php
require("db.php");

/*

function GetListeCaisse()
{
    $requeteAgence = $this->dao->prepar('SELECT * FROM TbleCaisse INNER JOIN TbleAgency ON TbleAgency.RefAgency=TbleCaisse.RefAgency INNER JOIN TbleChmod ON TbleChmod.RefCaisse=TbleCaisse.RefCaisse WHERE TbleChmod.RefUsers=:RefUsers');
    $requeteAgence->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
    $requeteAgence->execute();
    $ListeCaisse = $requeteAgence->fetchAll();

    return $ListeCaisse;
}

$StopCaisse = $this->dao->prepare('INSERT INTO TbleSolde(RefCaisse,Solde,RefUsers) VALUES(:RefCaisse,:Solde,:RefUsers)');
$StopCaisse->bindValue(':RefCaisse', $RefCaisse, \PDO::PARAM_INT);
$StopCaisse->bindValue(':Solde', $Solde, \PDO::PARAM_STR);
$StopCaisse->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
$StopCaisse->execute();