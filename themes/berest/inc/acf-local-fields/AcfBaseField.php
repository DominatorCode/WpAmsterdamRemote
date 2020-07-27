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
			return true;
		}

		return false;
	}
}