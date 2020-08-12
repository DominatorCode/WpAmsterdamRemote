<?php

namespace DirectoryCustomFields;

class AcfBaseField
{
	/**
	 * Generates unique key id for ACF fields
	 * @param string $namePrefix
	 * @return string
	 */
	public function GenerateUniqueKeyId($namePrefix = 'field_') : string
	{
		return uniqid($namePrefix, false);
	}

	public function CheckIfAcfInstalled() : bool
	{
		if (!class_exists('ACF')) {
			return false;
		}

		return true;
	}

	public $idKey;
	public $nameLabel;
	public $nameField;
	public $nameWrapper;

	public function __construct($pNameLabel, $pNameField, $pIdKey = '', $wrapper = array(
		'width' => '',
		'class' => '',
		'id' => '',
	))
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
		$this->nameWrapper = $wrapper;

	}

}