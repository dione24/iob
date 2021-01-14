<?php

namespace Applications\App\Modules\Caisse;

class CaisseController extends \Library\BackController
{
    public function executeIndex(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Ma Caisse"); // Titre de la page
        $Chmod  = $this->managers->getManagerOf("Bielletage")->CheckOuverture(); //Recuperation de la liste
        $this->page->addVar("CheckOuverture", $Chmod); // Creation de la variable, ajout d'une variable a la vue
        $UserCaisse  = $this->managers->getManagerOf("Caisse")->UserCaisse(); //Recuperation de la liste
        $this->page->addVar("UserCaisse", $UserCaisse); // Creation de 
        if (!empty($request->postData('RefCaisse'))) {
            $GetOperations  = $this->managers->getManagerOf("Caisse")->GetOperations($request->postData('RefCaisse')); //Recuperation de la liste
            $this->page->addVar("GetOperations", $GetOperations); // Creation de 
            $ResultCaisse = $this->managers->getManagerOf('Caisse')->ResultCaisse($request->postData('RefCaisse'));
            $this->page->addVar("Solde", $ResultCaisse); // Creation de 
        } else {
            $GetOperations  = $this->managers->getManagerOf("Caisse")->GetOperations(NULL); //Recuperation de la liste
            $this->page->addVar("GetOperations", $GetOperations); // Creation de 
            $ResultCaisse = $this->managers->getManagerOf('Caisse')->ResultCaisse(NULL);
            $this->page->addVar("Solde", $ResultCaisse); // Creation d
        }
    }

    public function executeTransfertfond(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Liste des Transfert de fond"); // Titre de la page
        $Fond  = $this->managers->getManagerOf("Caisse")->ListeFond(); //Recuperation de la liste
        $this->page->addVar("ListeFond", $Fond); // Creation d
        $UserCaisse  = $this->managers->getManagerOf("Journal")->UserCaisse(); //Recuperation de la liste
        $this->page->addVar("ListeCaisse", $UserCaisse); // Creation de la variable, ajout d'une variable a la vue
        if ($request->method() == 'POST') {
            $AddTransfert  = $this->managers->getManagerOf("Caisse")->AddTransfert($request); //Recuperation de la liste
            $_SESSION['message']['type'] = 'success';
            $_SESSION['message']['text'] = 'Ajout réussie !';
            $_SESSION['message']['number'] = 2;
            $this->app()->httpResponse()->redirect('/Caisse/transfertfond'); //Retour en arriere
        }
    }
    public function executeApprocaisse(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Liste des Appro"); // Titre de la page
        $Appro  = $this->managers->getManagerOf("Caisse")->ListeAppro(); //Recuperation de la liste
        $this->page->addVar("ListeAppro", $Appro); // Creation d
        $UserCaisse  = $this->managers->getManagerOf("Journal")->UserCaisse(); //Recuperation de la liste
        $this->page->addVar("ListeCaisse", $UserCaisse); // Creation de la variable, ajout d'une variable a la vue
        if ($request->method() == 'POST') {
            $AddAppro  = $this->managers->getManagerOf("Caisse")->AddAppro($request); //Recuperation de la liste
            $_SESSION['message']['type'] = 'success';
            $_SESSION['message']['text'] = 'Ajout réussie !';
            $_SESSION['message']['number'] = 2;
            $this->app()->httpResponse()->redirect('/Caisse/ApproCaisse'); //Retour en arriere
        }
    }
    public function executeDeleteTransfert(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Delete Transfert"); // Titre de la page
        $this->managers->getManagerOf("Caisse")->DeleteTransfert($request->getData('id')); //Recuperation de la liste
        $_SESSION['message']['type'] = 'success';
        $_SESSION['message']['text'] = 'Suppression réussie !';
        $_SESSION['message']['number'] = 2;
        $this->app()->httpResponse()->redirect('/Caisse/transfertfond'); //Retour en arriere
    }
    public function executeDeleteAppro(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Delete Appro"); // Titre de la page
        $this->managers->getManagerOf("Caisse")->DeleteAppro($request->getData('id')); //Recuperation de la liste
        $_SESSION['message']['type'] = 'success';
        $_SESSION['message']['text'] = 'Suppression réussie !';
        $_SESSION['message']['number'] = 2;
        $this->app()->httpResponse()->redirect('/Caisse/ApproCaisse'); //Retour en arriere
    }
}