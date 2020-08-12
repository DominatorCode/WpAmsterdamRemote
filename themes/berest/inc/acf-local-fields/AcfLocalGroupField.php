<?php

use DirectoryCustomFields\AcfBaseField;
require get_template_directory() . '/inc/acf-local-fields/AcfBaseField.php';

/**
 * Contains data for creating ACF Local Group field
 * Class AcfLocalGroupField
 */
class AcfLocalGroupField extends AcfBaseField
{
    private $arrFieldsGenerated;
	private $isChangedSubFieldCode = false;

	/**
	 * Return a generated code for ACF group field
	 * @return mixed
	 */
	public function getArrFieldsGenerated()
	{
		if (empty($this->arrFieldsGenerated) || $this->isChangedSubFieldCode === true) {
			$this->arrFieldsGenerated = $this->GenerateAcfGroupField();
		}
		$this->isChangedSubFieldCode = false;
		return $this->arrFieldsGenerated;
	}
    private $arr_fields_sub = array();


    /**
     * Generates field code
     * @return array
     */
	public function GenerateAcfGroupField(): array
	{

		return array(
			'key' => $this->idKey,
			'label' => $this->nameLabel,
			'name' => 'group_' . $this->nameField,
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => $this->nameWrapper,
			'layout' => 'block',
			'sub_fields' => $this->arr_fields_sub);
	}

	/**
	 * Add code for LG sub fields
	 * @param $codeSubField
	 */
	public function AddSubFieldsCode($codeSubField): void
	{
		if (!empty($codeSubField)) {
			$this->arr_fields_sub[] = $codeSubField;
			$this->isChangedSubFieldCode = true;
		}

	}

}