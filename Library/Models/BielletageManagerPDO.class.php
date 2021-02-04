<?php

namespace Library\Models;

use \Library\Entities\Bielletage;

class BielletageManagerPDO extends BielletageManager
{

    public function ChomdUser()
    {
        $requete
            = $this->dao->prepare('SELECT * FROM TbleChmod WHERE RefUsers=:RefUsers');
        $requete->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
        $requete->execute();
        $display = $requete->fetchAll();
        return $display;
    }
    public function CheckOuverture()
    {
        $ChomdUser = $this->ChomdUser();
        if (!empty($ChomdUser)) {
            $Caisse = [];
            foreach ($ChomdUser as $key => $value) {
                $Caisse[] = $value['RefCaisse'];
            }
            $implode = implode(',', $Caisse);
            $requete =
                $this->dao->prepare("SELECT * FROM TbleOuverture INNER JOIN TbleCaisse ON TbleCaisse.RefCaisse=TbleOuverture.RefCaisse INNER JOIN TbleAgency ON TbleAgency.RefAgency=TbleCaisse.RefAgency  WHERE TbleOuverture.RefCaisse IN (" . $implode . ") AND TbleOuverture.RefDays=:jour AND NOW() BETWEEN TbleOuverture.HeureDebut AND TbleOuverture.HeureFin ");
            $requete->bindValue(':jour', (date('w') == 0) ? 7 : date('w'), \PDO::PARAM_STR);
            $requete->execute();
            $display = $requete->fetchAll();
            foreach ($display as $key => $value) {
                $display[$key]['caisse'] = $this->CheckDailyClose($value['RefCaisse']);
            }
            return $display;
        }
    }
    public function CheckDailyClose($Caisse)
    {
        $requete = $this->dao->prepare("SELECT * FROM TbleSolde WHERE RefCaisse=:RefCaisse AND date(DateSolde)=:jour");
        $requete->bindValue(':RefCaisse', $Caisse, \PDO::PARAM_INT);
        $requete->bindValue(':jour', date('Y-m-d'), \PDO::PARAM_STR);
        $requete->execute();
        $Result = $requete->fetch();
        return $Result['RefCaisse'];
    }
    public function CheckAfterRapport($Caisse)
    {
        $requete = $this->dao->prepare("SELECT * FROM TbleRapportOp WHERE RefCaisse=:RefCaisse AND Date=:jour");
        $requete->bindValue(':RefCaisse', $Caisse, \PDO::PARAM_INT);
        $requete->bindValue(':jour', date('Y-m-d'), \PDO::PARAM_STR);
        $requete->execute();
        $data = $requete->fetch();
        return $data;
    }

