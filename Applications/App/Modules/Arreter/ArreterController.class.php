<?php

namespace Applications\App\Modules\Arreter;

class ArreterController extends \Library\BackController
{
    public function executeIndex(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Arreter de Caisse"); // Titre de la page
        $Chmod  = $this->managers->getManagerOf("Bielletage")->CheckOuverture(); //Recuperation de la liste
        $this->page->addVar("CheckOuverture", $Chmod); // Creation de la variable, ajout d'une variable a la vue
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
        $ListeSolde = $this->managers->getManagerOf('Arreter')->ListeSolde($request->getData('id'));
        $this->page->addVar('ListeSolde', $ListeSolde);
        $Biellet = $this->managers->getManagerOf('Arreter')->GetDailyBielletage('2020-12-25');
        $this->page->addVar('Biellet', $Biellet);
    }

    public function executeDelete(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Suppresion "); // Titre de la page
        $this->managers->getManagerOf("Arreter")->DeleteSolde($request->getData('id'));
        $this->app()->httpResponse()->redirect('/Arreter/index'); //Retour en arriere
    }
}