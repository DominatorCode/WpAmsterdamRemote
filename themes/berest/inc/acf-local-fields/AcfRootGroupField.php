<?php

namespace DirectoryCustomFields;
use AcfLocalGroupField;

require get_template_directory() . '/inc/acf-local-fields/AcfLocalGroupField.php';
//require get_template_directory() . '/inc/acf-local-fields/AcfBaseField.php';

class AcfRootGroupField extends AcfBaseField
{
    /**
     * Path for working directory for ACF json files
     * @var string
     */
    public $path_dir_acf = '/acf-json';

    public $nameGroupRoot;

	private $arr_name_terms_excluded = array();

	private $arr_id_terms_excluded = array();

	/**
	 * @return mixed
	 */
	public function getArrNameTermsExcluded()
	{
		return $this->arr_name_terms_excluded;
	}

	/**
	 * @param string $name_term_exclude
	 */
	public function addNameTermExclude(string $name_term_exclude): void
	{
		$this->arr_name_terms_excluded[] = strtolower($name_term_exclude);
	}

    /**
     *  Root group key id
     * @var string
     */
    public $idKeyRoot = 'group_5eb935640cddd';

    /**
     * AcfRootGroupField constructor.
     * @param $name_displayed
     * Displayed name of root group
     * @param string $id_group
     * Key Id identification of creating root group
     */
    public function __construct($name_displayed, $id_group = '')
    {
        if (!empty($id_group))
            $this->idKeyRoot = $id_group;

        $this->nameGroupRoot = $name_displayed;
        add_filter('acf/settings/save_json', array($this, 'my_acf_json_save_point'));
        add_filter('acf/settings/load_json', array($this, 'my_acf_json_load_point'));

    }

	public function CreateAcfLocalGroup()
	{
		add_action('admin_init', array($this, 'wpse29164_GenerateLocalAdminAcfForms'));
	}

    public function ApplyFilterTaxonomyFields()
    {
        add_action('admin_init', array($this, 'wpse29164_applyAcfFilters'));
    }

