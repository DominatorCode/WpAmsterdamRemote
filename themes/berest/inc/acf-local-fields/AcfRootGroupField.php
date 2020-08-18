<?php

namespace DirectoryCustomFields;

use AcfLocalGroupField;

require get_template_directory() . '/inc/acf-local-fields/AcfLocalGroupField.php';
require get_template_directory() . '/inc/acf-local-fields/AcfLocalInputField.php';
require get_template_directory() . '/inc/acf-local-fields/ConfigurationParameters.php';

//require get_template_directory() . '/inc/acf-local-fields/AcfBaseField.php';

/**
 * Class AcfRootGroupField
 * Creates root ACF local group
 * @package DirectoryCustomFields
 */
class AcfRootGroupField extends AcfBaseField
{
	/**
	 * Path for working directory for ACF json files
	 * @var string
	 */
	private $path_dir_acf = '/acf-json';

	/**
	 * Contains names of terms excluded for display
	 * @var array
	 */
	private $arr_name_terms_excluded = array();
	private $arr_id_terms_excluded = array();

	private $id_post = 0; // 0 if it's a new post


	/**
	 * List of fields using meta fields
	 * @var array
	 * associative array
	 * [IdTerm][MetaData]
	 */
	private $data_fields_custom_all = array();

	/**
	 * Contains data which meta name should use named term
	 * @var array
	 * associative array
	 * [NameTerm][NameMeta]
	 */
	private $data_terms_meta_custom = array();
	private $customTaxonomies;

	/**
	 * AcfRootGroupField constructor.
	 * @param $id_post
	 * Post ID
	 * @param $name_displayed
	 * Displayed name of root group
	 * @param string $id_group
	 * Key Id identification of creating root group
	 */
	public function __construct($id_post, $name_displayed, $id_group = '')
	{
		if (empty($id_group)) {
			$id_group = 'group_5eb935640cddd';
		}

		if (empty($name_displayed)) {
			$name_displayed = 'Root Group';
		}

		if (is_numeric($id_post)) {
			$this->id_post = $id_post;
		}

		parent::__construct($name_displayed, '', $id_group);

		// if using local JSON
		add_filter('acf/settings/save_json', array($this, 'my_acf_json_save_point'));
		add_filter('acf/settings/load_json', array($this, 'my_acf_json_load_point'));

		// sets ID for new post
		add_action('admin_head', array($this, 'SetPostId'));

		// update fields data in DB after post edited
		add_action('save_post', array($this, 'update_acf_field_data'));

		// delete meta data after term was deleted
		add_action('pre_delete_term', array($this, 'on_term_delete'), 10, 3);
	}

	/**
	 * @param array $arr_meta_terms_custom
	 * Associative array[array]
	 * [NameTerm][NameMeta]
	 */
	public function setArrMetaTermsCustom(array $arr_meta_terms_custom): void
	{
		// filter 'bad' elements
		foreach ($arr_meta_terms_custom as $key => $item) {
			if (!array_key_exists('NameTerm', $item) ||
				!array_key_exists('NameMeta', $item)) {
				unset($arr_meta_terms_custom[$key]);

			}
		}

		$this->data_terms_meta_custom = $arr_meta_terms_custom;

	}

	/**
	 * @return mixed
	 */
	public function getArrNameTermsExcluded()
	{
		return $this->arr_name_terms_excluded;
	}

	/**
	 * Add terms excluded for display
	 * @param string $name_term_exclude
	 */
	public function addNameTermExclude(string $name_term_exclude): void
	{
		$this->arr_name_terms_excluded[] = strtolower($name_term_exclude);
	}

	/**
	 * Creates ACF form in dashboard
	 */
	public function CreateAcfRootLocalGroup(): void
	{
		add_action('admin_init', array($this, 'wpse29164_GenerateLocalAdminAcfForms'));
	}

	public function ApplyFilterTaxonomyFields(): void
	{
		add_action('admin_init', array($this, 'wpse29164_applyAcfFilters'));
	}