    public  function GetCaisse()
    {
        if ($_SESSION['statut'] == 'Caissier') {
            $requeteCaisse = $this->dao->prepare('SELECT * FROM TbleOperations INNER JOIN TbleType ON TbleType.RefType=TbleOperations.RefType INNER JOIN TbleCaisse ON TbleCaisse.RefCaisse=TbleOperations.RefCaisse INNER JOIN TbleAgency ON TbleAgency.RefAgency=TbleCaisse.RefAgency  WHERE TbleOperations.Reset_Id IS NULL AND TbleOperations.Insert_Time=:today AND TbleOperations.Insert_Id=:RefUsers ');
            $requeteCaisse->bindValue(':today', date('Y-m-d'), \PDO::PARAM_STR);
            $requeteCaisse->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
            $requeteCaisse->execute();
            $GetCaisse = $requeteCaisse->fetchAll();
            return $GetCaisse;
        } elseif ($_SESSION['statut'] == 'ChefCaisse') {
            $requeteCaisse = $this->dao->prepare('SELECT * FROM TbleOperations INNER JOIN TbleType ON TbleType.RefType=TbleOperations.RefType  INNER JOIN TbleCaisse ON TbleCaisse.RefCaisse=TbleOperations.RefCaisse INNER JOIN TbleAgency ON TbleAgency.RefAgency=TbleCaisse.RefAgency  WHERE TbleOperations.Reset_Id IS NULL AND TbleOperations.Insert_Time=:today AND TbleOperations.Insert_Id=:RefUsers ');
            $requeteCaisse->bindValue(':today', date('Y-m-d'), \PDO::PARAM_STR);
            $requeteCaisse->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
            $requeteCaisse->execute();
            $GetCaisse = $requeteCaisse->fetchAll();
            return $GetCaisse;
        } elseif ($_SESSION['statut'] == 'admin') {
            $requeteCaisse = $this->dao->prepare('SELECT * FROM TbleOperations INNER JOIN TbleType ON TbleType.RefType=TbleOperations.RefType INNER JOIN TbleCaisse ON TbleCaisse.RefCaisse=TbleOperations.RefCaisse INNER JOIN TbleAgency ON TbleAgency.RefAgency=TbleCaisse.RefAgency  WHERE TbleOperations.Reset_Id IS NULL AND TbleOperations.Insert_Time=:today ');
            $requeteCaisse->bindValue(':today', date('Y-m-d'), \PDO::PARAM_STR);
            $requeteCaisse->execute();
            $GetCaisse = $requeteCaisse->fetchAll();
            return $GetCaisse;
        } elseif ($_SESSION['statut'] == 'Niveau1') {
            $requeteCaisse = $this->dao->prepare('SELECT * FROM TbleOperations INNER JOIN TbleType ON TbleType.RefType=TbleOperations.RefType INNER JOIN TbleCaisse ON TbleCaisse.RefCaisse=TbleOperations.RefCaisse INNER JOIN TbleAgency ON TbleAgency.RefAgency=TbleCaisse.RefAgency WHERE TbleOperations.Reset_Id IS NULL AND TbleOperations.Insert_Time=:today ');
            $requeteCaisse->bindValue(':today', date('Y-m-d'), \PDO::PARAM_STR);
            $requeteCaisse->execute();
            $GetCaisse = $requeteCaisse->fetchAll();
            return $GetCaisse;
        }
    }
    public function GetInvoice($id)
    {
        $requeteGetInvoice = $this->dao->prepare("SELECT * FROM TbleBilletage INNER JOIN TbleOperations ON TbleOperations.RefOperations=TbleBilletage.RefOperations INNER JOIN TbleUsers ON TbleUsers.RefUsers=TbleOperations.Insert_Id WHERE  TbleBilletage.RefOperations=:RefOperations");
        $requeteGetInvoice->bindValue(':RefOperations', $id, \PDO::PARAM_INT);
        $requeteGetInvoice->execute();
        $dataInvoice = $requeteGetInvoice->fetch();
        $dataInvoice['NameAgency'] = $this->GetAgency($dataInvoice['RefCaisse']);
        return $dataInvoice;
    }

