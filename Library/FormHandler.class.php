<?php
	namespace Library;

	class FormHandler {
		protected $form;
		protected $manager;
		protected $httpRequest;

		public function __construct(\Library\Form $form,\Library\Manager $manager,\Library\HTTPRequest $httpRequest) {
			$this->setForm($form);
			$this->setManager($manager);
			$this->setRequest($httpRequest);
		}
		public function process() {
			if ($this->httpRequest->method() == 'POST' && $this->form->isValid()) {
				$this->manager->save($this->form->entity());
				return true;
			}
			return false;
		}
		public function setForm(\Library\Form $form) {
			$this->form = $form;
		}
		public function setManager(\Library\Manager $manager) {
			$this->manager = $manager;
		}
		public function setRequest(\Library\HTTPRequest $httpRequest) {
			$this->httpRequest = $httpRequest;
		}
	}