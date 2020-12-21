<?php

namespace Applications\Blog\Modules\Users;

class UsersController extends \Library\BackController
{
    public function executeIndex(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Accueil"); // Titre de la page
        $Users  = $this->managers->getManagerOf("User")->getListeOf(); //Recuperation de la liste
        $this->page->addVar("ListeUsers", $Users); // Creation de la variable, ajout d'une variable a la vue
    }
    public function executeAdd(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Ajouter un Utilisateur");
        if ($request->method() == 'POST') { //Verification de la methode send
            $Users = new \Library\Entities\User(array('login' => $request->postData('login'), 'password' => $request->postData('password')));
            if ($Users->isValid()) { //check de validite
                $this->managers->getManagerOf("User")->save($Users);
                $this->app()->user()->setFlash('Complete');
                $this->app()->httpResponse()->redirect('/ListeUsers'); //Retour en arriere
            }
        }
    }
    public function executeUpdate(\Library\HTTPRequest $request)
    {
        $this->page->addVar('titles', 'Modifier un Utilisateur');
        $Users = $this->managers->getManagerOf('User')->get($request->getData('id'));
        if ($request->method() == 'POST') {
            $Users = new  \Library\Entities\User(array('id' => $request->getData('id'), 'login' => $request->postData('login'), 'password' => $request->postData('password'))); //Stockage des donnees du post
            if ($Users->isValid()) {
                $this->managers->getManagerOf("User")->save($Users); //Creation de la variable
                $this->app()->user()->setFlash('Complete');
                $this->app()->httpResponse()->redirect('/ListeUsers'); //Retour en arriere
            }
        }
        $this->page->addVar('User', $Users);
    }

    public function executeDelete(\Library\HTTPRequest $request)
    {
        $this->page->addVar('titles', 'Suppression d\'un Article');
        $this->managers->getManagerOf('User')->delete($request->getData('id'));
        $this->app()->user()->setFlash('Complete');
        $this->app()->httpResponse()->redirect('/ListeUsers');
    }
}
