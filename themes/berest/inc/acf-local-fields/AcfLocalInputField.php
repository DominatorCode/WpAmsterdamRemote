<?php

namespace DirectoryCustomFields;

class AcfLocalInputField extends AcfBaseField
{
	private $arr_code_field = array();

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
			'default_value' => '',
			'min' => '',
			'max' => '',
			'step' => '',
			'prepend' => '',
			'append' => '',
		);
	}
}