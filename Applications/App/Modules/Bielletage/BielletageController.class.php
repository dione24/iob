<?php

namespace Applications\App\Modules\Bielletage;

class BielletageController extends \Library\BackController
{
    public function executeIndex(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Accueil"); // Titre de la page
        $Chmod  = $this->managers->getManagerOf("Bielletage")->CheckOuverture(); //Recuperation de la liste
        $this->page->addVar("CheckOuverture", $Chmod); // Creation de la variable, ajout d'une variable a la vue
        $Operations = $this->managers->getManagerOf('Bielletage')->GetCaisse();
        $this->page->addVar('Operation', $Operations);
        $Biellet = $this->managers->getManagerOf('Arreter')->GetDailyBielletage(date('Y-m-d'));
        $this->page->addVar('Biellet', $Biellet);
        $SommeVersement = $this->managers->getManagerOf('Bielletage')->SommeVersement(date('Y-m-d'));
        $this->page->addVar('SommeVersement', $SommeVersement);
        $SommeRetrait = $this->managers->getManagerOf('Bielletage')->SommeRetrait(date('Y-m-d'));
        $this->page->addVar('SommeRetrait', $SommeRetrait);
        $DailyVersement = $this->managers->getManagerOf('Bielletage')->DailyVersement();
        $this->page->addVar('DailyVersement', $DailyVersement);
        $Yesterday = $this->managers->getManagerOf('Bielletage')->YesterdaySolde();
        $Solde = $SommeVersement - $SommeRetrait;
        $this->page->addVar('Solde', $Solde);
        $SommeVersementAgence = $this->managers->getManagerOf('Bielletage')->SommeVersementAgence(NULL, date('Y-m-d'));
        $this->page->addVar('SommeVersementAgence', $SommeVersementAgence);
        $SommeRetraitAgence = $this->managers->getManagerOf('Bielletage')->SommeRetraitAgence(NULL, date('Y-m-d'));
        $this->page->addVar('SommeRetraitAgence', $SommeRetraitAgence);
        $SoldeAgence = $SommeVersementAgence - $SommeRetraitAgence + $Yesterday;
        $this->page->addVar('SoldeAgence', $SoldeAgence);
    }
    public function executeStopcaisse(\Library\HTTPRequest $request)
    {
        $SommeVersement = $this->managers->getManagerOf('Bielletage')->SommeVersementAgence($request->getData('id'), date('Y-m-d'));
        $this->page->addVar('SommeVersement', $SommeVersement);
        $SommeRetrait = $this->managers->getManagerOf('Bielletage')->SommeRetraitAgence($request->getData('id'), date('Y-m-d'));
        $this->page->addVar('SommeRetrait', $SommeRetrait);
        $Yesterday = $this->managers->getManagerOf('Bielletage')->YesterdaySolde($request->getData('id'));
        $Solde = $SommeVersement - $SommeRetrait + $Yesterday;
        $this->managers->getManagerOf('Arreter')->StopCaisse($request->getData('id'), $Solde);
        $this->app()->httpResponse()->redirect('/Arreter/index'); //Retour en arriere
    }
    public function executeBielletage(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Nouvelle Opération"); // Titre de la page
        $Chmod  = $this->managers->getManagerOf("Bielletage")->CheckOuverture(); //Recuperation de la liste
        $this->page->addVar("CheckOuverture", $Chmod); // Creation de la variable, ajout d'une variable a la vue
    }
    public function executeInvoice(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Bordereau"); // Titre de la page
        $this->page->setTemplate('bordereau');
        if ($request->method() == 'POST') {
            $reference  = $request->postData('id');
        } else {
            $reference = $request->getData('id');
        }
        $Invoice  = $this->managers->getManagerOf("Bielletage")->GetInvoice($reference); //Recuperation de la liste
        $this->page->addVar("GetInvoice", $Invoice); // Creation de la variable, ajout d'une variable a la vue
    }
    public function executeAdd(\Library\HTTPRequest $request)
    {
        $this->managers->getManagerOf("Bielletage")->Add(); //Recuperation de la liste
    }
}