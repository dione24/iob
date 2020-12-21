<?php
	namespace Library;

	abstract class Validator {
		protected $errorMessage;
		
		abstract public function isValid($value);

		public function __construct($errorMessage) {
			$this->setErrorMessage($errorMessage);
		}
		public function setErrorMessage($errorMessage) {
			if (is_string($errorMessage)) {
				$this->errorMessage = $errorMessage;
			}
		}
		public function errorMessage() {
			return $this->errorMessage;
		}
	}