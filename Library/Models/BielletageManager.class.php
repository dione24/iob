<?php

namespace Library\Models;

use \Library\Entities\Bielletage;

abstract class BielletageManager extends \Library\Manager
{
    abstract protected function CheckOuverture();
    abstract protected function ChomdUser();
    abstract protected function GetCaisse();
    abstract protected function GetInvoice($id);
    abstract protected function GetAgency($Caisse);
    abstract protected function CheckAfterRapport($Caisse);
    abstract protected function Add();
}