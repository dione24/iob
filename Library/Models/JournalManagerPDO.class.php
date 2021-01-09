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
}