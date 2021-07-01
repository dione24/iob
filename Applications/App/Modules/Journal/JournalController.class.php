<?php

namespace Applications\App\Modules\Journal;

class JournalController extends \Library\BackController
{
    public function executeIndex(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Journal de Caisse"); // Titre de la page
        $Chmod  = $this->managers->getManagerOf("Bielletage")->CheckOuverture(); //Recuperation de la liste
        $this->page->addVar("CheckOuverture", $Chmod); // Creation de la variable, ajout d'une variable a la vue
        $Agence  = $this->managers->getManagerOf("Pannel")->UserAgence();
        $this->page->addVar('UserAgence', $Agence);
        $this->page->addVar('Debut', $request->postData('Debut'));
        $this->page->addVar('Fin', $request->postData('Fin'));
        $this->page->addVar('Value', $request->postData('RefAgency'));
        $Biellet = $this->managers->getManagerOf('Journal')->GetBielletageJournal(NULL, NULL, NULL);
        $this->page->addVar('Biellet', $Biellet);
        if (!empty($request->postData('RefAgency')) or isset($_GET['value'])) {
            if (isset($_GET['debut']) && isset($_GET['fin']) && isset($_GET['value'])) {
                $Operations = $this->managers->getManagerOf('Journal')->GetOperations($_GET['debut'], $_GET['fin'], $_GET['value']);
                $this->page->addVar('Debut', $_GET['debut']);
                $this->page->addVar('Fin', $_GET['fin']);
                $this->page->addVar('Value', $_GET['value']);
            } else {
                $Operations = $this->managers->getManagerOf('Journal')->GetOperations($request->postData('Debut'), $request->postData('Fin'), $request->postData('RefAgency'));
                $this->page->addVar('Debut', $request->postData('Debut'));
                $this->page->addVar('Fin', $request->postData('Fin'));
                $this->page->addVar('Value', $request->postData('RefAgency'));
            }
            $this->page->addVar('Operations', $Operations);
            $sommeVersementPeriode = $this->managers->getManagerOf('Journal')->sommeVersementPeriode($request->postData('Debut'), $request->postData('Fin'), $request->postData('RefAgency'));
            $this->page->addVar('sommeVersementPeriode', $sommeVersementPeriode);
            $sommeRetraitPeriode = $this->managers->getManagerOf('Journal')->sommeRetraitPeriode($request->postData('Debut'), $request->postData('Fin'), $request->postData('RefAgency'));
            $this->page->addVar('sommeRetraitPeriode', $sommeRetraitPeriode);
            $sommeVersementPeriodeAvecAppro = $this->managers->getManagerOf('Journal')->sommeVersementPeriodeAvecAppro($request->postData('Debut'), $request->postData('Fin'), $request->postData('RefAgency'));
            $this->page->addVar('sommeVersementPeriodeAvecAppro', $sommeVersementPeriodeAvecAppro);
            $sommeRetraitPeriodeAvecSortie = $this->managers->getManagerOf('Journal')->sommeRetraitPeriodeAvecSortie($request->postData('Debut'), $request->postData('Fin'), $request->postData('RefAgency'));
            $this->page->addVar('sommeRetraitPeriodeAvecSortie', $sommeRetraitPeriodeAvecSortie);
            $Yesterday = $this->managers->getManagerOf('Journal')->YesterdaySoldeAgence($request->postData('Debut'), $request->postData('Fin'), $request->postData('RefAgency'));
            $Solde = ($Yesterday);
            $this->page->addVar('Solde', $Solde);
            $Biellet = $this->managers->getManagerOf('Journal')->GetBielletageJournal($request->postData('Debut'), $request->postData('Fin'), $request->postData('RefAgency'));
            $this->page->addVar('Biellet', $Biellet);
        } else {
            $Operations = $this->managers->getManagerOf('Journal')->Operations();
            $this->page->addVar('Operations', $Operations);
            $sommeVersementPeriode = $this->managers->getManagerOf('Journal')->sommeVersementPeriode();
            $this->page->addVar('sommeVersementPeriode', $sommeVersementPeriode);
            $sommeRetraitPeriode = $this->managers->getManagerOf('Journal')->sommeRetraitPeriode();
            $this->page->addVar('sommeRetraitPeriode', $sommeRetraitPeriode);
            $sommeVersementPeriodeAvecAppro = $this->managers->getManagerOf('Journal')->sommeVersementPeriodeAvecAppro();
            $this->page->addVar('sommeVersementPeriodeAvecAppro', $sommeVersementPeriodeAvecAppro);
            $sommeRetraitPeriodeAvecSortie = $this->managers->getManagerOf('Journal')->sommeRetraitPeriodeAvecSortie();
            $this->page->addVar('sommeRetraitPeriodeAvecSortie', $sommeRetraitPeriodeAvecSortie);
            $Yesterday = $this->managers->getManagerOf('Journal')->YesterdaySoldeAgence();
            $Solde = ($Yesterday +  ($sommeVersementPeriodeAvecAppro - $sommeRetraitPeriodeAvecSortie));
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
        $Agence  = $this->managers->getManagerOf("Pannel")->UserAgence(); //Recuperation de la liste
        foreach ($Agence as $key => $value) {
            $Agence[$key]['Afficher'] = $this->managers->getManagerOf("Journal")->CaisseAgence($value['RefAgency'], date('Y-m-d'));
            $Agence[$key]['validate'] = $this->managers->getManagerOf("Journal")->CheckDailyClose($value['RefAgency']);
            $Agence[$key]['YesterdayReserve'] = $this->managers->getManagerOf("Journal")->YesterdayReserve($value['RefAgency']);
            $Agence[$key]['SommeDepot'] = $this->managers->getManagerOf("Journal")->SommeDepotAgence(date('Y-m-d'), $value['RefAgency']);
            $Agence[$key]['SommeSortie'] = $this->managers->getManagerOf("Journal")->SommeRetraitAgence(date('Y-m-d'), $value['RefAgency']);
            $Agence[$key]['TotalAppoAgenceSansApproInitial'] = $this->managers->getManagerOf("Journal")->TotalApproAgenceSansApproInitial(date('Y-m-d'), $value['RefAgency']);
            $Agence[$key]['TotalSortieAgence'] = $this->managers->getManagerOf("Journal")->TotalSortieAgence(date('Y-m-d'), $value['RefAgency']);
            $Agence[$key]['ReserveActuelle'] = $Agence[$key]['YesterdayReserve'] + $Agence[$key]['SommeDepot'] - $Agence[$key]['SommeSortie'] +
                $Agence[$key]['TotalAppoAgenceSansApproInitial'] - $Agence[$key]['TotalSortieAgence'];
            $Agence[$key]['DayReserve'] =  $Agence[$key]['YesterdayReserve'] - $this->managers->getManagerOf("Journal")->TotalApproAgenceGlobal(date('Y-m-d'), $value['RefAgency']);
        }
        $this->page->addVar('Agence', $Agence);
    }
}