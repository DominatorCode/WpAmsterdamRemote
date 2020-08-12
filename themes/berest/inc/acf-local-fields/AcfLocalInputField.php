<?php

namespace DirectoryCustomFields;

/**
 * Class AcfLocalInputField implements Range ACF field
 *
 * UPGRADE
 * 1. Set min, max, step values from options
 * @package DirectoryCustomFields
 */
class AcfLocalInputField extends AcfBaseField
{
	private $arr_code_field = array();

	private $min = '';
	private $max = '';
	private $step = '';
	private $valueDefault = '';

	/**
	 * Return a generated code for ACF input field
	 * @return array
	 */
	public function getArrCodeField(): array
	{
		if (empty($this->arr_code_field)) {
			$this->arr_code_field = $this->GenerateInputFieldCode();
		}

		return $this->arr_code_field;
	}

	public function GenerateInputFieldCode(): array
	{
		return array(
			'key' => $this->idKey,
			'label' => $this->nameLabel,
			'name' => $this->nameField,
			'type' => 'range',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => $this->nameWrapper,
			'default_value' => $this->valueDefault,
			'min' => $this->min,
			'max' => $this->max,
			'step' => $this->step,
			'prepend' => '',
			'append' => '',
		);
	}

	/**
	 * Sets input parameters for field
	 * @param string $valMin
	 * @param string $valMax
	 * @param string $valStep
	 * @param string $valDefault
	 */
	public function SetInputParameters($valMin = '', $valMax = '', $valStep = '', $valDefault = ''): void
	{
		if (is_numeric($valMin)) {
			$this->min=$valMin;
		}

		if (is_numeric($valMax)) {
			$this->max=$valMax;
		}

		if (is_numeric($valStep) && $valStep > 0) {
			$this->step=$valStep;
		}

		if (is_numeric($valDefault)) {
			$this->valueDefault = $valDefault;
		}
	}
}