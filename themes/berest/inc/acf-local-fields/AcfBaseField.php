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

	/**
	 * Deletes all data for specific meta key
	 * @param $name_key_meta
	 * @param string $name_type_post
	 */
	public function DeleteAllDataMetaFiled($name_key_meta ,$name_type_post = 'post'): void {
		$args_p    = 'numberposts=-1&post_type=' . $name_type_post . '&post_status=any';
		$all_posts = get_posts( $args_p );

		foreach( $all_posts as $post_info) {
			delete_post_meta( $post_info->ID, $name_key_meta );
		}
	}

}