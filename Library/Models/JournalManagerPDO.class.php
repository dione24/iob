<?php

namespace Library\Models;

use \Library\Entities\Journal;

class JournalManagerPDO extends JournalManager
{
    public function Operations()
    {
        $requete = $this->dao->prepare('SELECT * FROM TbleOperations INNER JOIN TbleType ON TbleType.RefType=TbleOperations.RefType INNER JOIN TbleCaisse ON TbleCaisse.RefCaisse=TbleOperations.RefCaisse INNER JOIN TbleAgency ON TbleAgency.RefAgency=TbleCaisse.RefAgency INNER JOIN TbleUsers ON TbleUsers.Refusers=TbleOperations.Insert_Id  INNER JOIN TbleChmod ON TbleChmod.RefCaisse=TbleOperations.RefCaisse WHERE TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND Approve2_Time=:jour  AND TbleChmod.RefUsers=:RefUsers ORDER BY TbleOperations.datePayement ASC ');
        $requete->bindValue(':jour', date('Y-m-d'), \PDO::PARAM_STR);
        $requete->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
        $requete->execute();
        $data = $requete->fetchAll();
        return $data;
    }
    public function GetOperations($debut, $fin, $caisse)
    {
        $requete = $this->dao->prepare("SELECT * FROM TbleOperations INNER JOIN TbleType ON TbleType.RefType=TbleOperations.RefType INNER JOIN TbleCaisse ON TbleCaisse.RefCaisse=TbleOperations.RefCaisse INNER JOIN TbleAgency ON TbleAgency.RefAgency=TbleCaisse.RefAgency INNER JOIN TbleUsers ON TbleUsers.Refusers=TbleOperations.Insert_Id    WHERE TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND  date(TbleOperations.Approve2_Time) BETWEEN '$debut' AND '$fin'  AND TbleOperations.RefCaisse=:Caisse ORDER BY TbleOperations.datePayement ASC");
        $requete->bindValue(':Caisse', $caisse, \PDO::PARAM_INT);
        $requete->execute();
        $data = $requete->fetchAll();
        foreach ($data as $key => $value) {
            $data[$key]['Debut'] = $debut;
            $data[$key]['Debut'] = $fin;
        }
        return $data;
    }
    public function UserCaisse()
    {
        $requete = $this->dao->prepare("SELECT * FROM TbleCaisse INNER JOIN TbleAgency ON TbleAgency.RefAgency=TbleCaisse.RefAgency INNER JOIN TbleChmod ON TbleChmod.RefCaisse=TbleCaisse.RefCaisse WHERE TbleChmod.RefUsers=:RefUsers");
        $requete->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
        $requete->execute();
        $data = $requete->fetchAll();
        return $data;
    }
    public function DeleteOperations($id)
    {
        $today = date("Y-m-d H:i:s");
        $requete = $this->dao->prepare("UPDATE TbleOperations SET Reset_Id=:RefUsers,Reset_At=:day WHERE RefOperations=:RefOperations");
        $requete->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
        $requete->bindValue(':day', $today, \PDO::PARAM_INT);
        $requete->bindValue(':RefOperations', $id, \PDO::PARAM_INT);
        $requete->execute();
    }
    public function sommeRetraitPeriode($debut = NULL, $fin = NULL, $caisse = NULL)
    {
        if (!empty($debut) && !empty($fin) && !empty($caisse)) {
            $SommeRetraitPeriode = $this->dao->prepare("SELECT SUM(MontantVersement) AS TotalPeriodeRetrait FROM TbleOperations  WHERE  TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND  date(TbleOperations.Approve2_Time) BETWEEN '$debut' AND '$fin'   AND TbleOperations.RefCaisse=:Caisse AND  (TbleOperations.RefType=2 OR TbleOperations.RefType=4) ");
            $SommeRetraitPeriode->bindValue(':Caisse', $caisse, \PDO::PARAM_INT);
            $SommeRetraitPeriode->execute();
            $DataSomnmeRetrait = $SommeRetraitPeriode->fetch();
            return $DataSomnmeRetrait['TotalPeriodeRetrait'];
        } else {
            $SommeRetraitPeriode = $this->dao->prepare('SELECT SUM(MontantVersement) AS TotalPeriodeRetrait FROM TbleOperations  INNER JOIN TbleChmod ON TbleChmod.RefCaisse=TbleOperations.RefCaisse  WHERE  TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND Approve2_Time=:jour AND TbleChmod.RefUsers=:RefUsers AND  (TbleOperations.RefType=2 OR TbleOperations.RefType=4)');
            $SommeRetraitPeriode->bindValue(':jour', date('Y-m-d'), \PDO::PARAM_STR);
            $SommeRetraitPeriode->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
            $SommeRetraitPeriode->execute();
            $DataSomnmeRetrait = $SommeRetraitPeriode->fetch();
            return $DataSomnmeRetrait['TotalPeriodeRetrait'];
        }
    }

