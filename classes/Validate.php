<?php
// klasse som bruges til bl.a. validering af brugerens indtastninger
class Validate {
    private $_passed = false,
            $errors = array(),
            $_db = null;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function check($source, $items = array()) {
        foreach ($items as $item => $rules) {

            $field_name = $rules['name']; 	//henter det navn man sætter inputtet til at have
            unset($rules['name']);			//fjerner navnet fra arrayet, så vi ikke behøver at loope over det

            foreach ($rules as $rule => $rule_value) {
                $value = trim($source[$item]); // fjern whitespace
                $item = escape($item);

                if ($rule === 'required' && empty($value)) {
                    $this->addError("{$field_name} er krævet");
                } else if(!empty($value)) {
                    switch($rule) {
                        case 'min':
                            if(strlen($value) < $rule_value) {
                                $this->addError("{$field_name} skal være mindst {$rule_value} karakterer");
                            }   
                        break;
                        case 'max':
                        if(strlen($value) > $rule_value) {
                            $this->addError("{$field_name} skal være maks {$rule_value} karakterer");
                        } 
                        break;
                        case 'matches':
                        if ($value != $source[$rule_value]) {
                            //$this->addError("{$field_name} skal matche {$rules['matches']}");
                            $this->addError("{$field_name} skal matche " . $items[$rule_value]['name']); // giver os mulighed for at tilføje et andet navn til vores inputs                 
                        }
                        break;
                        case 'validemail':
                            if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError("{$field_name} skal være en korrekt email");
                            }   
                        break;
                        case 'zero-int-allowed':
                            if(!is_numeric($value)) {
                                $this->addError("{$field_name} er krævet");
                            }   
                        break;
                        case 'min-age':              
                        $minAge = $rule_value;

                        $today = new DateTime(date("Y-m-d"));
                        $originalEntered = Input::getDate($value); // konverter fra yyyy-mm-dd til dd-mm-yyyy
                        $enteredConverted = date("d-m-Y", strtotime($originalEntered)); // konverter til unixtime
                        $bday = new DateTime($enteredConverted); // 

                        $interval = $today->diff($bday);
                        
                        if(intval($interval->y) < $minAge) {
                            $this->addError("Du skal være mindst {$minAge} år for at kunne oprette en konto");
                        }
                        break;
                        case 'max-age':              
                        $maxAge = $rule_value;

                        $today = new DateTime(date("Y-m-d"));
                        $originalEntered = Input::getDate($value); // konverter fra yyyy-mm-dd til dd-mm-yyyy
                        $enteredConverted = date("d-m-Y", strtotime($originalEntered)); // konverter til unixtime
                        $bday = new DateTime($enteredConverted); // 

                        $interval = $today->diff($bday);
                        
                        if(intval($interval->y) > $maxAge) {
                            $this->addError("Du må maks være {$maxAge} år");
                        }
                        break;                                       
                        case 'disallow-only-numbers':
                            if(is_numeric($value)) {
                                $this->addError("{$field_name} må ikke kun indeholde tal");
                            }   
                        break;
                        case 'unique':
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if($check->count()) {
                                $this->addError("{$field_name} eksisterer allerede");
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

    public function addError($error) {
        $this->_errors[] = $error;
    }

    public function errors() {
        return $this->_errors;
    }

    public function passed() {
        return $this->_passed;
    }
}