<?php

namespace Applications\App\Modules\Arreter;

class ArreterController extends \Library\BackController
{
    public function executeIndex(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Arreter de Caisse"); // Titre de la page
        $Caisse  = $this->managers->getManagerOf("Arreter")->GetListeCaisse(); //Recuperation de la liste
        $this->page->addVar('Caisse', $Caisse);
    }
    public function executeClose(\Library\HTTPRequest $request)
    {
        $Caisse = $this->managers->getManagerOf("Arreter")->CloseCaisse($request->getData('id')); //Recuperation de la liste
    }
    public function executeView(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Liste des Rapports "); // Titre de la page
        $GetRapports = $this->managers->getManagerOf('Arreter')->GetRapports($request->getData('id'));
        $this->page->addVar('GetRapports', $GetRapports);
        $Biellet = $this->managers->getManagerOf('Arreter')->GetDailyBielletage(date('Y-m-d'));
        $this->page->addVar('Biellet', $Biellet);
    }
}