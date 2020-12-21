<?php

namespace Applications\Blog\Modules\Categories;

class CategoriesController extends \Library\BackController
{
    public function executeIndex(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Accueil"); // Titre de la page
        $Categories  = $this->managers->getManagerOf("Categories")->getListeOf(); //Recuperation de la liste
        $this->page->addVar("ListeCategories", $Categories); // Creation de la variable, ajout d'une variable a la vue
    }
    public function executeAdd(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Ajouter une categorie");
        if ($request->method() == 'POST') { //Verification de la methode send
            $Categories = new \Library\Entities\Categories(array('name' => $request->postData('name'), 'groupe' => $request->postData('groupe')));
            if ($Categories->isValid()) { //check de validite
                $this->managers->getManagerOf("Categories")->save($Categories);
                $this->app()->user()->setFlash('Complete');
                $this->app()->httpResponse()->redirect('/ListeCategories'); //Retour en arriere
            }
        }
    }
    public function executeUpdate(\Library\HTTPRequest $request)
    {
        $this->page->addVar('titles', 'Modifier une categorie');
        $Categories = $this->managers->getManagerOf('Categories')->get($request->getData('id'));
        if ($request->method() == 'POST') {
            $Categories = new  \Library\Entities\Categories(array('id' => $request->getData('id'), 'name' => $request->postData('name'), 'groupe' => $request->postData('groupe'),)); //Stockage des donnees du post
            if ($Categories->isValid()) {
                $this->managers->getManagerOf("Categories")->save($Categories); //Creation de la variable
                $this->app()->user()->setFlash('Complete');
                $this->app()->httpResponse()->redirect('/ListeCategories'); //Retour en arriere
            }
        }
        $this->page->addVar('Categories', $Categories);
    }
    public function executeDelete(\Library\HTTPRequest $request)
    {
        $this->page->addVar('titles', 'Suppression d\'une categorie');
        $this->managers->getManagerOf('Categories')->delete($request->getData('id'));
        $this->app()->user()->setFlash('Complete');
        $this->app()->httpResponse()->redirect('/ListeCategories');
    }
}
