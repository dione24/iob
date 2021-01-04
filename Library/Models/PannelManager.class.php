<?php

namespace Library\Models;

use \Library\Entities\Pannel;

abstract class PannelManager extends \Library\Manager
{
    abstract protected function  ListeAgence();
    abstract protected function AddCaisse();
    abstract protected function ListeDays();
    abstract protected function AddOuverture();
    abstract protected function DeleteAgence($Agence);
    abstract protected function ListeBanque();
    abstract protected function AddAgency();
    abstract protected function AddBanque();
    abstract protected function DeleteBanque($Banque);
    abstract protected function VerifOpening($Caisse, $Days);
}