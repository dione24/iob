<?php
	namespace Library;

	abstract class FormBuilder {
		protected $form;

		abstract public function build();

		public function __construct(Entity $entity) {
			$this->setForm(new Form($entity));
		}
		public function form() {
			return $this->form;
		}
		public function setForm(Form $form) {
			$this->form = $form;
		}
	}