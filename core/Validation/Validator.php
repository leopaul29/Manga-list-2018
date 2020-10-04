<?php

namespace Core\Validation;

/**
* Form Validator
*/
class Validator {

	/**
	* $_POST from form submitting
	*/
	private $datas = [];
	/**
	* form errors
	*/
	private $errors = [];

	/**
	* new Validator
	*/
	public function __construct($datas) {
		$this->datas = $datas;
	}

	/**
	* check a value post with a rule
	*/
	public function check($name, $rule, $options = false) {
		// build method to call for a validation
		$validator = "validate_$rule";
		if(!$this->$validator($name, $options)) {
			// store error for field value
			$this->errors[$name] = "The field $name has not been correctly filled";
		}
	}

	/**
	* check a field value exist and is not empty
	*/
	public function validate_required($name) {
		return array_key_exists($name, $this->datas) && $this->datas[$name] != '';
	}

	/**
	* check a field value exist and is an email
	*/
	public function validate_email($name) {
		return array_key_exists($name, $this->datas) && filter_var($this->datas['email'], FILTER_VALIDATE_EMAIL);
	}

	/**
	* check a field value exist and is in array
	*/
	public function validate_in($name, $values) {
		return array_key_exists($name, $this->datas) && in_array($this->datas[$name], $values);
	}

	/**
	* get errors from validation
	*/
	public function errors() {
		return $this->errors;
	}
}