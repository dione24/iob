<?php

namespace Library\Models;

use \Library\Entities\Pannel;

class PannelManagerPDO extends PannelManager
{


    public function ListeAgence()
    {
        $requeteAgence = $this->dao->prepare('SELECT * FROM TbleAgency');
        $requeteAgence->execute();
        $ListeAgence = $requeteAgence->fetchAll();
        return $ListeAgence;
    }

    public function UserAgence()
    {
        $requeteAgence = $this->dao->prepare('SELECT * FROM TbleAgency INNER JOIN TbleCaisse ON TbleCaisse.RefAgency=TbleAgency.RefAgency INNER JOIN TbleChmod ON TbleChmod.RefCaisse=TbleCaisse.RefCaisse WHERE TbleChmod.RefUsers=:RefUsers GROUP BY(TbleAgency.RefAgency)');
        $requeteAgence->bindValue(':RefUsers', $_SESSION['RefUsers'], \PDO::PARAM_INT);
        $requeteAgence->execute();
        $ListeAgence = $requeteAgence->fetchAll();
        return $ListeAgence;
    }


    public function GetAgency($id)
    {
        $requeteAgence = $this->dao->prepare('SELECT * FROM TbleAgency WHERE RefAgency=:RefAgency');
        $requeteAgence->bindValue(':RefAgency', $id, \PDO::PARAM_INT);
        $requeteAgence->execute();
        $ListeAgence = $requeteAgence->fetch();
        return $ListeAgence;
    }


    public function ListeProduit()
    {
        $requeteProduuit = $this->dao->prepare('SELECT * FROM TbleProduit INNER JOIN TbleBanque ON TbleBanque.RefBanque=TbleProduit.RefBanque');
        $requeteProduuit->execute();
        $ListeProduit = $requeteProduuit->fetchAll();
        return $ListeProduit;
    }


    public function AddProduit()
    {
        $requeteAdd = $this->dao->prepare("INSERT INTO TbleProduit(NameProduit,RefBanque) VALUES(:NameProduit,:RefBanque)");
        $requeteAdd->bindValue(':NameProduit', $_POST['NameProduit'], \PDO::PARAM_STR);
        $requeteAdd->bindValue(':RefBanque', $_POST['RefBanque'], \PDO::PARAM_INT);
        $requeteAdd->execute();
    }

    public function DeleteProduit($Produit)
    {
        $requete = $this->dao->prepare('DELETE FROM TbleProduit WHERE RefProduit=:RefProduit');
        $requete->bindValue(':RefProduit', $Produit, \PDO::PARAM_INT);
        $requete->execute();
    }

    public function AddCaisse()
    {
        $requeteAddService = $this->dao->prepare("INSERT INTO TbleCaisse(NameCaisse,RefAgency) VALUES(:NameCaisse,:RefAgency)");
        $requeteAddService->bindValue(':NameCaisse', $_POST['NameCaisse'], \PDO::PARAM_STR);
        $requeteAddService->bindValue(':RefAgency', $_POST['RefAgency'], \PDO::PARAM_INT);
        $requeteAddService->execute();
    }
    public function ListeCaisse()
    {
        $requeteAgence = $this->dao->prepare('SELECT * FROM TbleCaisse INNER JOIN TbleAgency ON TbleAgency.RefAgency=TbleCaisse.RefAgency');
        $requeteAgence->execute();
        $ListeCaisse = $requeteAgence->fetchAll();
        return $ListeCaisse;
    }
    public function ListeDays()
    {
        $requeteDays = $this->dao->prepare('SELECT * FROM TbleDays');
        $requeteDays->execute();
        $ListeDays = $requeteDays->fetchAll();
        return $ListeDays;
    }
    public function AddOuverture()
    {
        $requeteDelete = $this->dao->prepare('DELETE FROM TbleOuverture WHERE RefCaisse=:RefCaisse');
        $requeteDelete->bindValue(':RefCaisse', $_POST['RefCaisse'], \PDO::PARAM_INT);
        $requeteDelete->execute();
        if (!empty($_POST['RefDays'])) {
            foreach ($_POST['RefDays'] as $key => $value) {
                $requeteInsert = $this->dao->prepare('INSERT INTO TbleOuverture(RefCaisse,RefDays,HeureDebut,HeureFin) VALUES(:caisse,:days,:debut,:fin)');
                $requeteInsert->bindValue(':caisse', $_POST['RefCaisse'], \PDO::PARAM_INT);
                $requeteInsert->bindValue(':days', $_POST['RefDays'][$key], \PDO::PARAM_INT);
                $requeteInsert->bindValue(':debut', $_POST['HeureDebut'][$key], \PDO::PARAM_STR);
                $requeteInsert->bindValue(':fin', $_POST['HeureFin'][$key], \PDO::PARAM_STR);
                $requeteInsert->execute();
                header("location: /Pannel/Caisse");
                $_SESSION['flash']['success'] = "Opération Effectuée";
            }
        }
    }
    public function DeleteAgence($Agence)
    {
        $requete = $this->dao->prepare('DELETE FROM TbleAgency WHERE RefAgency=:RefAgency');
        $requete->bindValue(':RefAgency', $Agence, \PDO::PARAM_INT);
        $requete->execute();
    }
    public function ListeBanque()
    {
        $requeteBanque = $this->dao->prepare('SELECT * FROM TbleBanque');
        $requeteBanque->execute();
        $ListeBanque = $requeteBanque->fetchAll();
        return $ListeBanque;
    }
    public function AddAgency()
    {
        $requeteAddService = $this->dao->prepare("INSERT INTO TbleAgency(NameAgency) VALUES(:NameAgency)");
        $requeteAddService->bindValue(':NameAgency', $_POST['NameAgency'], \PDO::PARAM_STR);
        $requeteAddService->execute();
    }

    public function AddBanque()
    {
        $requete = $this->dao->prepare("INSERT INTO TbleBanque(NameBanque) VALUES(:NameBanque)");
        $requete->bindValue(':NameBanque', $_POST['NameBanque'], \PDO::PARAM_STR);
        $requete->execute();
    }
    public function DeleteBanque($Banque)
    {
        $requete = $this->dao->prepare('DELETE FROM TbleBanque WHERE RefBanque=:RefBanque');
        $requete->bindValue(':RefBanque', $Banque, \PDO::PARAM_INT);
        $requete->execute();
    }
    public function VerifOpening($Caisse, $Days)
    {

        $requete = $this->dao->prepare('SELECT * FROM TbleOuverture WHERE RefCaisse=:RefCaisse AND RefDays=:RefDays');
        $requete->bindValue(':RefCaisse', $Caisse, \PDO::PARAM_INT);
        $requete->bindValue(':RefDays', $Days, \PDO::PARAM_INT);
        $requete->execute();
        $data = $requete->fetch();
        return $data;
    }
}