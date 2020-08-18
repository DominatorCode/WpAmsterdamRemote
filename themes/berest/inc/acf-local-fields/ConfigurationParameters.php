<?php

namespace DirectoryCustomFields;

class ConfigurationParameters
{
	private static $position_tab_rates = 4;

	private static $id_key_field_sub_custom = 'field_5f29e46ced777';

	public static $name_postfix_rates_in = '_in';
	public static $name_postfix_rates_out = '_out';

	public static $data_terms_input_range = array('Taxonomy' => 'statistics', 'Id' => 111);

	/**
	 * @return string
	 */
	public static function getIdKeyFieldSubCustom(): string
	{
		return self::$id_key_field_sub_custom;
	}
}