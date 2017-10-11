<?php

/**
 * JQValidatorComponent class file.
 * 
 * This file is part of CakePHP JQValidator Plugin.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */

/**
 * JQValidatorComponent class.
 * 
 * JQValidatorComponent is used for creating JQuery client side validation
 * for models.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	app.Plugin.JQValidator
 * @category	component 
 */
class JQValidatorComponent extends Component {

	function addValidation($modelName, $validationOptions, $formId = null, $validationGroups = array(), $relatedForms = array()) {
		if (!$formId)
			$formId = '';
		else
			$formId = '#' . $formId;

		$validations = Configure::read('JQValidator.jQValidations');

		if (!isset($validations))
			$validations = array();

		$validations[$formId] = array(
			'modelName' => $modelName,
			'validationOptions' => $validationOptions,
			'formId' => $formId,
            'validationGroups' => $validationGroups,
            'relatedForms' => $relatedForms
		);

		Configure::write('JQValidator.jQValidations', $validations);
		Configure::write('JQValidator.closestSelector', $this->settings['closestSelector']);
	}
}