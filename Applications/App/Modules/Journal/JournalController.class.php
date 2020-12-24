<?php

namespace Applications\App\Modules\Journal;

class JournalController extends \Library\BackController
{
    public function executeIndex(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Journal de Caisse"); // Titre de la page
        $UserCaisse  = $this->managers->getManagerOf("Journal")->UserCaisse(); //Recuperation de la liste
        $this->page->addVar("UserCaisse", $UserCaisse); // Creation de la variable, ajout d'une variable a la vue
        $this->page->addVar('Debut', $request->postData('Debut'));
        $this->page->addVar('Fin', $request->postData('Fin'));
        $this->page->addVar('Value', $request->postData('RefCaisse'));
        if (!empty($request->postData('RefCaisse'))) {
            $Operations = $this->managers->getManagerOf('Journal')->GetOperations($request->postData('Debut'), $request->postData('Fin'), $request->postData('RefCaisse'));
            $this->page->addVar('Operations', $Operations);
            $this->page->addVar('Debut', $request->postData('Debut'));
            $this->page->addVar('Fin', $request->postData('Fin'));
            $this->page->addVar('Value', $request->postData('RefCaisse'));
        } else {
            $Operations = $this->managers->getManagerOf('Journal')->Operations();
            $this->page->addVar('Operations', $Operations);
        }
    }
}