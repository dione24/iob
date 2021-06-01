<?php

namespace Library\Models;

use \Library\Entities\Analytics;

class AnalyticsManagerPDO extends AnalyticsManager
{

    public function GetOperations($debut, $fin)
    {
        $requete = $this->dao->prepare("SELECT * FROM TbleOperations INNER JOIN TbleType ON TbleType.RefType=TbleOperations.RefType INNER JOIN TbleCaisse ON TbleCaisse.RefCaisse=TbleOperations.RefCaisse INNER JOIN TbleAgency ON TbleAgency.RefAgency=TbleCaisse.RefAgency INNER JOIN TbleUsers ON TbleUsers.Refusers=TbleOperations.Insert_Id    WHERE TbleOperations.Approve2_Id IS NOT NULL AND TbleOperations.Reset_Id IS NULL AND  date(TbleOperations.Approve2_Time) BETWEEN '$debut' AND '$fin'  AND(TbleOperations.Reftype=1 OR TbleOperations.Reftype=2 ) ORDER BY TbleOperations.datePayement ASC");
        $requete->execute();
        $data = $requete->fetchAll();
        return $data;
    }
}