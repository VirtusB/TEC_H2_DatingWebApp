<?php
class Validate {
    private $_passed = false,
            $errors = array(),
            $_db = null;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function check($source, $items = array()) {
        foreach ($items as $item => $rules) {

            $field_name = $rules['name']; 	//Pulls out the name you set in Register.php
            unset($rules['name']);			//Removes the name from the array so you don't have to loop over it

            foreach ($rules as $rule => $rule_value) {
                $value = trim($source[$item]);
                $item = escape($item);

                if ($rule === 'required' && empty($value)) {
                    $this->addError("{$field_name} is required");
                } else if(!empty($value)) {
                    switch($rule) {
                        case 'min':
                            if(strlen($value) < $rule_value) {
                                $this->addError("{$field_name} must be a minimum of {$rule_value} characters");
                            }   
                        break;
                        case 'max':
                        if(strlen($value) > $rule_value) {
                            $this->addError("{$field_name} must be a maximum of {$rule_value} characters");
                        } 
                        break;
                        case 'matches':
                        if ($value != $source[$rule_value]) {
                            $this->addError("{$field_name} must match {$rules['matches']}");
                        }
                        break;
                        case 'unique':
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if($check->count()) {
                                $this->addError("{$item} already exists");
                            }
                        break;
                    }
                }
            }
        }

        if(empty($this->errors())){
            $this->_passed = true;
        }

        return $this;
    }

    private function addError($error) {
        $this->_errors[] = $error;
    }

    public function errors() {
        return $this->_errors;
    }

    public function passed() {
        return $this->_passed;
    }
}