    public function sommeVersementPeriode($debut = NULL, $fin = NULL, $caisse = NULL)
    {
        if (!empty($debut) && !empty($fin) && !empty($caisse)) {
            $requete = $this->dao->prepare("SELECT SUM(MontantVersement) AS TotalPeriodeVersement FROM TbleOperations  WHERE TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND  date(TbleOperations.Approve2_Time) BETWEEN '$debut' AND '$fin'   AND TbleOperations.RefCaisse=:Caisse  AND (TbleOperations.RefType=1 OR TbleOperations.RefType=3)  ");
            $requete->bindValue(':Caisse', $caisse, \PDO::PARAM_INT);
            $requete->execute();
            $data = $requete->fetch();
            return $data['TotalPeriodeVersement'];
        } else {
            $requete = $this->dao->prepare('SELECT SUM(MontantVersement) AS TotalPeriodeVersement FROM TbleOperations  INNER JOIN TbleChmod ON TbleChmod.RefCaisse=TbleOperations.RefCaisse  WHERE TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND Approve2_Time=:jour AND TbleChmod.RefUsers=:RefUsers  AND (TbleOperations.RefType=1 OR TbleOperations.RefType=3) ');
            $requete->bindValue(':jour', date('Y-m-d'), \PDO::PARAM_STR);
            $requete->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
            $requete->execute();
            $data = $requete->fetch();
            return $data['TotalPeriodeVersement'];
        }
    }

    public function YesterdaySolde($debut = NULL, $fin = NULL, $caisse = NULL)
    {
        $Solde = $this->dao->prepare("SELECT Solde FROM TbleSolde WHERE DateSolde=(SELECT MAX(DateSolde) FROM TbleSolde WHERE RefCaisse=:RefCaisse AND DateSolde <:today)");
        $Solde->bindValue(':RefCaisse', $caisse, \PDO::PARAM_INT);
        $Solde->bindValue(':today', $fin, \PDO::PARAM_STR);
        $Solde->execute();
        $data = $Solde->fetch();
        return  $data['Solde'];
    }
    public function  Versement($debut = NULL, $fin = NULL, $caisse = NULL)
    {
        $dixmille = 0;
        $cinqmille = 0;
        $deuxmille = 0;
        $mille = 0;
        $cinqcent = 0;
        $deuxcentcinq = 0;
        $deuxcent = 0;
        $cent = 0;
        $cinquante = 0;
        $vingtcinq = 0;
        $dix = 0;
        $cinq = 0;
        $un = 0;
        if (!empty($debut) && !empty($fin) && !empty($caisse)) {
            $requete = $this->dao->prepare("SELECT * FROM TbleOperations INNER JOIN TbleBilletage ON TbleBilletage.RefOperations=TbleOperations.RefOperations WHERE date(TbleOperations.Approve2_Time) BETWEEN '$debut' AND '$fin'  AND TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL  AND RefCaisse=:Caisse AND (RefType=1 OR RefType=3)  ");
            $requete->bindValue(':Caisse', $caisse, \PDO::PARAM_INT);
            $requete->execute();
            $Versement = $requete->fetchAll();
            $VersementList = [];
        } else {
            $requete = $this->dao->prepare("SELECT * FROM TbleOperations INNER JOIN TbleBilletage ON TbleBilletage.RefOperations=TbleOperations.RefOperations INNER JOIN TbleChmod ON TbleChmod.RefCaisse=TbleOperations.RefCaisse WHERE TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND Approve2_Time=:day AND TbleChmod.RefUsers=:RefUsers AND (TbleOperations.RefType=1 OR TbleOperations.RefType=3) ");
            $requete->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
            $requete->bindValue(':day', date('Y-m-d'), \PDO::PARAM_STR);
            $requete->execute();
            $Versement = $requete->fetchAll();
            $VersementList = [];
        }

        foreach ($Versement as $key => $value) {
            $dixmille += intval($value['a2']);
            $cinqmille
                += intval($value['b2']);
            $deuxmille += intval($value['c2']);
            $mille += intval($value['d2']);
            $cinqcent
                += intval($value['e2']);
            $deuxcentcinq
                += intval($value['f2']);
            $deuxcent
                += intval($value['g2']);
            $cent += intval($value['h2']);
            $cinquante
                += intval($value['i2']);
            $vingtcinq
                += intval($value['j2']);
            $dix
                += intval($value['k2']);
            $cinq
                += intval($value['l2']);
            $un
                += intval($value['m2']);
        }
        $VersementList['dixmille'] = $dixmille;
        $VersementList['cinqmille'] = $cinqmille;
        $VersementList['deuxmille'] = $deuxmille;
        $VersementList['mille'] = $mille;
        $VersementList['cinqcent'] = $cinqcent;
        $VersementList['deuxcentcinq'] = $deuxcentcinq;
        $VersementList['deuxcent'] = $deuxcent;
        $VersementList['cent'] = $cent;
        $VersementList['cinquante'] = $cinquante;
        $VersementList['vingtcinq'] = $vingtcinq;
        $VersementList['dix'] = $dix;
        $VersementList['cinq'] = $cinq;
        $VersementList['un'] = $un;
        return $VersementList;
    }

