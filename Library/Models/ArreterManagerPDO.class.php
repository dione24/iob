<?php

namespace Library\Models;

use \Library\Entities\Arreter;

class ArreterManagerPDO extends ArreterManager
{
    public function GetListeCaisse()
    {
        $requeteAgence = $this->dao->prepare('SELECT * FROM TbleCaisse INNER JOIN TbleAgency ON TbleAgency.RefAgency=TbleCaisse.RefAgency INNER JOIN TbleChmod ON TbleChmod.RefCaisse=TbleCaisse.RefCaisse WHERE TbleChmod.RefUsers=:RefUsers');
        $requeteAgence->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
        $requeteAgence->execute();
        $ListeCaisse = $requeteAgence->fetchAll();
        foreach ($ListeCaisse as $key => $value) {
            $ListeCaisse[$key]['Valide'] = $this->CheckDailyClose($value['RefCaisse']);
        }
        return $ListeCaisse;
    }
    public function StopCaisse($RefCaisse, $Solde)
    {
        //Arreter de Caisse 
        $StopCaisse = $this->dao->prepare('INSERT INTO TbleSolde(RefCaisse,Solde,RefUsers) VALUES(:RefCaisse,:Solde,:RefUsers)');
        $StopCaisse->bindValue(':RefCaisse', $RefCaisse, \PDO::PARAM_INT);
        $StopCaisse->bindValue(':Solde', $Solde, \PDO::PARAM_STR);
        $StopCaisse->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
        $StopCaisse->execute();
    }
    public function CheckDailyClose($Caisse)
    {
        $requete = $this->dao->prepare("SELECT * FROM TbleSolde WHERE RefCaisse=:RefCaisse AND date(DateSolde)=:jour");
        $requete->bindValue(':RefCaisse', $Caisse, \PDO::PARAM_INT);
        $requete->bindValue(':jour', date('Y-m-d'), \PDO::PARAM_STR);
        $requete->execute();
        $Result = $requete->fetch();
        return $Result;
    }
    public function CheckRapportDaily($Caisse)
    {
        $requete = $this->dao->prepare("SELECT * FROM TbleRapportOp WHERE RefCaisse=:RefCaisse AND Date=:jour");
        $requete->bindValue(':RefCaisse', $Caisse, \PDO::PARAM_INT);
        $requete->bindValue(':jour', date('Y-m-d'), \PDO::PARAM_STR);
        $requete->execute();
        $dataResult = $requete->fetch();
        return $dataResult;
    }

