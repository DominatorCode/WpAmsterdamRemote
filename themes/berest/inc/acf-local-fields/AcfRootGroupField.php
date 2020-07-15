<?php

namespace DirectoryCustomFields;
use AcfLocalGroupField;

require get_template_directory() . '/inc/acf-local-fields/AcfLocalGroupField.php';

class AcfRootGroupField
{
    public $path_dir_acf = '/acf-json';

    /**
     * AcfRootGroupField constructor.
     */
    public function __construct()
    {
        add_filter('acf/settings/save_json', array($this, 'my_acf_json_save_point'));
        add_filter('acf/settings/load_json', array($this, 'my_acf_json_load_point'));
        add_action('admin_init', array($this, 'wpse29164_GenerateLocalAdminAcfForms'));
    }

    public function ApplyFilterTaxonomyFields()
    {
        add_action('init', array($this, 'wpse29164_applyAcfFilters'));
    }

    public function wpse29164_GenerateLocalAdminAcfForms(): void
    {
        if (function_exists('acf_add_local_field_group')):
            // root group key id
            $idKeyRoot = 'group_5eb935640cddd';

            // Generate fields with Custom Taxonomies
            // get list of custom taxonomies
            $taxonomies = $this->GetListCustomTaxonomies('objects');

            // contains generated code for fields
            $arr_fields = array();

            // contains group id and used taxonomy for generated group fields
            $arrKeysGroup = array();
            $indexGroupsTabs = 0;
            // generate key id
            $idKeyGroup = str_replace("group_", "field_", $idKeyRoot);
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

            // Generate "Rates" field
            $arr_fields[] = $this->CreateAcfTabField('rates_tab', 'Rates', 'field_5ecee45e7b68a');
            $arr_fields[] = $this->CreateAcfTextField('Model Rates', 'model_rates', '', array(
                'width' => '',
                'class' => '',
                'id' => 'fakeField',
            ));

            // Generate "Model Image Field"
            $arr_fields[] = $this->CreateAcfTabField('image_tab', 'Image','field_5ecee4837b68b');
            $arr_fields[] = $this->CreateAcfTextField('Model Images', 'model_images', '', array(
                'width' => '',
                'class' => '',
                'id' => 'fakeField',
            ));

            $arrDataGroupExport = array(
                'key' => $idKeyRoot,
                'title' => 'Model Parameters',
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
                    ]);

                    // generate columns for each parent term
                    $indexTerm = 0;
                    foreach ($terms as $term) {
                        $this->CreateAcfSubFieldTaxonomy($term->name,$term->slug,
                            'checkbox', $taxonomy->name, $arrKeysGroup[$indexGroup], $arrKeysGroup[$indexGroup] . $indexTerm++);
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

        // Step 2: Get custom taxonomies name and id from list
        if ($taxonomies) {
            foreach ($taxonomies as $taxonomy) {
                $terms = get_terms([
                    'taxonomy' => $taxonomy,
                    'hide_empty' => false,
                    'parent' => 0,
                ]);
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

    /**
     * Generates unique key id for ACF fields
     * @return string
     */
    public function GenerateUniqueKeyId() : string
    {
        return uniqid('field_', false);
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



}