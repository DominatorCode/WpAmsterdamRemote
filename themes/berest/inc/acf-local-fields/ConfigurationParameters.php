<?php

namespace DirectoryCustomFields;

class ConfigurationParameters
{
	private static $position_tab_rates = 4;

	private static $id_key_field_sub_custom = 'field_5f29e46ced777';

	public static $name_postfix_rates_in = '_in';
	public static $name_postfix_rates_out = '_out';

	public static $data_terms_input_range = array('Taxonomy' => 'statistics', 'Id' => 111);

	// list of custom meta names for terms
	private static $list_term_names_meta = null;

	/**
	 * @return null
	 */
	public static function getListTermNamesMeta(): ?array
	{
		if (empty(self::$list_term_names_meta)) {
			self::$list_term_names_meta = array();
			self::$list_term_names_meta[] = array('NameTerm' => 'Additional Hour Admin', 'NameMeta' => 'add_hour');
			self::$list_term_names_meta[] = array('NameTerm' => '1 Hour', 'NameMeta' => 'one_hour');
			self::$list_term_names_meta[] = array('NameTerm' => 'Dinner Date', 'NameMeta' => 'dinner_date');
			self::$list_term_names_meta[] = array('NameTerm' => 'Overnight', 'NameMeta' => 'overnight');
		}
		return self::$list_term_names_meta;
	}

	/**
	 * @return string
	 */
	public static function getIdKeyFieldSubCustom(): string
	{
		return self::$id_key_field_sub_custom;
	}

	/**
	 * Returns an array of child terms objects for given parent term
	 * @param $name_taxonomy
	 * @param $id_term_parent
	 * @return int|\WP_Error|\WP_Term[]
	 */
	public static function GetTermsList($name_taxonomy, $id_term_parent)
	{
		return get_terms(
			array(
				'taxonomy' => $name_taxonomy,
				'hide_empty' => false,
				'parent' => $id_term_parent,
			)
		);
	}
}