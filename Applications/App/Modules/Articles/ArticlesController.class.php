<?php

namespace Applications\Blog\Modules\Articles;

class ArticlesController extends \Library\BackController
{
	public function executeIndex(\Library\HTTPRequest $request)
	{
		$this->page->addVar("titles", "Accueil"); // Titre de la page
		$Articles  = $this->managers->getManagerOf("Article")->getListeOf(); //Recuperation de la liste
		$this->page->addVar("ListeArticle", $Articles); // Creation de la variable, ajout d'une variable a la vue
	}
	public function executeAdd(\Library\HTTPRequest $request)
	{
		$this->page->addVar("titles", "Ajouter un Article");
		if ($request->method() == 'POST') { //Verification de la methode send
			$Articles = new \Library\Entities\Article(array('Title' => $request->postData('Title'), 'Content' => $request->postData('Content')));
			if ($Articles->isValid()) { //check de validite
				$this->managers->getManagerOf("Article")->save($Articles);
				$this->app()->user()->setFlash('Complete');
				$this->app()->httpResponse()->redirect('/'); //Retour en arriere
			}
		}
	}
	public function executeUpdate(\Library\HTTPRequest $request)
	{
		$this->page->addVar('titles', 'Modifier un Article');
		$Articles = $this->managers->getManagerOf('Article')->get($request->getData('id'));
		if ($request->method() == 'POST') {
			$Articles = new  \Library\Entities\Article(array('id' => $request->getData('id'), 'Title' => $request->postData('Title'), 'Content' => $request->postData('Content'), 'Date' => $request->postData('Date'))); //Stockage des donnees du post
			if ($Articles->isValid()) {
				$this->managers->getManagerOf("Article")->save($Articles); //Creation de la variable
				$this->app()->user()->setFlash('Complete');
				$this->app()->httpResponse()->redirect('/'); //Retour en arriere
			}
		}
		$this->page->addVar('Article', $Articles);
	}
	public function executeDelete(\Library\HTTPRequest $request)
	{
		$this->page->addVar('titles', 'Suppression d\'un Article');
		$this->managers->getManagerOf('Article')->delete($request->getData('id'));
		$this->app()->user()->setFlash('Complete');
		$this->app()->httpResponse()->redirect('/');
	}
	public function executeSearch(\Library\HTTPRequest $request)
	{
		$this->page->addVar("titles", "Resultat Recherche"); // Titre de la page
		$Search  = $this->managers->getManagerOf("Article")->search($request->postData('Title')); //Recuperation de la liste
		$this->page->addVar("Resultat", $Search); // Creation de la variable, ajout d'une variable a la vue
	}
}
