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
 * $Id: class.tx_templatedisplay_tceforms.php 245 2009-10-13 12:58:47Z fsuter $
 */
class tx_tagpackaltselector_tceforms {

	/**
	 * This method renders the user-defined selector field
	 *
	 * @param	array			$PA: information related to the field
	 * @param	t3lib_tceform	$fobj: reference to calling TCEforms object
	 *
	 * @return	string	The HTML for the form field
	 */
	public function selectorField($PA, $fobj) {
		$formField = '';
//		$formField = t3lib_div::view_array($PA);
		$tagQuery = array(
			'SELECT'	=> 'tx_tagpack_tags.*, tx_tagpack_categories.name AS categoryname',
			'FROM'		=> 'tx_tagpack_tags LEFT JOIN tx_tagpack_categories ON (tx_tagpack_tags.category = tx_tagpack_categories.uid)',
			'WHERE'		=> '',
			'GROUPBY'	=> '',
			'ORDERBY'	=> 'tx_tagpack_categories.name, tx_tagpack_tags.name',
			'LIMIT'		=> ''
		);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECT_queryArray($tagQuery);
		$categorizedTags = array();
		while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) {
			$categoryName = $row['categoryname'];
			if (!isset($categorizedTags[$categoryName])) {
				$categorizedTags[$categoryName] = array();
			}
			$categorizedTags[$categoryName][] = array('uid' => $row['uid'], 'name' => $row['name']);
		}
		$maxTags = 0;
		foreach ($categorizedTags as $categoryName => $tags) {
			$maxTags = max($maxTags, count($tags));
		}
		$tableRows = array(0 => array());
		foreach ($categorizedTags as $categoryName => $tags) {
			$tableRows[0][] = $categoryName;
			for ($i = 0; $i < $maxTags; $i++) {
				$content = '&nbsp;';
				if (isset($tags[$i])) {
					$id = $PA['itemFormElID'] . '_' . $tags[$i]['uid'];
					$content = '<input type="checkbox" name="' . $PA['itemFormElName'] . '[]" id="' . $id . '" value="' . $tags[$i]['uid'] . '" class="checkbox" />';
					$content .= '<label for="' . $id . '">' . $tags[$i]['name'] . '</label>';
				}
				$tableRows[$i + 1][] = $content;
			}
		}
//		$formField .= t3lib_div::view_array($tableRows);
		$formField .= '<table cellpadding="2" cellspacing="1" border="0">';
		foreach ($tableRows as $index => $row) {
			$class = 'bgColor3-20';
			if ($index == 0) {
				$class = 'bgColor5';
			}
			$formField .= '<tr class="' . $class . '">';
			foreach ($row as $cell) {
				$formField .= '<td>' . $cell . '</td>';
			}
			$formField .= '</tr>';
		}
		$formField .= '</table>';
		return $formField;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tagpack_altselector/class.tx_tagpackaltselector_tceforms.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tagpack_altselector/class.tx_tagpackaltselector_tceforms.php']);
}

?>