    public function  Retrait($debut = NULL, $fin = NULL, $caisse = NULL)
    {
        $dixmille = 0;
        $cinqmille = 0;
        $deuxmille = 0;
        $mille = 0;
        $cinqcent = 0;
        $deuxcentcinq = 0;
        $deuxcent = 0;
        $cent = 0;
        $cinquante = 0;
        $vingtcinq = 0;
        $dix = 0;
        $cinq = 0;
        $un = 0;
        if (!empty($debut) && !empty($fin) && !empty($caisse)) {
            $requete = $this->dao->prepare("SELECT * FROM TbleOperations INNER JOIN TbleBilletage ON TbleBilletage.RefOperations=TbleOperations.RefOperations WHERE date(TbleOperations.Approve2_Time) BETWEEN '$debut' AND '$fin'  AND TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND RefCaisse=:Caisse AND  (TbleOperations.RefType=2 OR TbleOperations.RefType=4) ");
            $requete->bindValue(':Caisse', $caisse, \PDO::PARAM_INT);
            $requete->execute();
            $retrait = $requete->fetchAll();
            $RetraitList = [];
        } else {
            $requete = $this->dao->prepare("SELECT * FROM TbleOperations INNER JOIN TbleBilletage ON TbleBilletage.RefOperations=TbleOperations.RefOperations INNER JOIN TbleChmod ON TbleChmod.RefCaisse=TbleOperations.RefCaisse WHERE TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND Approve2_Time=:day AND TbleChmod.RefUsers=:RefUsers AND (TbleOperations.RefType=2 OR TbleOperations.RefType=4)");
            $requete->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
            $requete->bindValue(':day', date('Y-m-d'), \PDO::PARAM_STR);
            $requete->execute();
            $retrait = $requete->fetchAll();
            $RetraitList = [];
        }
        foreach ($retrait as $key => $value) {
            $dixmille += intval($value['a2']);
            $cinqmille
                += intval($value['b2']);
            $deuxmille += intval($value['c2']);
            $mille += intval($value['d2']);
            $cinqcent
                += intval($value['e2']);
            $deuxcentcinq
                += intval($value['f2']);
            $deuxcent
                += intval($value['g2']);
            $cent += intval($value['h2']);
            $cinquante
                += intval($value['i2']);
            $vingtcinq
                += intval($value['j2']);
            $dix
                += intval($value['k2']);
            $cinq
                += intval($value['l2']);
            $un
                += intval($value['m2']);
        }
        $RetraitList['dixmille'] = $dixmille;
        $RetraitList['cinqmille'] = $cinqmille;
        $RetraitList['deuxmille'] = $deuxmille;
        $RetraitList['mille'] = $mille;
        $RetraitList['cinqcent'] = $cinqcent;
        $RetraitList['deuxcentcinq'] = $deuxcentcinq;
        $RetraitList['deuxcent'] = $deuxcent;
        $RetraitList['cent'] = $cent;
        $RetraitList['cinquante'] = $cinquante;
        $RetraitList['vingtcinq'] = $vingtcinq;
        $RetraitList['dix'] = $dix;
        $RetraitList['cinq'] = $cinq;
        $RetraitList['un'] = $un;
        return $RetraitList;
    }
    public function GetBielletageJournal($debut, $fin, $caisse)
    {

        $Versement = $this->Versement($debut, $fin, $caisse);
        $Retrait = $this->Retrait($debut, $fin, $caisse);

        $Biellet['dixmille'] = $Versement['dixmille'] - $Retrait['dixmille'];
        $Biellet['cinqmille'] = $Versement['cinqmille'] - $Retrait['cinqmille'];
        $Biellet['deuxmille'] = $Versement['deuxmille'] - $Retrait['deuxmille'];
        $Biellet['mille'] = $Versement['mille'] - $Retrait['mille'];
        $Biellet['cinqcent'] = $Versement['cinqcent'] - $Retrait['cinqcent'];
        $Biellet['deuxcentcinq'] = $Versement['deuxcentcinq'] - $Retrait['deuxcentcinq'];
        $Biellet['deuxcent'] = $Versement['deuxcent'] - $Retrait['deuxcent'];
        $Biellet['cent'] = $Versement['cent'] - $Retrait['cent'];
        $Biellet['cinquante'] = $Versement['cinquante'] - $Retrait['cinquante'];
        $Biellet['vingtcinq'] = $Versement['vingtcinq'] - $Retrait['vingtcinq'];
        $Biellet['dix'] = $Versement['dix'] - $Retrait['dix'];
        $Biellet['cinq'] = $Versement['cinq'] - $Retrait['cinq'];
        $Biellet['un'] = $Versement['un'] - $Retrait['un'];
        return $Biellet;
    }
    public function ValidateOperations()
    {
        $requete = $this->dao->prepare("UPDATE TbleOperations SET Validate= 2,DateValidate=:date,RefValidate=:RefUsers WHERE RefOperations=:RefOperations");
        $requete->bindValue(':RefOperations', $_POST['RefOperations'], \PDO::PARAM_STR);
        $requete->bindValue(':date', $_POST['DateValidate'], \PDO::PARAM_STR);
        $requete->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
        $requete->execute();
    }
}