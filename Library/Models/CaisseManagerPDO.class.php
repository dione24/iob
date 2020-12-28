<?php

namespace Library\Models;

use \Library\Entities\Caisse;

class CaisseManagerPDO extends CaisseManager
{
    public function UserCaisse()
    {
        $requete = $this->dao->prepare("SELECT * FROM TbleCaisse INNER JOIN TbleAgency ON TbleAgency.RefAgency=TbleCaisse.RefAgency INNER JOIN TbleChmod ON TbleChmod.RefCaisse=TbleCaisse.RefCaisse WHERE TbleChmod.RefUsers=:RefUsers");
        $requete->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
        $requete->execute();
        $data = $requete->fetchAll();
        return $data;
    }
    public function GetSolde($Caisse)
    {
        if (!empty($Caisse)) {
            $requete = $this->dao->prepare("SELECT  *  FROM tblecompte WHERE  RefCaisse=:RefCaisse");
            $requete->bindValue(':RefCaisse', $Caisse, \PDO::PARAM_INT);
            $requete->execute();
            $data = $requete->fetch();
        } else {
            $requete = $this->dao->prepare("SELECT  *  FROM tblecompte INNER JOIN TbleChmod ON TbleChmod.RefCaisse=tblecompte.RefCaisse WHERE TbleChmod.RefUsers=:RefUsers");
            $requete->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
            $requete->execute();
            $data = $requete->fetch();
        }


        return $data;
    }
    public function GetOperations($Caisse)
    {
        if (!empty($Caisse)) {
            $requete = $this->dao->prepare("SELECT  *  FROM TbleOperations INNER JOIN TbleType ON TbleType.RefType=TbleOperations.RefType WHERE  RefCaisse=:RefCaisse");
            $requete->bindValue(':RefCaisse', $Caisse, \PDO::PARAM_INT);
            $requete->execute();
            $data = $requete->fetchAll();
        } else {
            $requete = $this->dao->prepare("SELECT  *  FROM TbleOperations INNER JOIN TbleType ON TbleType.RefType=TbleOperations.RefType INNER JOIN TbleChmod ON TbleChmod.RefCaisse=TbleOperations.RefCaisse WHERE  TbleChmod.RefUsers=:RefUsers");
            $requete->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
            $requete->execute();
            $data = $requete->fetchAll();
        }
        return $data;
    }

    public function Versement($Caisse)
    {
        if (!empty($Caisse)) {
            $requete = $this->dao->prepare("SELECT SUM(MontantVersement) AS Versement FROM TbleOperations  WHERE RefType='1' AND  RefCaisse=:RefCaisse");
            $requete->bindValue(':RefCaisse', $Caisse, \PDO::PARAM_INT);
            $requete->execute();
            $data = $requete->fetch();
        } else {
            $requete = $this->dao->prepare("SELECT SUM(MontantVersement) AS Versement FROM TbleOperations INNER JOIN TbleChmod ON TbleChmod.RefCaisse=TbleOperations.RefCaisse  WHERE RefType='1' AND  TbleChmod.RefUsers=:RefUsers");
            $requete->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
            $requete->execute();
            $data = $requete->fetch();
        }
        return $data['Versement'];
    }

    public function Retrait($Caisse)
    {
        if (!empty($Caisse)) {
            $requete = $this->dao->prepare("SELECT SUM(MontantVersement) AS Retrait FROM TbleOperations  WHERE RefType='2' AND  RefCaisse=:RefCaisse");
            $requete->bindValue(':RefCaisse', $Caisse, \PDO::PARAM_INT);
            $requete->execute();
            $data = $requete->fetch();
        } else {
            $requete = $this->dao->prepare("SELECT SUM(MontantVersement) AS Retrait FROM TbleOperations INNER JOIN TbleChmod ON TbleChmod.RefCaisse=TbleOperations.RefCaisse  WHERE RefType='2' AND  TbleChmod.RefUsers=:RefUsers");
            $requete->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
            $requete->execute();
            $data = $requete->fetch();
        }
        return $data['Retrait'];
    }
    public function Appro($Caisse)
    {
        if (!empty($Caisse)) {
            $requete = $this->dao->prepare("SELECT SUM(MontantAppro) AS Appro FROM TbleAppro  WHERE   RefCaisse=:RefCaisse");
            $requete->bindValue(':RefCaisse', $Caisse, \PDO::PARAM_INT);
            $requete->execute();
            $data = $requete->fetch();
        } else {
            $requete = $this->dao->prepare("SELECT SUM(MontantAppro) AS Appro FROM TbleAppro INNER JOIN TbleChmod ON TbleChmod.RefCaisse=TbleAppro.RefCaisse  WHERE   TbleChmod.RefUsers=:RefUsers ");
            $requete->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
            $requete->execute();
            $data = $requete->fetch();
        }
        return $data['Appro'];
    }



    public function Transfert($Caisse)
    {
        if (!empty($Caisse)) {
            $requete = $this->dao->prepare("SELECT SUM(MontantTransfert) AS Transfert FROM tbletransfert  WHERE   RefCaisse=:RefCaisse");
            $requete->bindValue(':RefCaisse', $Caisse, \PDO::PARAM_INT);
            $requete->execute();
            $data = $requete->fetch();
        } else {
            $requete = $this->dao->prepare("SELECT SUM(MontantTransfert) AS Transfert FROM tbletransfert INNER JOIN TbleChmod ON TbleChmod.RefCaisse=tbletransfert.RefCaisse  WHERE   TbleChmod.RefUsers=:RefUsers ");
            $requete->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
            $requete->execute();
            $data = $requete->fetch();
        }
        return $data['Transfert'];
    }


    public function ResultCaisse($Caisse)
    {
        $SoldeCaisse = 0;
        if (!empty($Caisse)) {
            $Versement = $this->Versement($Caisse);
            $Retrait = $this->Retrait($Caisse);
            $Appro = $this->Appro($Caisse);
            $Solde =  $this->GetSolde($Caisse);
            $Transfert = $this->Transfert($Caisse);
        } else {
            $Versement = $this->Versement(NULL);
            $Retrait = $this->Retrait(NULL);
            $Appro = $this->Appro(NULL);
            $Solde =  $this->GetSolde(NULL);
            $Transfert = $this->Transfert(NULL);
        }
        $SoldeCaisse = $Solde['SoldeCompte'] + $Retrait - $Versement + $Appro - $Transfert;
        return $SoldeCaisse;
    }
}