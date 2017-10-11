<?php

/**
 * JQValidatorHelper class file.
 * 
 * This file is part of CakePHP JQValidator Plugin.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */

/**
 * JQValidatorHelper class.
 * 
 * JQValidatorHelper is used for creating JQuery client side validation
 * for models.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	app.Plugin.JQValidator
 * @category	helper 
 */
class JQValidatorHelper extends AppHelper {

	/**
	 * Other helpers used by this helper
	 */
	public $helpers = array('Html');

	/**
	 * Returns a script which creates the client side JQuery validation for
	 * the specified model
	 * 
	 * @return string
	 */
	public function validator() {
		$closestSelector = Configure::read('JQValidator.closestSelector');
		$script = '';
		// get the validation rules saved in the component
		$validations = Configure::read('JQValidator.jQValidations');
		if (!empty($validations)) {
			$script = "<script type=\"text/javascript\">
            function isValidDOB(year, month, day){
                if((year !== '') && (month !== '') && (day !== '')){
                    month = parseInt(month) - 1;
                    var dob = day + '-' + month + '-' + year;
                    var today = new Date();
                    var birthDate = new Date(year,month,day);
                    var age = today.getFullYear() - birthDate.getFullYear();
                    var m = today.getMonth() - birthDate.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    return (age < 13)?false:true;
                }
                else{
                    return true;
                }
            }
			$(document).ready(function(){
                $.validator.setDefaults({
                    'errorElement': 'span',
					'errorClass': 'help-block',
					'highlight': function(element,errorClass) {
                        var closestElement = $(element).closest('.form-group-col');
                        if(closestElement.length === 0){
                            var closestElement = $(element).closest('.{$closestSelector}');
                        }
                        closestElement.addClass('error');
                        var elementGroupContainer = closestElement.parents('.form-group-container');
                        if(elementGroupContainer.length > 0) {
                            elementGroupContainer.find('.form-group .help-block').remove();
                            elementGroupContainer.addClass('error');
                        }
					},
					'unhighlight': function(element,errorClass) {
                        var closestElement = $(element).closest('.form-group-col');
                        if(closestElement.length === 0){
                            var closestElement = $(element).closest('.{$closestSelector}');
                        }
                        closestElement.removeClass('error');
                        var elementGroupContainer = closestElement.parents('.form-group-container');
                        if(elementGroupContainer.length > 0) {
                            elementGroupContainer.find('.form-group').removeClass('error');
                            elementGroupContainer.find('.help-block').remove();
                        }
					}
                });
                $.validator.addMethod('regex',function(value, element, regexp) {
                    var re = new RegExp(regexp);
                    return this.optional(element) || re.test(value);
                });
				$.validator.addMethod('validDOB', function(value, element, options) {
					var month;
					var day;
					var year;
					if ($(element).is('input:text')) {
						var dob = value;
						var dobArr = dob.split('-');
						month = dobArr[0];
						day = dobArr[1];
						year = dobArr[2];
					}
					else {
						var elementGroupContainer = $(element).closest('.form-group-container');
						year = elementGroupContainer.find('[name*=\"year\"]').val();
						month = elementGroupContainer.find('[name*=\"month\"]').val();
						day = elementGroupContainer.find('[name*=\"day\"]').val();
					}

					return isValidDOB(year, month, day);
				});
                $.validator.addMethod('groupRequired', function (value, element, options) {
                    var elementGroupContainer = $(element).closest('.form-group-container');
                    var anyEmpty = false;
                    elementGroupContainer.find('.form-group').each(function(){
                         if($(this).find('select,input').val() === '') {
                             anyEmpty = true;
                             return;
                         }
                    });
                    return !anyEmpty;
                });
				$.validator.addMethod('usZipCode', function (value, element, options) {                	

					var related = $(element).attr('data-rel');
					related = new String(related).split('#');
					var stateName = $.trim($('.' + related[1] +' option:selected').text());
					var cityName = $.trim($('.' + related[2] +' option:selected').text());
					var cityId = $.trim($('.' + related[2] +'').val());					
					var status = false;		

					 var closestElement = $(element).closest('.form-group');
					 closestElement.removeClass('error');
					 closestElement.find('.help-block').remove();
                        
					$('.zip_validating').html('');
					$('.zip_validating').html('Validating US Zip ...');		
					var jqXHR = $.ajax({
						url: '/api/checkValidUSZip',
						type: 'POST',
						async: false,
						data: {
							'zipcode': value,
							'stateName': stateName,
							'cityName': cityName,
							'cityId' : cityId
						},
						success: function(result) {
						$('.zip_validating').html('');	
						 if(result === 'false'){
							status = false;
						} else {
							status = true;
						}}
					});						
					return status;
                });
				
                $.validator.addMethod('customUrl', function (value, element, options) {
                    if(value.substr(0,7) != 'http://'){
                        if(value.substr(0,8) != 'https://'){
                            value = 'http://' + value;
                        }
                    }
                    if(value.substr(value.length-1, 1) != '/'){
                        value = value + '/';
                    }
                    return this.optional(element) || /^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(value);
                });
                ";
			foreach ($validations as $validation) {
				$modelName = $validation['modelName'];
				$validationOptions = $validation['validationOptions'];
				$formId = $validation['formId'];
				$validationGroups = $validation['validationGroups'];

				$rulesArr = array();
				$messagesArr = array();
				$groupsArr = array();
				if (!empty($validationGroups)) {
					foreach ($validationGroups as $groupName => $groupFieldsArr) {
						foreach ($groupFieldsArr as $groupField) {
							$groupFieldValues[] = "data[$modelName][$groupField]";
						}
						$groupFields = join(" ", $groupFieldValues);
						$groupsArr[] = "$groupName: \"$groupFields\"";
						$groupFieldValues = "";
					}
				}
				foreach ($validationOptions as $name => $settings) {
					$fieldName = "'data[$modelName][$name]'";
					$fieldRulesMessages = $this->getFieldRulesMessages($settings, $modelName);
					$rulesArr[] = "$fieldName: { {$fieldRulesMessages['rules']} }";
					$messagesArr[] = "$fieldName: { {$fieldRulesMessages['messages']} }";
				}
				$rules = join(', ', $rulesArr);
				$messages = join(', ', $messagesArr);
				$groups = join(',', $groupsArr);
				$script .= "$('$formId').validate({
                                    ignore: ':hidden input, :hidden select, :hidden textarea',
                                    groups: { $groups },
                                    errorPlacement: function(error, element) {
                                        if (element.hasClass('hasDatepicker') && element.parents('.form-group').find('#customDateErrorMsg').length > 0) {
                                            var container_rep = $('#customDateErrorMsg');
                                            error.appendTo(container_rep);
                                        } else if (element.hasClass('setApptErrorDiv')) {
                                            var container_rep = $('#appt_time_error_wrapper');
                                            error.appendTo(container_rep);
                                        } else if (element.hasClass('setRemErrorDiv')) {
                                            var container_rep = $('#rem_time_error_wrapper');
                                            error.appendTo(container_rep);
                                        } else if (element.hasClass('setErrorDivRepeat')) {
                                            var container_rep = $('#time_error_wrapper_rep');
                                            error.appendTo(container_rep);
                                        } else if (element.hasClass('setErrorDivOneday')) {
                                            var container = $('#time_error_wrapper_no_rep');
                                            error.appendTo(container);
                                        } else if (element.parents('.form-group-container').length > 0) {
                                            var groupContainer = $(element).closest('.row');
                                            groupContainer.find('.help-block').remove();
                                            error.appendTo(groupContainer);
                                        } else if (element.parent('.token-input').length > 0) {
                                            var errorContainer = $(element).closest('.form-group-col');
                                            errorContainer.find('.help-block').remove();
                                            error.appendTo(errorContainer);
                                        } else if (element.parents('.controls').length > 0) {
											errorContainer = element.parents('.controls');
                                            error.appendTo(errorContainer);
                                        } else {
                                        	if(element.parent().hasClass(\"radio-inline\")) {
                                        		error.appendTo(element.parent().parent());
                                        	} else {
                                            	error.appendTo(element.parent());
                                            }
                                        }
                                    },
                                    rules: { $rules },
                                    messages: { $messages }
				});";
			}

			$script .= "});";
			$relatedForms = $validation['relatedForms'];
			if (!empty($relatedForms)) {
				$script .= $this->getRelatedFormsValidationScript($relatedForms);
			}
			$script .="</script>";
		}

		return $script;
	}

	/**
	 * Function to get the validation rules and messages for a field
	 * 
	 * @param arrat $settings
	 * @param string $modelName
	 * @return array
	 */
	private function getFieldRulesMessages($settings, $modelName) {
		$fieldRulesMessages = array();
		$fieldRulesArr = array();
		$fieldMessagesArr = array();
		foreach ($settings as $ruleKey => $options) {
			if (!empty($options['rule'])) {
				$rule = $options['rule'];
				if (is_array($rule)) {
					$msg = $rule[0];
					$ruleName = $rule[0];
				} else {
					$msg = $rule;
					$ruleName = $rule;
				}

				if (!empty($options['message'])) {
					$msg = $options['message'];
				}

				if ($ruleKey === 'regex') {
					$JQRuleName = $ruleKey;
					$JQRuleValue = $options['rule'];
					$JQRuleMsg = $options['message'];

					$fieldRulesArr[] = "'$JQRuleName':$JQRuleValue";
					$fieldMessagesArr[] = "'$JQRuleName':'$JQRuleMsg'";
				} else {
					$methodName = 'jqueryValidate' . ucfirst($ruleName);
					if (method_exists($this, $methodName)) {
						$JQvalidation = $this->$methodName($rule, $msg, $modelName);

						$JQRuleName = $JQvalidation['name'];
						$JQRuleValue = $JQvalidation['value'];
						$JQRuleMsg = $JQvalidation['message'];

						$fieldRulesArr[] = "'$JQRuleName':$JQRuleValue";
						$fieldMessagesArr[] = "'$JQRuleName':'$JQRuleMsg'";
					}
				}
			}
		}

		$fieldRulesMessages['rules'] = join(', ', $fieldRulesArr);
		$fieldRulesMessages['messages'] = join(', ', $fieldMessagesArr);
		return $fieldRulesMessages;
	}

	/**
	 * Function to get the validation for related forms
	 * 
	 * @param arrat $relatedForms
	 * @return string
	 */
	private function getRelatedFormsValidationScript($relatedForms) {
		$script = 'function runRelatedFormsValidationScript(){';
		foreach ($relatedForms as $modelName) {
			$relatedFormModel = ClassRegistry::init($modelName);
			$validations = $relatedFormModel->validate;
			foreach ($validations as $field => $fieldValidations) {
				$fieldRulesMessages = $this->getFieldRulesMessages($fieldValidations, $modelName);
				$script .= "$('[name*=\"[$field]\"]').each(function() {
                                $(this).rules('add', {
                                    {$fieldRulesMessages['rules']},
                                    messages: { 
                                        {$fieldRulesMessages['messages']}
                                    }
                                });
                            });";
			}
		}
		$script.='}';
		return $script;
	}

	/**
	 * Various functions to convert a CakePHP validation to a Jquery 
	 * validatation rule and message
	 * 
	 * @param array $params
	 * @param string $msg
	 * @return array
	 */
	private function jqueryValidateAlphaNumeric($params, $msg, $model) {
		$rule['name'] = 'alphanumeric';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateBetween($params, $msg, $model) {
		$rule['message']['min'] = $msg;
		$rule['message']['max'] = $msg;
		$response['min'] = $params[1];
		$response['max'] = $params[2];
		return $response;
	}

	private function jqueryValidateBlank($params, $msg, $model) {
		$rule['name'] = 'rangelength';
		$rule['value'] = '[0, 0]';
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateCc($params, $msg, $model) {
		$rule['name'] = 'creditcard';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateDate($params, $msg, $model) {
		$rule['name'] = 'date';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateDecimal($params, $msg, $model) {
		$rule['name'] = 'decimal';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateEmail($params, $msg, $model) {
		$rule['name'] = 'email';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateExtension($params, $msg, $model) {
		$rule['name'] = 'accept';
		$rule['value'] = "'" . implode($params[1], "|") . "'";
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateIp($params, $msg, $model) {
		$rule['name'] = 'ipv4';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateMaxLength($params, $msg, $model) {
		$rule['name'] = 'maxlength';
		$rule['value'] = $params[1];
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateMinLength($params, $msg, $model) {
		$rule['name'] = 'minlength';
		$rule['value'] = $params[1];
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateNotEmpty($params, $msg, $model) {
		$rule['name'] = 'required';
		if (isset($params['dependentField']) && isset($params['dependentValue'])) {
			$dependentField = "data[$model][{$params['dependentField']}]";
			$dependentValue = $params['dependentValue'];
			$value = "function(){";
			if (isset($params['isRadio']) && $params['isRadio'] === true) {
				$value .= "var dependentField = $('[name=\"{$dependentField}\"]:checked');";
			} else {
				$value .= "var dependentField = $('[name=\"{$dependentField}\"]');";
			}
			$value .= "var dependentFieldValue = dependentField.val();
                var isRequired =  (dependentFieldValue == {$dependentValue});
                return isRequired;
            }";
		} else {
			$value = 'true';
		}
		$rule['value'] = $value;
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateNumeric($params, $msg, $model) {
		$rule['name'] = 'number';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateNaturalNumber($params, $msg, $model) {
		$rule['name'] = 'digits';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateRequired($params, $msg, $model) {
		$rule['name'] = 'required';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidatePhone($params, $msg, $model) {
		$rule['name'] = 'phoneUS';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateTime($params, $msg, $model) {
		$rule['name'] = 'time';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateUrl($params, $msg, $model) {
		$rule['name'] = 'customUrl';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	/**
	 * @todo
	 */
	private function jqueryValidateBoolean($params, $msg, $model) {
		return '';

		$rule['name'] = 'boolean';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	/**
	 * @todo
	 */
	private function jqueryValidateComparison($params, $msg, $model) {
		return '';

		$response = array();
		$op = $params[1];
		$value = $params[2];
		switch ($op) {
			case '>':
				$value++;
				$rule['message']['min'] = $msg;
				$response['min'] = $value;
				break;
			case '>=':
				$rule['message']['min'] = $msg;
				$response['min'] = $value;
				break;
			case '<':
				$value--;
				$rule['message']['max'] = $msg;
				$response['max'] = $value;
				break;
			case '<=':
				$rule['message']['max'] = $msg;
				$response['max'] = $value;
				break;
			case '!=':
				$value++;
				$rule['message']['min'] = $msg;
				$response['min'] = $value;
				$value = $value - 2;
				$rule['message']['max'] = $msg;
				$response['max'] = $value;
				break;
			default:
				return '';
		}
		return $response;
	}

	/**
	 * @todo
	 */
	private function jqueryValidateDatetime($params, $msg, $model) {
		return '';

		$rule['name'] = 'datetime';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	/**
	 * @todo
	 */
	private function jqueryValidateequalTo($params, $msg, $model) {
		$rule['name'] = 'equalTo';
		$rule['value'] = "'#" . Inflector::camelize($model) . Inflector::camelize($params[1]) . "'";
		$rule['message'] = $msg;
		return $rule;
	}

	/**
	 * @todo
	 */
	private function jqueryValidateInList($params, $msg, $model) {
		return '';

		$rule['name'] = 'inList';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	/**
	 * @todo
	 */
	private function jqueryValidateLuhn($params, $msg, $model) {
		return '';

		$rule['name'] = 'luhn';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	/**
	 * @todo
	 */
	private function jqueryValidateMoney($params, $msg, $model) {
		return '';

		$rule['name'] = 'money';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	/**
	 * @todo
	 */
	private function jqueryValidatePostal($params, $msg, $model) {
		return '';

		$rule['message']['minlength'] = $msg;
		$rule['message']['maxlength'] = $msg;
		$response['minlength'] = 5;
		$response['maxlength'] = 5;
		return $response;

		$rule['name'] = 'alphanumeric';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	/**
	 * @todo
	 */
	private function jqueryValidateRange($params, $msg, $model) {
		return '';

		$rule['message']['min'] = $msg;
		$rule['message']['max'] = $msg;
		$response['min'] = $params[1];
		$response['max'] = $params[2];
		return $response;
	}

	/**
	 * @todo
	 */
	private function jqueryValidateSsn($params, $msg, $model) {
		return '';

		$rule['message']['minlength'] = $msg;
		$rule['message']['maxlength'] = $msg;
		$response['minlength'] = 9;
		$response['maxlength'] = 9;
		return $response;
	}

	/**
	 * @todo
	 */
	private function jqueryValidateUuid($params, $msg, $model) {
		return '';

		$rule['name'] = 'uuid';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateRemote($params, $msg, $model) {
		$fieldName = $params[2];
		$field = "data[{$model}][{$fieldName}]";
		$idField = "data[{$model}][id]";
		$rule['name'] = 'remote';
		$rule['value'] = "{url: '$params[1]',
                           type: 'post',
                           data: {
                                '$fieldName': function() {
                                    return $('input[name=\"{$field}\"]').val();
                                 },
                                'id': function() {
                                    return $('input[name=\"{$idField}\"]').val();
                                 }
                           }                           
                        }";
		$rule['message'] = $msg;
		return $rule;
	}
	
	private function jqueryValidateUsZipCode($params, $msg, $model) {
		$rule['name'] = 'usZipCode';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateDob($params, $msg, $model) {
		$rule['name'] = 'validDOB';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateGroupRequired($params, $msg, $model) {
		$rule['name'] = 'groupRequired';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateIsValidDiagonisedDate($params, $msg, $model) {
		$rule['name'] = 'isValidDiagonisedDate';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateIsValidYear($params, $msg, $model) {
		$rule['name'] = 'isValidYear';
		$rule['value'] = 'true';
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateMax($params, $msg, $model) {
		$rule['name'] = 'max';
		$rule['value'] = $params[1];
		$rule['message'] = $msg;
		return $rule;
	}

	private function jqueryValidateMin($params, $msg, $model) {
		$rule['name'] = 'min';
		$rule['value'] = $params[1];
		$rule['message'] = $msg;
		return $rule;
	}

	/**
	 * Function to print the country id, zip validation regex script
	 */
	public function printCountryZipRegexScriptBlock() {
		$countryModel = ClassRegistry::init('Country');
		$countryZipRegexList = $countryModel->getCountryIdZipRegexList();
		$countryZipRegexJSON = json_encode($countryZipRegexList);
		$script = "var countryZipRegexJSON = {$countryZipRegexJSON};";
		echo $this->Html->scriptBlock($script);
	}
}