    public function GetAgency($Caisse)
    {
        $requete = $this->dao->prepare('SELECT * FROM TbleCaisse INNER JOIN TbleAgency ON TbleAgency.RefAgency=TbleCaisse.RefAgency WHERE TbleCaisse.RefCaisse=:RefCaisse');
        $requete->bindValue(':RefCaisse', $Caisse, \PDO::PARAM_INT);
        $requete->execute();
        $data = $requete->fetch();
        return $data['NameAgency'];
    }
    public function Add()
    {
        $date = date('Y-m-d');
        if (!empty($_POST['NumCompte']) && !empty($_POST['MontantVersement']) && !empty($_POST['NameClient']) && !empty($_POST['RefCaisse'])) {
            // $CheckAppro  = $this->CheckAppro($_POST['RefCaisse']);
            //  if (!empty($CheckAppro) or $_POST['RefType'] == 3) {
            $requeteAddversement = $this->dao->prepare('INSERT INTO TbleOperations(RefCaisse,NumCompte,NameClient,MontantVersement,Remarque,Insert_Id,Insert_Time,Approve1_Id,Approve1_Time,Approve2_Id,Approve2_Time,Bordereau,NameDeposant,RefType) VALUES(:RefCaisse,:NumCompte,:NameClient,:MontantVersement,:Remarque,:Insert_Id,:Insert_Time,:Approve1_Id,:Approve1_Time,:Approve2_Id,:Approve2_Time,:Bordereau,:NameDeposant,:RefType)');
            $requeteAddversement->bindValue(':RefCaisse', $_POST['RefCaisse'], \PDO::PARAM_INT);
            $requeteAddversement->bindValue(':NumCompte', $_POST['NumCompte'], \PDO::PARAM_STR);
            $requeteAddversement->bindValue(':NameClient', $_POST['NameClient'], \PDO::PARAM_STR);
            $requeteAddversement->bindValue(':MontantVersement', $_POST['MontantVersement'], \PDO::PARAM_STR);
            $requeteAddversement->bindValue(':Remarque', $_POST['Remarque'], \PDO::PARAM_STR);
            $requeteAddversement->bindValue(':Insert_Id', $_SESSION['RefUsers'], \PDO::PARAM_INT);
            $requeteAddversement->bindValue(':Insert_Time', $date, \PDO::PARAM_STR);
            $requeteAddversement->bindValue(':Approve1_Id', $_SESSION['RefUsers'], \PDO::PARAM_INT);
            $requeteAddversement->bindValue(':Approve1_Time', $date, \PDO::PARAM_STR);
            $requeteAddversement->bindValue(':Approve2_Id', $_SESSION['RefUsers'], \PDO::PARAM_INT);
            $requeteAddversement->bindValue(':Approve2_Time', $date, \PDO::PARAM_STR);
            $requeteAddversement->bindValue(':Bordereau', 'NULL', \PDO::PARAM_STR);
            $requeteAddversement->bindValue(':NameDeposant', $_POST['NameDeposant'], \PDO::PARAM_STR);
            $requeteAddversement->bindValue(':RefType', $_POST['RefType'], \PDO::PARAM_INT);
            $requeteAddversement->execute();
            $Refoperations = $this->dao->lastInsertId();
            $requetteBilletage = $this->dao->prepare('INSERT INTO TbleBilletage(RefOperations,a1,a2,b1,b2,c1,c2,d1,d2,e1,e2,f1,f2,g1,g2,h1,h2,i1,i2,j1,j2,k1,k2,l1,l2,m1,m2) VALUES(:RefOperations,:a1,:a2,:b1,:b2,:c1,:c2,:d1,:d2,:e1,:e2,:f1,:f2,:g1,:g2,:h1,:h2,:i1,:i2,:j1,:j2,:k1,:k2,:l1,:l2,:m1,:m2)');
            $requetteBilletage->bindValue(':RefOperations', $Refoperations, \PDO::PARAM_INT);
            $requetteBilletage->bindValue(':a1', $_POST['a1'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':a2', $_POST['a2'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':b1', $_POST['b1'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':b2', $_POST['b2'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':c1', $_POST['c1'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':c2', $_POST['c2'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':d1', $_POST['d1'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':d2', $_POST['d2'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':e1', $_POST['e1'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':e2', $_POST['e2'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':f1', $_POST['f1'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':f2', $_POST['f2'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':g1', $_POST['g1'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':g2', $_POST['g2'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':h1', $_POST['h1'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':h2', $_POST['h2'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':i1', $_POST['i1'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':i2', $_POST['i2'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':j1', $_POST['j1'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':j2', $_POST['j2'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':k1', $_POST['k1'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':k2', $_POST['k2'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':l1', $_POST['l1'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':l2', $_POST['l2'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':m1', $_POST['m1'], \PDO::PARAM_STR);
            $requetteBilletage->bindValue(':m2', $_POST['m2'], \PDO::PARAM_STR);
            $requetteBilletage->execute();
            header("location: /");
            $_SESSION['message']['type'] = 'success';
            $_SESSION['message']['text'] = 'Opération réussie !';
            $_SESSION['message']['number'] = 2;
            // } elseif ($CheckAppro == NULL) {
            // header("location: /");
            // $_SESSION['message']['type'] = 'error';
            //  $_SESSION['message']['text'] = "Approvisonner la Caisse en premier ! ";
            //  $_SESSION['message']['number'] = 2;
            // }
        } else {
            header("location: /");
            $_SESSION['message']['type'] = 'error';
            $_SESSION['message']['text'] = "Veuillez  reprendre l'operation. le Formulaire n'est pas remplit correctement,!";
            $_SESSION['message']['number'] = 2;
        }
    }

    public function YesterdaySolde()
    {
        $ChomdUser = $this->ChomdUser();
        $montant = 0;
        foreach ($ChomdUser as $key => $Caisse) {
            $Solde = $this->dao->prepare("SELECT Solde FROM TbleSolde WHERE DateSolde=(SELECT MAX(DateSolde) FROM TbleSolde WHERE RefCaisse=:RefCaisse) ");
            $Solde->bindValue(':RefCaisse', $Caisse['RefCaisse'], \PDO::PARAM_INT);
            $Solde->execute();
            $data = $Solde->fetch();
            $montant += $data['Solde'];
        }
        return $montant;
    }
    public function SommeVersement($Date)
    {

        if ($_SESSION['statut'] != 'admin') {

            $requeteSUm = $this->dao->prepare('SELECT SUM(MontantVersement) AS TotalVersment FROM TbleOperations  WHERE TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND Approve2_Time=:jour  AND TbleOperations.Insert_Id=:RefUsers AND (TbleOperations.RefType=1 OR TbleOperations.RefType=3)  ');
            $requeteSUm->bindValue(':jour', $Date, \PDO::PARAM_STR);
            $requeteSUm->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
            $requeteSUm->execute();
            $data = $requeteSUm->fetch();
            return $data['TotalVersment'];
        } else {
            $requeteSUm = $this->dao->prepare('SELECT SUM(MontantVersement) AS TotalVersment FROM TbleOperations  WHERE TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND Approve2_Time=:jour AND (TbleOperations.RefType=1 OR TbleOperations.RefType=3)  ');
            $requeteSUm->bindValue(':jour', $Date, \PDO::PARAM_STR);
            $requeteSUm->execute();
            $data = $requeteSUm->fetch();
            return $data['TotalVersment'];
        }
    }
    public function SommeRetrait($Date)
    {
        if ($_SESSION['statut'] != 'admin') {
            $requeteSUm = $this->dao->prepare('SELECT SUM(MontantVersement) AS TotalVersment FROM TbleOperations  WHERE TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND Approve2_Time=:jour  AND TbleOperations.Insert_Id=:RefUsers AND (TbleOperations.RefType=2 OR TbleOperations.RefType=4)  ');
            $requeteSUm->bindValue(':jour', $Date, \PDO::PARAM_STR);
            $requeteSUm->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
            $requeteSUm->execute();
            $data = $requeteSUm->fetch();
            return $data['TotalVersment'];
        } else {
            $requeteSUm = $this->dao->prepare('SELECT SUM(MontantVersement) AS TotalVersment FROM TbleOperations WHERE TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND Approve2_Time=:jour AND (TbleOperations.RefType=2 OR TbleOperations.RefType=4)  ');
            $requeteSUm->bindValue(':jour', $Date, \PDO::PARAM_STR);
            $requeteSUm->execute();
            $data = $requeteSUm->fetch();
            return $data['TotalVersment'];
        }
    }

    public  function getCurrentWeek()
    {
        $monday = strtotime("last monday");
        $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
        $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");
        $date['Debut']  = date("Y-m-d", $monday);
        $date['Fin'] = date("Y-m-d", $sunday);
        return $date;
    }

    public  function daysofweek()
    {

        $getCurrentWeek = $this->getCurrentWeek();
        $tableau = [];
        for ($i = 0; $i < 7; $i++) {
            $tableau[] = date('Y-m-d', strtotime($getCurrentWeek['Debut'] . " +" . $i . " days"));
        }
        return $tableau;
    }

    public function SommeVersementStatistique($Date)
    {
        if ($_SESSION['statut'] != 'admin') {

            $requeteSUm = $this->dao->prepare('SELECT SUM(MontantVersement) AS TotalVersment FROM TbleOperations  WHERE TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND Approve2_Time=:jour  AND TbleOperations.Insert_Id=:RefUsers AND (TbleOperations.RefType=1)  ');
            $requeteSUm->bindValue(':jour', $Date, \PDO::PARAM_STR);
            $requeteSUm->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
            $requeteSUm->execute();
            $data = $requeteSUm->fetch();
            return $data['TotalVersment'];
        } else {
            $requeteSUm = $this->dao->prepare('SELECT SUM(MontantVersement) AS TotalVersment FROM TbleOperations  WHERE TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND Approve2_Time=:jour  AND (TbleOperations.RefType=1) ');
            $requeteSUm->bindValue(':jour', $Date, \PDO::PARAM_STR);
            $requeteSUm->execute();
            $data = $requeteSUm->fetch();
            return $data['TotalVersment'];
        }
    }

    public function SommeRetraitStatistique($Date)
    {
        if ($_SESSION['statut'] != 'admin') {
            $requeteSUm = $this->dao->prepare('SELECT SUM(MontantVersement) AS TotalVersment FROM TbleOperations  WHERE TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND Approve2_Time=:jour  AND TbleOperations.Insert_Id=:RefUsers AND (TbleOperations.RefType=2)  ');
            $requeteSUm->bindValue(':jour', $Date, \PDO::PARAM_STR);
            $requeteSUm->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
            $requeteSUm->execute();
            $data = $requeteSUm->fetch();
            return $data['TotalVersment'];
        } else {
            $requeteSUm = $this->dao->prepare('SELECT SUM(MontantVersement) AS TotalVersment FROM TbleOperations WHERE TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND Approve2_Time=:jour AND (TbleOperations.RefType=2)  ');
            $requeteSUm->bindValue(':jour', $Date, \PDO::PARAM_STR);
            $requeteSUm->execute();
            $data = $requeteSUm->fetch();
            return $data['TotalVersment'];
        }
    }
    public function DailyVersement()
    {
        $jour = $this->daysofweek();
        $Result['LundiVersement'] = $this->SommeVersementStatistique($jour['0']);
        $Result['MardiVersement'] = $this->SommeVersementStatistique($jour['1']);
        $Result['MercrediVersement'] = $this->SommeVersementStatistique($jour['2']);
        $Result['JeudiVersement'] = $this->SommeVersementStatistique($jour['3']);
        $Result['VendrediVersement'] = $this->SommeVersementStatistique($jour['4']);
        $Result['SamediVersement'] = $this->SommeVersementStatistique($jour['5']);
        $Result['LundiRetrait'] = $this->SommeRetraitStatistique($jour['0']);
        $Result['MardiRetrait'] = $this->SommeRetraitStatistique($jour['1']);
        $Result['MercrediRetrait'] = $this->SommeRetraitStatistique($jour['2']);
        $Result['JeudiRetrait'] = $this->SommeRetraitStatistique($jour['3']);
        $Result['VendrediRetrait'] = $this->SommeRetraitStatistique($jour['4']);
        $Result['SamediRetrait'] = $this->SommeRetraitStatistique($jour['5']);
        return $Result;
    }
}