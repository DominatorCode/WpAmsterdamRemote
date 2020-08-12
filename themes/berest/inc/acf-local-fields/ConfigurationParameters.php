<?php

namespace DirectoryCustomFields;

class ConfigurationParameters
{
	private static $position_tab_rates = 4;

	private static $id_key_field_sub_custom = 'field_5f29e46ced777';

	/**
	 * @return string
	 */
	public static function getIdKeyFieldSubCustom(): string
	{
		return self::$id_key_field_sub_custom;
	}
}