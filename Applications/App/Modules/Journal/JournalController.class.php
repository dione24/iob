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
        if (!empty($request->postData('RefCaisse')) or isset($_GET['value'])) {
            if (isset($_GET['debut']) && isset($_GET['fin']) && isset($_GET['value'])) {
                $Operations = $this->managers->getManagerOf('Journal')->GetOperations($_GET['debut'], $_GET['fin'], $_GET['value']);
                $this->page->addVar('Debut', $_GET['debut']);
                $this->page->addVar('Fin', $_GET['fin']);
                $this->page->addVar('Value', $_GET['value']);
            } else {
                $Operations = $this->managers->getManagerOf('Journal')->GetOperations($request->postData('Debut'), $request->postData('Fin'), $request->postData('RefCaisse'));
                $this->page->addVar('Debut', $request->postData('Debut'));
                $this->page->addVar('Fin', $request->postData('Fin'));
                $this->page->addVar('Value', $request->postData('RefCaisse'));
            }
            $this->page->addVar('Operations', $Operations);
            $sommeVersementPeriode = $this->managers->getManagerOf('Journal')->sommeVersementPeriode($request->postData('Debut'), $request->postData('Fin'), $request->postData('RefCaisse'));
            $Yesterday = $this->managers->getManagerOf('Journal')->YesterdaySolde($request->postData('Debut'), $request->postData('Fin'), $request->postData('RefCaisse'));
            $this->page->addVar('sommeVersementPeriode', $sommeVersementPeriode);
            $sommeRetraitPeriode = $this->managers->getManagerOf('Journal')->sommeRetraitPeriode($request->postData('Debut'), $request->postData('Fin'), $request->postData('RefCaisse'));
            $this->page->addVar('sommeRetraitPeriode', $sommeRetraitPeriode);
            $Solde = $sommeVersementPeriode - $sommeRetraitPeriode + $Yesterday;
            $this->page->addVar('Solde', $Solde);
            $Biellet = $this->managers->getManagerOf('Journal')->GetBielletageJournal($request->postData('Debut'), $request->postData('Fin'), $request->postData('RefCaisse'));
            $this->page->addVar('Biellet', $Biellet);
        } else {
            $Operations = $this->managers->getManagerOf('Journal')->Operations();
            $this->page->addVar('Operations', $Operations);
            $sommeVersementPeriode = $this->managers->getManagerOf('Journal')->sommeVersementPeriode();
            $this->page->addVar('sommeVersementPeriode', $sommeVersementPeriode);
            $sommeRetraitPeriode = $this->managers->getManagerOf('Journal')->sommeRetraitPeriode();
            $this->page->addVar('sommeRetraitPeriode', $sommeRetraitPeriode);
            $Yesterday = $this->managers->getManagerOf('Bielletage')->YesterdaySolde();
            $Solde = (($sommeVersementPeriode - $sommeRetraitPeriode) + $Yesterday);
            $this->page->addVar('Solde', $Solde);
        }
    }
    public function executeValidate(\Library\HTTPRequest $request)
    {
        $this->managers->getManagerOf("Journal")->ValidateOperations($request);
        $this->app()->httpResponse()->redirect("/Journal/index/" . $request->postData('Debut') . "/" . $request->postData('Fin') . "/" . $request->postData('RefCaisse')); //Retour en arriere
    }

    public function executeCancelvalidate(\Library\HTTPRequest $request)
    {
        $this->managers->getManagerOf("Journal")->CancelValidate($request->getData('id'));
        $this->app()->httpResponse()->redirect("/Journal/index"); //Retour en arriere
    }

    public function executeDelete(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Suppresion "); // Titre de la page
        $this->managers->getManagerOf("Journal")->DeleteOperations($request->getData('id'));
        $this->app()->httpResponse()->redirect('/Journal/index'); //Retour en arriere
    }

    public function executePetitecaisse(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Petite Caisse"); // Titre de la page
        $Caisse  = $this->managers->getManagerOf("Arreter")->GetListeCaisse(); //Recuperation de la liste
        foreach ($Caisse as $key => $value) {
            if (!empty($request->postData('jour'))) {
                $Caisse[$key]['FullName'] =  $this->managers->getManagerOf('Journal')->GetCaissier($request->postData('jour'), $value['RefCaisse']);
                $Caisse[$key]['Versement'] = $this->managers->getManagerOf('Journal')->sommeVersementPeriode($request->postData('jour'), $request->postData('jour'), $value['RefCaisse']);
                $Caisse[$key]['Yesterday'] = $this->managers->getManagerOf('Journal')->YesterdaySolde($request->postData('jour'), $request->postData('jour'), $value['RefCaisse']);
                $Caisse[$key]['Retrait'] = $this->managers->getManagerOf('Journal')->sommeRetraitPeriode($request->postData('jour'), $request->postData('jour'), $value['RefCaisse']);
                $Caisse[$key]['SoldeGlobal'] = ($Caisse[$key]['Versement'] - $Caisse[$key]['Retrait']) +  $Caisse[$key]['Yesterday'];
            } else {
                $Caisse[$key]['FullName'] =  $this->managers->getManagerOf('Journal')->GetCaissier(date('Y-m-d'), $value['RefCaisse']);
                $Caisse[$key]['Versement'] = $this->managers->getManagerOf('Journal')->sommeVersementPeriode(date('Y-m-d'), date('Y-m-d'), $value['RefCaisse']);
                $Caisse[$key]['Yesterday'] = $this->managers->getManagerOf('Journal')->YesterdaySolde(date('Y-m-d'), date('Y-m-d'), $value['RefCaisse']);
                $Caisse[$key]['Retrait'] = $this->managers->getManagerOf('Journal')->sommeRetraitPeriode(date('Y-m-d'), date('Y-m-d'), $value['RefCaisse']);
                $Caisse[$key]['SoldeGlobal'] = ($Caisse[$key]['Versement'] - $Caisse[$key]['Retrait']) +  $Caisse[$key]['Yesterday'];
            }
        }
        $this->page->addVar('Caisse', $Caisse);
        $this->page->addVar('jour', $request->postData('jour'));
    }
}