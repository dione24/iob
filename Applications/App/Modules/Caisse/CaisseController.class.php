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
}