	// UPGRADE split function
	public function wpse29164_GenerateLocalAdminAcfForms(): void
	{
		if (function_exists('acf_add_local_field_group')):

			// Generate fields with Custom Taxonomies
			// get list of custom taxonomies
			$taxonomies = $this->getCustomTaxonomyList();

			// contains generated code for fields
			$arr_fields = array();

			// contains group id and used taxonomy for generated group fields
			$arrKeysGroup = array();
			$indexGroupsTabs = 0;

			// generate key id for fields
			$idKeyGroup = str_replace("group_", "field_", $this->idKey);

			// generate Tabs and Groups for custom Taxonomy fields
			if ($taxonomies) {
				foreach ($taxonomies as $taxonomy) {

					// generate Tab field
					$arr_fields[] = $this->CreateAcfTabField($taxonomy->name, $taxonomy->label);

					// generate group field code
					$cFieldGroup = new AcfLocalGroupField('Choose ' . $taxonomy->label,
						$taxonomy->name, $idKeyGroup . $indexGroupsTabs++);
					$arr_fields[] = $cFieldGroup->getArrFieldsGenerated();

					// save group id
					$arrKeysGroup[] = $cFieldGroup->idKey;

				}
			}

			// Generate "Rates" tab
			$arr_fields[] = $this->CreateAcfTabField('rates_tab', 'Rates', 'field_5ecee45e7b68a');

			//<editor-fold desc="Generate Custom Group fields for Rates">
			// Rates term data
			$arr_term_rates_data = ConfigurationParameters::$data_terms_input_range;
			$postfix_out = ConfigurationParameters::$name_postfix_rates_out;

			// Get Rates term child data object
			$obj_rates = get_terms(
				array(
					'taxonomy' => $arr_term_rates_data['Taxonomy'],
					'hide_empty' => false,
					'parent' => $arr_term_rates_data['Id'],
				)
			);

			// get child names and labels
			$arr_label_rates = array();
			$arr_name_rates = array();
			$arr_id_term_rates = array();

			foreach ($obj_rates as $obj_rate) {
				$arr_label_rates[] = $obj_rate->name;
				$arr_name_rates[] = $obj_rate->slug;
				$arr_id_term_rates[] = $obj_rate->term_id;
			}

			$index_rates = 0;

			// Create group field for each term
			foreach ($arr_label_rates as $arr_label_rate) {
				$cFieldGroupRates = new AcfLocalGroupField($arr_label_rate,
					$arr_name_rates[$index_rates], '', array(
						'width' => '40',
						'class' => 'acf-responsive',
						'id' => '',
					));

				//<editor-fold desc="Generate custom sub fields code for each term group">

				//<editor-fold desc="Get initial values from DB for custom meta">
				$valDefault_in = -1;
				$valDefault_out = -1;
				$name_field_meta = '';
				$data_fields_meta_using = array();

				// check if it's need to use a custom meta name
				foreach ($this->data_terms_meta_custom as $item) {
					if ($item['NameTerm'] === $arr_label_rate) {
						$name_field_meta = $item['NameMeta'];
						$valDefault_in = get_post_meta($this->id_post, $item['NameMeta'] . '_in', true);
						$valDefault_out = get_post_meta($this->id_post, $item['NameMeta'] . $postfix_out, true);
					}
				}

				// if it's not custom use meta name built from it's name
				if (empty($name_field_meta)) {
					$name_field_meta = $arr_name_rates[$index_rates];
					$valDefault_in = get_post_meta($this->id_post, $name_field_meta . '_in', true);
					$valDefault_out = get_post_meta($this->id_post, $name_field_meta . $postfix_out, true);
				}

				//</editor-fold>

				// 1. Create "In" sub field
				$id_in = ConfigurationParameters::getIdKeyFieldSubCustom() . '_in' . $index_rates;
				$nameIn = substr($arr_name_rates[$index_rates], 0, 3) . '_in';
				$cIn = new  AcfLocalInputField('In', $nameIn, $id_in);
				$cIn->SetInputParameters(0, 200, 1, $valDefault_in);

				// add field data for future updating [NameMeta][KeyId][ValueOld]
				$data_fields_meta_using[] = array('NameMeta' => $name_field_meta . '_in',
					'KeyId' => $id_in, 'ValueOld' => $valDefault_in);

				// add input field code to group field
				$cFieldGroupRates->AddSubFieldsCode($cIn->getArrCodeField());

				// 2. "Out" sub field
				$id_out = ConfigurationParameters::getIdKeyFieldSubCustom() . $postfix_out . $index_rates;
				$nameOut = substr($arr_name_rates[$index_rates], 0, 3) . $postfix_out;
				$cOut = new  AcfLocalInputField('Out', $nameOut, $id_out);
				$cOut->SetInputParameters(0, 300, 1, $valDefault_out);

				// add data for future field updating [NameMeta][KeyId][ValueOld]
				$data_fields_meta_using[] = array('NameMeta' => $name_field_meta . $postfix_out,
					'KeyId' => $id_out, 'ValueOld' => $valDefault_out);

				// add input field code to group field
				$cFieldGroupRates->AddSubFieldsCode($cOut->getArrCodeField());

				// add final Rates code in common data
				$arr_fields[] = $cFieldGroupRates->getArrFieldsGenerated();

				// add field overall data in common array
				$this->data_fields_custom_all[] = array('IdTerm' => $arr_id_term_rates[$index_rates], 'MetaData' => $data_fields_meta_using);

				$index_rates++;
				//</editor-fold>
			}
			//</editor-fold>

			// Generate "Model Image Field" tab
			$arr_fields[] = $this->CreateAcfTabField('image_tab', 'Image', 'field_5ecee4837b68b');
			$arr_fields[] = $this->CreateAcfTextField('Model Images', 'model_images', '', array(
				'width' => '',
				'class' => '',
				'id' => '',
			));

			$arrDataGroupExport = array(
				'key' => $this->idKey,
				'title' => $this->nameLabel,
				'fields' => $arr_fields,
				'location' => array(
					array(
						array(
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'directory',
						),
					),
				),
				'menu_order' => 1,
				'position' => 'normal',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => true,
				'description' => '',
			);
			acf_add_local_field_group($arrDataGroupExport);

			//acf_write_json_field_group($arrDataGroupExport);

			//<editor-fold desc="Generate taxonomy sub fields for certain created groups">
			if ($taxonomies) {
				$indexGroup = 0;
				// if sub field type is 'taxonomy' then split it by parent term on columns
				foreach ($taxonomies as $taxonomy) {

					// get all parent terms for current taxonomy
					$terms = get_terms([
						'taxonomy' => $taxonomy->name,
						'hide_empty' => false,
						'parent' => 0,
						/*'exclude' => array( 111 ), may be use this for exclude terms
						Ex.: get_term_by('name', 'Rates', 'statistics');
						*/
					]);

					// generate columns for each parent term
					$indexTerm = 0;
					foreach ($terms as $term) {
						if (!in_array($term->slug, $this->arr_name_terms_excluded, true)) {
							$this->CreateAcfSubFieldTaxonomy($term->name, $term->slug,
								'checkbox', $taxonomy->name, $arrKeysGroup[$indexGroup], $arrKeysGroup[$indexGroup] . $indexTerm++);
						} else {
							$this->arr_id_terms_excluded[] = $term->term_taxonomy_id;
						}

					}
					$indexGroup++;
				}
			}
			//</editor-fold>
		endif;
	}