    public function wpse29164_GenerateLocalAdminAcfForms(): void
    {
        if (function_exists('acf_add_local_field_group')):

            // Generate fields with Custom Taxonomies
            // get list of custom taxonomies
            $taxonomies = $this->GetListCustomTaxonomies('objects');

            // contains generated code for fields
            $arr_fields = array();

            // contains group id and used taxonomy for generated group fields
            $arrKeysGroup = array();
            $indexGroupsTabs = 0;
            // generate key id
            $idKeyGroup = str_replace("group_", "field_", $this->idKeyRoot);
            if ($taxonomies) {
                foreach ($taxonomies as $taxonomy) {

                    // generate Tab field
                    $arr_fields[] = $this->CreateAcfTabField($taxonomy->name, $taxonomy->label);

                    // generate group field code
                    $cFieldGroup = new AcfLocalGroupField('Choose ' . $taxonomy->label,
                        $taxonomy->name, $idKeyGroup . $indexGroupsTabs++);
                    $arr_fields[] = $cFieldGroup->arrFieldsGenerated;

                    // save group id
                    $arrKeysGroup[] = $cFieldGroup->idKey;

                }
            }

            // Generate "Rates" tab
            $arr_fields[] = $this->CreateAcfTabField('rates_tab', 'Rates', 'field_5ecee45e7b68a');
            /*$arr_fields[] = $this->CreateAcfTextField('Model Rates', 'model_rates', '', array(
                'width' => '',
                'class' => '',
                'id' => 'fakeField',
            ));*/

	        $values = $this->CreateRatesGroups();
	        foreach ($values as $val) {
		        $arr_fields[] = $val;
	        }



            // Generate "Model Image Field" tab
            $arr_fields[] = $this->CreateAcfTabField('image_tab', 'Image','field_5ecee4837b68b');
            $arr_fields[] = $this->CreateAcfTextField('Model Images', 'model_images', '', array(
                'width' => '',
                'class' => '',
                'id' => 'fakeField',
            ));

            $arrDataGroupExport = array(
                'key' => $this->idKeyRoot,
                'title' => $this->nameGroupRoot,
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

            // generate taxonomy sub fields for created groups
            if ($taxonomies) {
                $indexGroup = 0;
                foreach ($taxonomies as $taxonomy) {
                    // if sub field type is 'taxonomy' then split it by parent term on columns
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
		                    $this->CreateAcfSubFieldTaxonomy($term->name,$term->slug,
			                    'checkbox', $taxonomy->name, $arrKeysGroup[$indexGroup], $arrKeysGroup[$indexGroup] . $indexTerm++);
	                    } else {
		                    $this->arr_id_terms_excluded[] = $term->term_taxonomy_id;
	                    }

                    }
                    $indexGroup++;
                }
            }
        endif;
    }

    /**
     * Generates Acf tab field
     * @param string $nameField
     * @param string $labelField
     * @param string $idField
     * @return array
     */
    public function CreateAcfTabField($nameField, $labelField, $idField = '') : array
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
            $keyId =  $this->GenerateUniqueKeyId();
        }

        acf_add_local_field( array(
            'key' => $keyId,
            'label' => $nameLabel,
            'name' => $nameField,
            'type' => 'taxonomy',
            'parent'       => $parentKey,
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
        ) );


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
    )) : array
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

    /**
     *
     * @param string $typeField
     * @return array
     */
    public function GetListCustomTaxonomies($typeField) : array
    {
        $args = array(
            'public' => true,
            '_builtin' => false
        );
        $output = $typeField; // or objects
        $operator = 'and'; // 'and' or 'or'
        return get_taxonomies($args, $output, $operator);
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

    // ACF json wrk directory
    public function my_acf_json_save_point($path): string
    {
        // update path
        return get_stylesheet_directory() . $this->path_dir_acf;
    }

    // add a new load point (folder) for ACF JSON
    public function my_acf_json_load_point($paths)
    {

        // remove original path (optional)
        unset($paths[0]);

        // append path
        $paths[] = get_stylesheet_directory() . $this->path_dir_acf;

        // return
	    return $paths;

    }

    private function CreateRatesGroups(): ?\Generator
    {

        //$arr_groups = array();
	    yield array(
            'key' => 'field_5f16ce2dcae6b',
            'label' => 'Additional Hour Admin',
            'name' => 'additional_hour_admin',
            'type' => 'group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '40',
                'class' => 'acf-responsive',
                'id' => '',
            ),
            'layout' => 'block',
            'sub_fields' => array(
                array(
                    'key' => 'field_5f16cf2fcae6c',
                    'label' => 'In',
                    'name' => 'in_aha',
                    'type' => 'range',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => 'acf-responsive',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'min' => '',
                    'max' => '',
                    'step' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_5f16cf6bcae6d',
                    'label' => 'Out',
                    'name' => 'out_aha',
                    'type' => 'range',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => 'acf-responsive',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'min' => '',
                    'max' => '',
                    'step' => '',
                    'prepend' => '',
                    'append' => '',
                ),
            ),
        );

	    yield array(
            'key' => 'field_5f16cffce2e7d',
            'label' => '1 Hour',
            'name' => '1_hour',
            'type' => 'group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '40',
                'class' => 'acf-responsive',
                'id' => '',
            ),
            'layout' => 'block',
            'sub_fields' => array(
                array(
                    'key' => 'field_5f16d01fe2e7e',
                    'label' => 'In',
                    'name' => 'in_1h',
                    'type' => 'range',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => 'acf-responsive',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'min' => '',
                    'max' => '',
                    'step' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_5f16d043e2e7f',
                    'label' => 'Out',
                    'name' => 'out_1h',
                    'type' => 'range',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => 'acf-responsive',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'min' => '',
                    'max' => '',
                    'step' => '',
                    'prepend' => '',
                    'append' => '',
                ),
            ),
        );

	    yield array(
            'key' => 'field_5f16d0f5d568b',
            'label' => 'Dinner Date',
            'name' => 'dinner_date',
            'type' => 'group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '40',
                'class' => 'acf-responsive',
                'id' => '',
            ),
            'layout' => 'block',
            'sub_fields' => array(
                array(
                    'key' => 'field_5f16d110d568c',
                    'label' => 'In',
                    'name' => 'in_dd',
                    'type' => 'range',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => 'acf-responsive',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'min' => '',
                    'max' => '',
                    'step' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_5f16d13dd568d',
                    'label' => 'Out',
                    'name' => 'out_dd',
                    'type' => 'range',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => 'acf-responsive',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'min' => '',
                    'max' => '',
                    'step' => '',
                    'prepend' => '',
                    'append' => '',
                ),
            ),
        );

	    yield array(
            'key' => 'field_5f16d1708dbc4',
            'label' => 'Overnight',
            'name' => 'overnight',
            'type' => 'group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '40',
                'class' => 'acf-responsive',
                'id' => '',
            ),
            'layout' => 'block',
            'sub_fields' => array(
                array(
                    'key' => 'field_5f16d1878dbc5',
                    'label' => 'In',
                    'name' => 'in_ov',
                    'type' => 'range',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => 'acf-responsive',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'min' => '',
                    'max' => '',
                    'step' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_5f16d1b08dbc6',
                    'label' => 'Out',
                    'name' => 'out_ov',
                    'type' => 'range',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => 'acf-responsive',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'min' => '',
                    'max' => '',
                    'step' => '',
                    'prepend' => '',
                    'append' => '',
                ),
            ),
        );

    }

}