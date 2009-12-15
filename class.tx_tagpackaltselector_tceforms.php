<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Francois Suter (Cobweb) <typo3@cobweb.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
*
***************************************************************/

/**
 * TCEform custom field for selecting tags from tag pack
 *
 * @author		Francois Suter (Cobweb) <typo3@cobweb.ch>
 * @package		TYPO3
 * @subpackage	tx_tagpackaltselector
 *
 * $Id$
 */
class tx_tagpackaltselector_tceforms {
	/**
	 * @var	string	ID of the element that wraps around all checkboxes
	 */
	static protected $checkboxesParentID = 'tx_tagpackaltselector_checkbox';

	/**
	 * This method renders the user-defined selector field
	 *
	 * @param	array			$PA: information related to the field
	 * @param	t3lib_tceform	$formObject: reference to calling TCEform object
	 *
	 * @return	string	The HTML for the form field
	 */
	public function selectorField($PA, $formObject) {
			// Add the needed JavaScript file
		$formObject->additionalCode_pre['tx_tagpackaltselector'] = '<script src="' . t3lib_extMgm::extRelPath('tagpack_altselector') . 'resources/tceform.js" type="text/javascript"></script>';
		$formField = '';

			// Get all the tags and their categories
		$tagQuery = array(
			'SELECT'	=> 'tx_tagpack_tags.*, tx_tagpack_categories.name AS categoryname',
			'FROM'		=> 'tx_tagpack_tags LEFT JOIN tx_tagpack_categories ON (tx_tagpack_tags.category = tx_tagpack_categories.uid)',
			'WHERE'		=> '',
			'GROUPBY'	=> '',
			'ORDERBY'	=> 'tx_tagpack_categories.name, tx_tagpack_tags.name',
			'LIMIT'		=> ''
		);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECT_queryArray($tagQuery);
			// Sort tags by category
		$categorizedTags = array();
		while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) {
			$categoryName = $row['categoryname'];
			if (!isset($categorizedTags[$categoryName])) {
				$categorizedTags[$categoryName] = array();
			}
			$categorizedTags[$categoryName][] = array('uid' => $row['uid'], 'name' => $row['name']);
		}
			// Calculate the maximum number of tags among the categories
		$maxTags = 0;
		foreach ($categorizedTags as $categoryName => $tags) {
			$maxTags = max($maxTags, count($tags));
		}
			// Get the selected tags for the current item
		$itemRows = tx_tagpack_api::getAttachedTagsForElement($PA['row']['uid'], $PA['table']);
		$selectedTags = array();
		$selectedTagsString = '';
		foreach ($itemRows as $tagRow) {
			$selectedTags[] = $tagRow['uid'];
			$selectedTagsString .= $tagRow['uid'] . ',';
		}

			// Assemble table rows
			// Each column represents a category
			// Each cell contains a tag with a checkbox for selection
			// If a category has less tags that the max number of tags, an empty content is generated
		$tableRows = array(0 => array());
		foreach ($categorizedTags as $categoryName => $tags) {
			$tableRows[0][] = $categoryName;
			for ($i = 0; $i < $maxTags; $i++) {
				$content = '&nbsp;';
				if (isset($tags[$i])) {
					$id = $PA['itemFormElID'] . '_' . $tags[$i]['uid'];
					$checked = '';
					if (in_array($tags[$i]['uid'], $selectedTags)) {
						$checked = ' checked="checked"';
					}
						// Render the checkbox and its label
						// NOTE: the "tx_tagpackselector_checkbox" class is set so that it's easy
						// to select all relevant checkboxes using JavaScript
					$content = '<input type="checkbox" name="' . $PA['itemFormElName'] . '_check" id="' . $id . '" value="' . $tags[$i]['uid'] . '" class="checkbox"'. $checked . ' onchange="updateSelectedList(\'' . self::$checkboxesParentID . '\', \'' . $PA['itemFormElID'] . '\')" />';
					$content .= '<label for="' . $id . '">' . $tags[$i]['name'] . '</label>';
				}
				$tableRows[$i + 1][] = $content;
			}
		}

			// Assemble complete table with the rows prepared above
		$formField .= '<table cellpadding="2" cellspacing="1" border="0" id="' . self::$checkboxesParentID . '">';
		foreach ($tableRows as $index => $row) {
			$class = 'bgColor3-20';
			$style = '';
			if ($index == 0) {
				$class = 'bgColor5';
				$style = ' style="padding: 4px; font-weight: bold;"';
			}
			$formField .= '<tr class="' . $class . '">';
			foreach ($row as $cell) {
				$formField .= '<td' . $style . '>' . $cell . '</td>';
			}
			$formField .= '</tr>';
		}
		$formField .= '</table>';
			// Add hidden field for containing comma-separated list of selected values
		$formField .= '<input type="hidden" name="' . $PA['itemFormElName'] . '" id="' . $PA['itemFormElID'] . '" value="' . $selectedTagsString . '" />';
		return $formField;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tagpack_altselector/class.tx_tagpackaltselector_tceforms.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tagpack_altselector/class.tx_tagpackaltselector_tceforms.php']);
}

?>