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
    abstract protected function SommeVersement($Date);
    abstract protected function SommeRetrait($Date);
    abstract protected function getCurrentWeek();
    abstract  protected function daysofweek();
    abstract protected function DailyVersement();
    abstract protected function SommeRetraitStatistique($Date);
    abstract protected function SommeVersementStatistique($Date);
    abstract protected function YesterdaySolde($Caisse = NULL);
    abstract protected function SommeVersementAgence($Caisse, $Date);
    abstract protected function SommeRetraitAgence($Caisse, $Date);
}