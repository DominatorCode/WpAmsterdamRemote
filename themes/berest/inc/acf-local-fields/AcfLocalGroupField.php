<?php

use DirectoryCustomFields\AcfBaseField;
require get_template_directory() . '/inc/acf-local-fields/AcfBaseField.php';

class AcfLocalGroupField extends AcfBaseField
{
    public $arrFieldsGenerated;
    public $idKey;
    public $nameLabel;
    public $nameField;

    public function __construct($pNameLabel, $pNameField, $pIdKey = '')
    {
        // check key id
        if (empty($pIdKey)) {
            $this->idKey =  $this->GenerateUniqueKeyId();
        }
        else {
            $this->idKey = $pIdKey;
        }

        $this->nameLabel = $pNameLabel;
        $this->nameField = $pNameField;

        $this->arrFieldsGenerated = $this->GenerateAcfGroupField();
    }

    /**
     * Generates field code
     * @return array
     */
    public function GenerateAcfGroupField() : array
    {
        $arr_fields_sub = array();

        return array(
            'key' => $this->idKey,
            'label' => $this->nameLabel,
            'name' => 'group_' . $this->nameField,
            'type' => 'group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'layout' => 'block',
            'sub_fields' => $arr_fields_sub);
    }

}