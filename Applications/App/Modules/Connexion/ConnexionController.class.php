<?php

namespace Applications\App\Modules\Connexion;

class ConnexionController extends \Library\BackController
{

    public function executeIndex(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Page de Connexion"); // Titre de la page
        $this->page->setTemplate('login');
        if ($request->method() == 'POST') {
            $User = $this->managers->getManagerOf('User')->login($request->postData('login'), $request->postData('password'));
            if (!empty($User)) {
                $this->app()->user()->setAuthenticated();
                $_SESSION['login'] = $User['login'];
                $_SESSION['NomUsers'] = $User['NomUsers'];
                $_SESSION['PrenomUsers'] = $User['PrenomUsers'];
                $_SESSION['RefUsers'] = $User['RefUsers'];
                $_SESSION['statut'] = $User['Name'];
                $this->app()->httpResponse()->redirect('/');
            }
        }
    }
    public function executeLogout(\Library\HTTPRequest $request)
    {
        $this->page->addVar('titles', 'Logout');
        $this->app()->user()->setAuthenticated(false); //deconnexion de user
        $this->app()->user()->setFlash('Logout Successul');
        $this->app()->httpResponse()->redirect('/');
    }
}