	/**
	 * @return array
	 */
	protected function getCustomTaxonomyList(): array
	{
		if (!isset($this->customTaxonomies)) {
			$this->customTaxonomies = $this->GetListCustomTaxonomies('objects');
		}

		return $this->customTaxonomies;
	}

	/**
	 *
	 * @param string $typeField
	 * @return array
	 */
	public function GetListCustomTaxonomies($typeField): array
	{
		$args = array(
			'public' => true,
			'_builtin' => false
		);
		$output = $typeField; // or objects
		$operator = 'and';    // 'and' or 'or'
		return get_taxonomies($args, $output, $operator);
	}

	/**
	 * Generates Acf tab field
	 * @param string $nameField
	 * @param string $labelField
	 * @param string $idField
	 * @return array
	 */
	public function CreateAcfTabField($nameField, $labelField, $idField = ''): array
	{
		if (empty($idField)) {
			$idField = 'field_' . uniqid('', true);
		}
		return array(
			'key' => $idField,
			'label' => $labelField,
			'name' => $nameField,
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		);
	}

	/**
	 * Generates ACF Text field
	 * @param string $label
	 * Field label
	 * @param string $name
	 * @param string $keyId
	 * @param array $wrapper
	 * ['width']
	 * ['class']
	 * ['id']
	 * @return array
	 */
	public function CreateAcfTextField($label, $name, $keyId = '', $wrapper = array(
		'width' => '',
		'class' => '',
		'id' => '',
	)): array
	{
		if (empty($keyId)) {
			$keyId = $this->GenerateUniqueKeyId();
		}
		return array(
			'key' => $keyId,
			'label' => $label,
			'name' => $name,
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => $wrapper,
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		);
	}

