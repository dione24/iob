<?php
	namespace Library;

	class NumberField extends Field {
		protected $minValue;
		protected $maxValue;
		
		public function buildWidget() {
			$widget = '';

			if (!empty($this->errorMessage)) {
				$widget .= $this->errorMessage.'<br/>';
			}
			$widget .= '<label>'.$this->label.'</label><input type="number" name="'.$this->name.'"';
			if (!empty($this->value)) {
				$widget .= ' value="'.htmlspecialchars($this->value).'"';
			}
			if (!empty($this->minValue)) {
				$widget .= ' min="'.$this->minValue.'"';
			}
			if (!empty($this->maxValue)) {
				$widget .= ' max="'.$this->maxValue.'"';
			}
			return $widget .= '/>';
		}
		public function setMinValue($value)	{
			$this->minValue = (int)$value;
		}
		public function setMaxValue($value) {
			$this->maxValue = (int)$value;
		}
	}