<?php

namespace Applications\App\Modules\Journal;

class JournalController extends \Library\BackController
{
    public function executeIndex(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Journal de Caisse"); // Titre de la page
        $Chmod  = $this->managers->getManagerOf("Bielletage")->CheckOuverture(); //Recuperation de la liste
        $this->page->addVar("CheckOuverture", $Chmod); // Creation de la variable, ajout d'une variable a la vue
        $UserCaisse  = $this->managers->getManagerOf("Journal")->UserCaisse(); //Recuperation de la liste
        $this->page->addVar("UserCaisse", $UserCaisse); // Creation de la variable, ajout d'une variable a la vue
        $this->page->addVar('Debut', $request->postData('Debut'));
        $this->page->addVar('Fin', $request->postData('Fin'));
        $this->page->addVar('Value', $request->postData('RefCaisse'));
        $Biellet = $this->managers->getManagerOf('Journal')->GetBielletageJournal(NULL, NULL, NULL);
        $this->page->addVar('Biellet', $Biellet);
        if (!empty($request->postData('RefCaisse'))) {
            $Operations = $this->managers->getManagerOf('Journal')->GetOperations($request->postData('Debut'), $request->postData('Fin'), $request->postData('RefCaisse'));
            $this->page->addVar('Operations', $Operations);
            $this->page->addVar('Debut', $request->postData('Debut'));
            $this->page->addVar('Fin', $request->postData('Fin'));
            $this->page->addVar('Value', $request->postData('RefCaisse'));
            $sommeVersementPeriode = $this->managers->getManagerOf('Journal')->sommeVersementPeriode($request->postData('Debut'), $request->postData('Fin'), $request->postData('RefCaisse'));
            $Yesterday = $this->managers->getManagerOf('Journal')->YesterdaySolde($request->postData('Debut'), $request->postData('Fin'), $request->postData('RefCaisse'));
            $this->page->addVar('sommeVersementPeriode', $sommeVersementPeriode + $Yesterday);
            $sommeRetraitPeriode = $this->managers->getManagerOf('Journal')->sommeRetraitPeriode($request->postData('Debut'), $request->postData('Fin'), $request->postData('RefCaisse'));
            $this->page->addVar('sommeRetraitPeriode', $sommeRetraitPeriode);
            $Biellet = $this->managers->getManagerOf('Journal')->GetBielletageJournal($request->postData('Debut'), $request->postData('Fin'), $request->postData('RefCaisse'));
            $this->page->addVar('Biellet', $Biellet);
        } else {
            $Operations = $this->managers->getManagerOf('Journal')->Operations();
            $this->page->addVar('Operations', $Operations);
            $sommeVersementPeriode = $this->managers->getManagerOf('Journal')->sommeVersementPeriode();
            $Yesterday = $this->managers->getManagerOf('Bielletage')->YesterdaySolde(date('Y-m-d'));
            $this->page->addVar('sommeVersementPeriode', $sommeVersementPeriode);
            $sommeRetraitPeriode = $this->managers->getManagerOf('Journal')->sommeRetraitPeriode();
            $this->page->addVar('sommeRetraitPeriode', $sommeRetraitPeriode);
        }
    }
    public function executeDelete(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Suppresion "); // Titre de la page
        $this->managers->getManagerOf("Journal")->DeleteOperations($request->getData('id'));
        $this->app()->httpResponse()->redirect('/Journal/index'); //Retour en arriere
    }
}