	/**
	 * @param string $nameLabel
	 * @param string $nameField
	 * @param string $typeMethodSelect
	 * [checkbox][multiselect][etc]
	 * @param string $nameTaxonomy
	 * @param string $parentKey
	 * @param string $keyId
	 */
	public function CreateAcfSubFieldTaxonomy($nameLabel, $nameField, $typeMethodSelect, $nameTaxonomy, $parentKey = '', $keyId = ''): void
	{
		// check key id
		if (empty($keyId)) {
			$keyId = $this->GenerateUniqueKeyId();
		}

		acf_add_local_field(array(
			'key' => $keyId,
			'label' => $nameLabel,
			'name' => $nameField,
			'type' => 'taxonomy',
			'parent' => $parentKey,
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => 'acf-responsive',
				'id' => '',
			),
			'taxonomy' => $nameTaxonomy,
			'field_type' => $typeMethodSelect,
			'add_term' => 1,
			'save_terms' => 1,
			'load_terms' => 1,
			'return_format' => 'id',
			'multiple' => 0,
			'allow_null' => 0,
		));

	}

	public function wpse29164_applyAcfFilters(): void
	{
		$taxonomies = $this->GetListCustomTaxonomies('names');

		// Step 2: Get custom taxonomy names and id from list
		if ($taxonomies) {
			foreach ($taxonomies as $taxonomy) {
				$terms = get_terms([
					'taxonomy' => $taxonomy,
					'hide_empty' => false,
					'parent' => 0,
					'exclude' => $this->arr_id_terms_excluded,
				]);

				/*if (defined('WP_DEBUG') && true === WP_DEBUG) {
					var_dump($terms);
				}

				define('DEBUG', true);
				if (DEBUG):
					var_dump($terms);
				endif;*/

				// Step 3: Apply filter for each term
				foreach ($terms as $term_single) {
					$this->CreateAcfFilterField($term_single);
				}
			}
		}
	}

	// ACF json wrk directory

	/**
	 * Filter to display only specific child terms for taxonomy field
	 * @param object $taxonomyUsed
	 */
	private function CreateAcfFilterField($taxonomyUsed): void
	{
		$id_term = $taxonomyUsed->term_id;
		$name_term = $taxonomyUsed->slug;

		$tax_filter = static function ($args) use ($id_term) {
			$args['child_of'] = $id_term;
			// Order by most used.
			/*$args['order_by'] = 'count';
			$args['order'] = 'DESC';*/

			return $args;
		};
		add_filter('acf/fields/taxonomy/wp_list_categories/name=' . $name_term, $tax_filter, 10, 2);
	}

	public function GetListAcfGroups(): void
	{

		$fields = get_fields();

		if ($fields) {
			foreach ($fields as $name => $value) {
				echo $name;
			}
		}
	}

	/**
	 * add a new save folder for ACF JSON
	 * @param $path
	 * @return string
	 */
	public function my_acf_json_save_point($path): string
	{
		// update path
		return get_stylesheet_directory() . $this->path_dir_acf;
	}

	/**
	 * Add a new load point (folder) for ACF JSON
	 * @param $paths
	 * @return mixed
	 */
	public function my_acf_json_load_point($paths)
	{

		// remove original path (optional)
		unset($paths[0]);

		// append path
		$paths[] = get_stylesheet_directory() . $this->path_dir_acf;

		// return
		return $paths;

	}

	/**
	 * Sets Post Id for new post
	 */
	public function SetPostId(): void
	{
		global $post_ID;

		if ($this->id_post === 0) {
			$this->id_post = $post_ID;
		}

	}

	/**
	 * updating acf fields data after post was updated
	 * @param $post_id
	 */
	public function update_acf_field_data($post_id): void
	{
		// Get all acf fields data
		$values = $_POST['acf'];

		// Check if a specific value was updated
		if ($values) {

			// transform to array
			$values_new = array_values($values);
			//update_post_meta($post_id, 'model_images', var_export($this->data_fields_custom_all, true));
			// looping each acf meta field and check for updating value
			foreach ($this->data_fields_custom_all as $data_field_custom) {
				foreach ($data_field_custom['MetaData'] as $field_meta) {

					// looping through updated data
					foreach ($values_new as $key => $item) {

						// update db data
						if (array_key_exists($field_meta['KeyId'], $item)) {
							// if old value unequal to new perform db update
							//if ($field_meta['ValueOld'] !== $item[$field_meta['KeyId']]) {
							update_post_meta($post_id, $field_meta['NameMeta'], sanitize_text_field($item[$field_meta['KeyId']]));

							//}

							// delete unused
							unset($values_new[$key][$field_meta['KeyId']]);
							break;

						}
					}

				}
			}

		}
	}

	/**
	 * Cleans db data after term was deleted
	 * @param $term_id
	 * @param $taxonomy
	 */
	public function on_term_delete($term_id, $taxonomy): void
	{

		// check if deleted taxonomy used custom input
		if ($taxonomy === ConfigurationParameters::$data_terms_input_range['Taxonomy']) {

			// check if its custom input field
			// get terms parent ID
			$id_parent = get_term($term_id, $taxonomy)->parent;

			// check if it's custom data field by parent
			if (($id_parent === ConfigurationParameters::$data_terms_input_range['Id'])) {
				// find all metas of fields by used id
				foreach ($this->data_fields_custom_all as $field_meta) {

					if ($field_meta['IdTerm'] === $term_id) {
						foreach ($field_meta['MetaData'] as $metaDatum) {
							// delete all meta data in db

							delete_post_meta_by_key($metaDatum['NameMeta']);
						}
					}
				}
			}

		}

	}
}