<?php

namespace DirectoryCustomFields;

class AcfFrontPageDisplay
{
	/**
	 * Prints data result in front page table
	 * @param string $name_cf
	 * Left column
	 * @param string $value_cf
	 * Right column
	 */
	public static function PrintCfTable(string $name_cf, string $value_cf): void
	{
		// printing in two column way name | value
		echo '<tr>';
		echo '<td class="text-left">' . $name_cf . ":" . '</td>';
		echo '<td class="text-right">' . $value_cf . '</td>';
		echo '</tr>';

	}

	/**
	 * @param array $subfields
	 * @param string $name_taxonomy
	 * @return array
	 */
	public static function AcfExtractDataFields(array $subfields, string $name_taxonomy): array
	{
		$result = array();
		foreach ($subfields as $key => $value) {
			if (!empty($value)) {
				$field = get_sub_field_object($key);

				// select all child tax ids for current parent tax from common taxes array
				// Setup blank array
				$arr_ids = array();
				foreach ($field['value'] as $id_child) {
					// get parent tax name of child term
					$child_term = get_term($id_child, $name_taxonomy);
					$term_parent = get_term($child_term->parent, $name_taxonomy)->name;

					// compare parent name with loop parent
					if (strcmp($term_parent, $field['label']) == 0) {
						$arr_ids[] = $id_child;
					}
				}

				$get_terms_args = array(
					'taxonomy' => $name_taxonomy,
					'hide_empty' => 0,
					'include' => $arr_ids,
				);
				// get selected terms
				$terms = get_terms($get_terms_args);

				$value_term = '';
				if ($terms) :

					foreach ($terms as $term) :
						$value_term .= $term->name . ', ';
					endforeach;
					$value_term = rtrim($value_term, ', ');

				endif;
				$result[] = ['name_field' => $field['label'], 'name_value' => $value_term];
			}
		}
		return array($result);
	}
}