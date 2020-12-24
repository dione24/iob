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
            $ListeCaisse[$key]['Valide'] = $this->CheckRapportDaily($value['RefCaisse']);
        }
        return $ListeCaisse;
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
        $requete = $this->dao->prepare("SELECT * FROM Tbleoperations INNER JOIN tblebilletage ON tblebilletage.RefOperations=tbleoperations.RefOperations WHERE date(tbleoperations.Insert_Time)=:date AND tbleoperations.RefCaisse='1' AND tbleoperations.Insert_Id='1' AND RefType='1' ");
        $requete->bindValue(':date', $Date, \PDO::PARAM_STR);
        $requete->execute();
        $Versement = $requete->fetchAll();
        foreach ($Versement as $key => $value) {
            $dixmille += $value['a2'];
            $cinqmille
                += $value['b2'];
            $deuxmille += $value['c2'];
            $mille += $value['d2'];
            $cinqcent
                += $value['e2'];
            $deuxcentcinq
                += $value['f2'];
            $deuxcent
                += $value['g2'];
            $cent += $value['h2'];
            $cinquante
                += $value['i2'];
            $vingtcinq
                += $value['j2'];
            $dix
                += $value['k2'];
            $cinq
                += $value['l2'];
            $un
                += $value['m2'];

            $Versement['dixmille'] = $dixmille;
            $Versement['cinqmille'] = $cinqmille;
            $Versement['deuxmille'] = $deuxmille;
            $Versement['mille'] = $mille;
            $Versement['cinqcent'] = $cinqcent;
            $Versement['deuxcentcinq'] = $deuxcentcinq;
            $Versement['deuxcent'] = $deuxcent;
            $Versement['cent'] = $cent;
            $Versement['cinquante'] = $cinquante;
            $Versement['vingtcinq'] = $vingtcinq;
            $Versement['dix'] = $dix;
            $Versement['cinq'] = $cinq;
            $Versement['un'] = $un;
        }
        return $Versement;
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
        $requete = $this->dao->prepare("SELECT * FROM Tbleoperations INNER JOIN tblebilletage ON tblebilletage.RefOperations=tbleoperations.RefOperations WHERE date(tbleoperations.Insert_Time)=:date AND tbleoperations.RefCaisse='1' AND tbleoperations.Insert_Id='1' AND RefType='2' ");
        $requete->bindValue(':date', $Date, \PDO::PARAM_STR);
        $requete->execute();
        $retrait = $requete->fetchAll();
        foreach ($retrait as $key => $value) {
            $dixmille += $value['a2'];
            $cinqmille
                += $value['b2'];
            $deuxmille += $value['c2'];
            $mille += $value['d2'];
            $cinqcent
                += $value['e2'];
            $deuxcentcinq
                += $value['f2'];
            $deuxcent
                += $value['g2'];
            $cent += $value['h2'];
            $cinquante
                += $value['i2'];
            $vingtcinq
                += $value['j2'];
            $dix
                += $value['k2'];
            $cinq
                += $value['l2'];
            $un
                += $value['m2'];

            $retrait['dixmille'] = $dixmille;
            $retrait['cinqmille'] = $cinqmille;
            $retrait['deuxmille'] = $deuxmille;
            $retrait['mille'] = $mille;
            $retrait['cinqcent'] = $cinqcent;
            $retrait['deuxcentcinq'] = $deuxcentcinq;
            $retrait['deuxcent'] = $deuxcent;
            $retrait['cent'] = $cent;
            $retrait['cinquante'] = $cinquante;
            $retrait['vingtcinq'] = $vingtcinq;
            $retrait['dix'] = $dix;
            $retrait['cinq'] = $cinq;
            $retrait['un'] = $un;
        }
        return $retrait;
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