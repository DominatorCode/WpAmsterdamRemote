<?php

namespace DirectoryCustomFields;

/**
 * Contains main settings for project
 * @package DirectoryCustomFields
 */
class ConfigurationParameters
{
    public static $name_postfix_rates_in = '_in';
    public static $name_postfix_rates_out = '_out';
    public static $data_terms_input_range = array('Taxonomy' => 'statistics', 'Id' => 111);
    public static $name_booking_subject = 'Feedback from escort booking';
    
    // list of custom meta names for terms
    public static $email_booking = '';
    public static $slug_taxonomy_location = 'location';
    public static $name_term_featured = 'Featured';
    public static $name_term_hot_model = 'Party Girl';
    public static $name_term_blog = 'Blog';
    public static $name_page_home = 'HOME';
    
    //<editor-fold desc="Booking form">
    public static $slug_taxonomy_main = 'category';
    /**
     * @var int
     */
    public static $count_posts_pagi = -1; // get_option('admin_email'); UPDATE
    //</editor-fold>
    public static $name_email_admin = 'ceroff@mail.ru';
    private static $id_key_field_sub_custom = 'field_5f29e46ced777';
    private static $list_term_names_meta = null;

    /**
     * @return null
     */
    public static function getListTermNamesMeta() : ?array
    {
        if (empty(self::$list_term_names_meta)) {
            self::$list_term_names_meta   = array();
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
    public static function getIdKeyFieldSubCustom() : string
    {
        return self::$id_key_field_sub_custom;
    }
    
    /**
     * Returns an array of child terms objects for given parent term
     *
     * @param $name_taxonomy
     * @param $id_term_parent
     *
     * @return int|\WP_Error|\WP_Term[]
     */
    public static function getTermsList($name_taxonomy, $id_term_parent)
    {
        return get_terms(
            array(
                'taxonomy'   => $name_taxonomy,
                'hide_empty' => false,
                'parent'     => $id_term_parent,
            )
        );
    }
    
    /**
     * Returns a term id by its name
     *
     * @param $name_term
     * @param string $name_slug_taxonomy
     *
     * @return int
     */
    public static function getTermIdByName($name_term, string $name_slug_taxonomy) : int
    {
        return get_term_by('name', $name_term, $name_slug_taxonomy)->term_id;
    }

    /**
     * Returns and Id of post by given Name
     *
     * @param $name_title
     *
     * @return int
     */
    public static function getPageIdByTitle($name_title) : int
    {
        $obj_page = get_page_by_title($name_title);
        
        return $obj_page->ID ?? -1;
    }
}
