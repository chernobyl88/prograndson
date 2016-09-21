<?php

namespace Library\Form;

if (!defined("EVE_APP"))
	exit();

/**
 * Field to create a password field
 * 
 * The corresponding XML is
 * 
 * 		<form_elem form_type="PasswordField">
 * 			<info name="label" value="[CONST_LABEL_NAME]" />
 * 			<info name="name" value="[field_name]" />
 * 		</form_elem>
 * 
 * This field has no value
 * 
 * @copyright ParaGP Swizerland
 * @author Zellweger Vincent
 * @version 1.0
 */
class PasswordField extends StringField{
	/**
	 * (non-PHPdoc)
	 * @see \Library\Form\StringField::buildWidget()
	 */
	public function buildWidget(){
		$widget = '';
		
		if(!empty($this->errorMessage)){
			$widget .= '<div class="error">' . constant($this->errorMessage) . '</div>';
		}
		$widget .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><label for="' . $this->name . '">' . constant($this->label) . '</label></div><div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"><input class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="' . $this->name . '" type="password" name="' . $this->name . '"';
		
		return $widget . ' /></div>';
	}
}

?>