    public  function SommeVersementCaisse($Caisse)
    {
        $requeteSUm = $this->dao->prepare('SELECT SUM(MontantVersement) AS TotalVersment FROM TbleOperations WHERE TbleOperations.RefType=1 AND TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND Approve2_Time=:jour  AND  TbleOperations.RefCaisse=:RefCaisse');
        $requeteSUm->bindValue(':jour', date('Y-m-d'), \PDO::PARAM_STR);
        $requeteSUm->bindValue(':RefCaisse', $Caisse, \PDO::PARAM_INT);
        $requeteSUm->execute();
        $data = $requeteSUm->fetch();
        return $data['TotalVersment'];
    }
    public function SommeRetraitCaisse($Caisse)
    {
        $requeteSUm = $this->dao->prepare('SELECT SUM(MontantVersement) AS TotalRetrait FROM TbleOperations  WHERE TbleOperations.RefType=2 AND TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND Approve2_Time=:jour  AND  TbleOperations.RefCaisse=:RefCaisse');
        $requeteSUm->bindValue(':jour', date('Y-m-d'), \PDO::PARAM_STR);
        $requeteSUm->bindValue(':RefCaisse', $Caisse, \PDO::PARAM_INT);
        $requeteSUm->execute();
        $data = $requeteSUm->fetch();
        return $data['TotalRetrait'];
    }
    /* Close caisse pour rapport omni
    public function CloseCaisse($Caisse)
    {
        $SommeVersementCaisse = $this->SommeVersementCaisse($Caisse);
        $SommeRetraitCaisse = $this->SommeRetraitCaisse($Caisse);
        $requeteOuverture = $this->dao->prepare("SELECT * FROM TbleRapportOp WHERE RefCaisse=:RefCaisse ORDER BY RefRapport DESC LIMIT 0,1   ");
        $requeteOuverture->bindValue(':RefCaisse', $Caisse, \PDO::PARAM_INT);
        $requeteOuverture->execute();
        $dataOuverture = $requeteOuverture->fetch();
        if (!empty($dataOuverture)) {
            $requeteAppro = $this->dao->prepare("SELECT SUM(MontantAppro) AS TotalAPpro FROM TbleAppro WHERE RefCaisse=:RefCaisse AND DateAppro=:jour");
            $requeteAppro->bindValue(':RefCaisse', $Caisse, \PDO::PARAM_INT);
            $requeteAppro->bindValue(':jour', date('Y-m-d'), \PDO::PARAM_STR);
            $requeteAppro->execute();
            $dataAppro = $requeteAppro->fetch();
            $SoldeFermetureOMNI = $dataOuverture['SoldeFrOmni'] + $dataAppro['MontantAppro'] - $SommeVersementCaisse + $SommeRetraitCaisse;
            $soldeFermetureEspces = $dataOuverture['SoldeFrEspeces'] - $SommeRetraitCaisse +  $SommeVersementCaisse;

            $requeteNewRaport = $this->dao->prepare("INSERT INTO TbleRapportOp(SoldeOvEspeces,SoldeOvOmni,Versement,Retrait,ApproOmni,SoldeFrEspeces,SoldeFrOmni,Date,RefCaisse) VALUES(:SoldeOvEspeces,:SoldeOvOmni,:Versement,:Retrait,:ApproOmni,:SoldeFrEspeces,:SoldeFrOmni,:Date,:RefCaisse) ");
            $requeteNewRaport->bindValue(':SoldeOvEspeces', $dataOuverture['SoldeFrEspeces'], \PDO::PARAM_STR);
            $requeteNewRaport->bindValue(':SoldeOvOmni', $dataOuverture['SoldeFrOmni'], \PDO::PARAM_STR);
            $requeteNewRaport->bindValue(':Versement', $SommeVersementCaisse, \PDO::PARAM_STR);
            $requeteNewRaport->bindValue(':Retrait', $SommeRetraitCaisse, \PDO::PARAM_STR);
            $requeteNewRaport->bindValue(':ApproOmni', $dataAppro['TotalAPpro'], \PDO::PARAM_STR);
            $requeteNewRaport->bindValue(':SoldeFrEspeces', $soldeFermetureEspces, \PDO::PARAM_STR);
            $requeteNewRaport->bindValue(':SoldeFrOmni', $SoldeFermetureOMNI, \PDO::PARAM_STR);
            $requeteNewRaport->bindValue(':Date', date('Y-m-d'), \PDO::PARAM_STR);
            $requeteNewRaport->bindValue(':RefCaisse', $Caisse, \PDO::PARAM_INT);
            $requeteNewRaport->execute();
            header("location: /Arreter/index");
            $_SESSION['flash']['success'] = "Changement Effectué";
        } else {
            header("location: /Arreter/index");
            $_SESSION['flash']['warning'] = "Changement non effectué, Absence du rapport initial, Veuillez Contacter l\'admin";
        }
    }
    */
    public function ListeSolde($Caisse)
    {
        $requeteRapport = $this->dao->prepare('SELECT * FROM TbleSolde INNER JOIN TbleUsers ON TbleUsers.RefUsers=TbleSolde.RefUsers WHERE RefCaisse=:RefCaisse');
        $requeteRapport->bindValue(':RefCaisse', $Caisse, \PDO::PARAM_INT);
        $requeteRapport->execute();
        $data = $requeteRapport->fetchAll();
        return $data;
    }
    public function DeleteSolde($RefSolde)
    {
        $requete = $this->dao->prepare('DELETE FROM TbleSolde WHERE RefSolde=:RefSolde');
        $requete->bindValue(':RefSolde', $RefSolde, \PDO::PARAM_INT);
        $requete->execute();
    }
    public function GetRapports($Caisse)
    {
        $requeteRapport = $this->dao->prepare('SELECT * FROM TbleRapportOp WHERE RefCaisse=:RefCaisse');
        $requeteRapport->bindValue(':RefCaisse', $Caisse, \PDO::PARAM_INT);
        $requeteRapport->execute();
        $data = $requeteRapport->fetchAll();
        return $data;
    }

    public function  Versement($Date)
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

        $requete = $this->dao->prepare("SELECT * FROM TbleOperations INNER JOIN TbleBilletage ON TbleBilletage.RefOperations=TbleOperations.RefOperations INNER JOIN TbleCaisse ON TbleCaisse.RefCaisse=TbleOperations.RefCaisse INNER JOIN TbleChmod ON TbleChmod.RefCaisse=TbleCaisse.RefCaisse WHERE TbleChmod.RefUsers=:RefUsers AND  TbleOperations.Approve2_Time=:day  AND  TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL  AND (TbleOperations.RefType=1 OR TbleOperations.RefType=3)");
        $requete->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
        $requete->bindValue(':day', $Date, \PDO::PARAM_STR);
        $requete->execute();
        $Versement = $requete->fetchAll();
        $VersementList = [];

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

    public function  Retrait($Date)
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

        $requete = $this->dao->prepare("SELECT * FROM TbleOperations INNER JOIN TbleBilletage ON TbleBilletage.RefOperations=TbleOperations.RefOperations  INNER JOIN TbleCaisse ON TbleCaisse.RefCaisse=TbleOperations.RefCaisse INNER JOIN TbleChmod ON TbleChmod.RefCaisse=TbleCaisse.RefCaisse WHERE TbleChmod.RefUsers=:RefUsers AND TbleOperations.Approve2_Time=:day  AND  TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL  AND (TbleOperations.RefType=2 OR TbleOperations.RefType=4) ");
        $requete->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
        $requete->bindValue(':day', $Date, \PDO::PARAM_STR);
        $requete->execute();
        $retrait = $requete->fetchAll();
        $RetraitList = [];
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
    public function GetDailyBielletage($Date)
    {

        $Versement = $this->Versement($Date);
        $Retrait = $this->Retrait($Date);

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
}