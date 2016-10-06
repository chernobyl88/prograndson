<?php

namespace Library\Form;

if (!defined("EVE_APP"))
	exit();

/**
 * Field to create a text field
 *
 * The corresponding XML is
 *
 * 		<form_elem form_type="StringField">
 * 			<info name="label" value="[CONST_LABEL_NAME]" />
 * 			<info name="name" value="[field_name]" />
 * 		</form_elem>
 *
 * Optional element
 *
 * 			<info name="value" value="[value]" />
 * 			<info name="maxlength" value="[max_length]" />
 *
 * @copyright ParaGP Swizerland
 * @author Zellweger Vincent
 * @version 1.0
 */
class StringField extends Field{
	/**
	 * Max length that could be inserted in the field
	 * @var int
	 */
	protected $maxlength;

	/**
	 * (non-PHPdoc)
	 * @see \Library\Form\Field::buildWidget()
	 */
	public function buildWidget(){
		$widget = '';

		if(!empty($this->errorMessage)){
			$widget .= '<div class="error">' . constant($this->errorMessage) . '</div>';
		}

		$widget .= '<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 margin-bottom-20"><label for="' . $this->name . '">' . constant($this->label) . '</label></div><div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 margin-bottom-20"><input class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="' . $this->name . '" type="text" name="' . $this->name . '"';

		if(!empty($this->value)){
			$widget .= ' value="' . $this->value . '"';
		}

		if(!empty($maxlength)){
			$widget .= ' maxlength="' . $this->maxlength . '"';
		}

		return $widget . ' /></div>';
	}

	/**
	 * Setter of the max length
	 *
	 * @param int $pVal
	 * @throws \IllegalArgumentException
	 * 				if the max length is lower or equels to 0
	 * @return number
	 */
	public function setMaxlength($pVal){
		$maxLength = (int)$pVal;

		if($maxLength > 0){
			$this->maxLength = $maxLength;
			return 1;
		}else{
			if (\Library\Application::appConfig()->getConst("LOG"))
				throw new \IllegalArgumentException("Error ID: " . \Library\Application::logger()->log("Error", "Form", "La longueur maximale doit être un nombre supérieur à 0", __FILE__, __LINE__));
			else
				throw new \IllegalArgumentException("La longueur maximale doit être un nombre supérieur à 0");
			return 0;
		}
	}
}

?>
