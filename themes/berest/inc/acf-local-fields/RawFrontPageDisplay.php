<?php

namespace DirectoryCustomFields;

/**
 * Class RawFrontPageDisplay
 * Contains data for correct display Custom fields
 * @package DirectoryCustomFields
 */
class RawFrontPageDisplay
{
    //public  $arrTermsData = array();

    public static function GetPostMainData($pWp_query)
    {
        return $pWp_query->get_queried_object();
    }

    /**
     * Extract passed taxonomy cf data and prints it
     * @param int $idPost
     * @param string $name_taxonomy
     */
    public static function GetCfDataAndPrint(int $idPost, string $name_taxonomy): void
    {
        // get used terms for current post
        $term_names = wp_get_post_terms($idPost, $name_taxonomy, array('fields' => 'all'));

        // get list of parent terms for taxonomy
        $arrTermsCfData = self::GetParentTermsForTaxonomy($name_taxonomy);

        // match used post terms with parent terms
        foreach ($term_names as $termRow) {
            foreach ($arrTermsCfData as $key => $value) {
                if ($termRow->parent === $value['Id']) {
                    if (empty($arrTermsCfData[$key]['Value'])) {
                        $arrTermsCfData[$key]['Value'] = $termRow->name;
                    } else {
                        $arrTermsCfData[$key]['Value'] .= ', ' . $termRow->name;
                    }

                    break;
                }
            }
        }

        // display results
        foreach ($arrTermsCfData as $termDataRow) {
            self::PrintCfTable($termDataRow, false);
        }
    }


    /**
     * Returns an array data for used terms in Taxonomy
     * @param $pNameTaxonomy
     * @return array
     */
    public static function GetParentTermsForTaxonomy($pNameTaxonomy): array
    {
        $arrTermsData = array();
        $top_level_terms = get_terms(array(
            'taxonomy' => $pNameTaxonomy,
            'parent' => '0',
            'hide_empty' => false,
        ));

        // only if some terms actually exists, we move on
        if ($top_level_terms) {

            foreach ($top_level_terms as $top_level_term) {

                // the id of the top-level-term, we need this further down
                $top_term_id = $top_level_term->term_id;
                // the name of the top-level-term
                $top_term_name = $top_level_term->name;

                // add to array
                $arrTermsData[] = array('Name' => $top_term_name, 'Id' => $top_term_id, 'Value' => '');
            }
        }
        return $arrTermsData;
    }

    /**
     * Prints custom fields data in front page table
     * @param array $pArrCfData
     * [Name][Id][Value]
     * @param bool $isDisplayEmptyValue
     */
    public static function PrintCfTable(array $pArrCfData, $isDisplayEmptyValue = true): void
    {
        // printing in two column way name | value
        if (!$isDisplayEmptyValue && empty($pArrCfData['Value'])) {
            return;
        }

        echo '<tr>';
        echo '<td class="text-left">' . $pArrCfData['Name'] . ":" . '</td>';
        echo '<td class="text-right">' . $pArrCfData['Value'] . '</td>';
        echo '</tr>';

    }

}