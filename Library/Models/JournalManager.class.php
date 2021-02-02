<?php

namespace Library\Models;

use \Library\Entities\Journal;

abstract class JournalManager extends \Library\Manager
{
    abstract protected function  Operations();
    abstract protected function GetOperations($debut, $fin, $caisse);
    abstract protected function UserCaisse();
    abstract protected function DeleteOperations($id);
    abstract protected function sommeVersementPeriode($debut, $fin, $caisse);
    abstract protected function sommeRetraitPeriode($debut, $fin, $caisse);
    abstract protected function GetBielletageJournal($debut, $fin, $caisse);
    abstract protected function Versement($debut, $fin, $caisse);
    abstract protected function Retrait($debut, $fin, $caisse);
    abstract protected function YesterdaySolde($debut, $fin, $caisse);
}