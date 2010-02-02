<?php
/***************************************************************
* Copyright notice
*
* (c) 2010 Tolleiv Nietsch (info@tolleiv.de)
* (c) 2010 Steffen Kamper (info@sk-typo3.de)
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
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
***************************************************************/

class tx_templavoila_preview_type_text {

	protected $previewField = 'bodytext';

	/**
	 *
	 * @param array $row
	 * @param string $table
	 * @param string $output
	 * @param boolean $alreadyRendered
	 * @param object $ref
	 * @return string
	 */
	public function render_previewContent ($row, $table, $output, $alreadyRendered, &$ref) {
		$label = $this->getPreviewLabel();
		$data = $this->getPreviewData($row);

		return $ref->link_edit('<strong>' . $label . '</strong> ' . $data , 'tt_content', $row['uid']);
	}

	/**
	 * @return string
	 */
	protected function getPreviewLabel() {
		return $GLOBALS['LANG']->sL(t3lib_BEfunc::getItemLabel('tt_content',$this->previewField),1);
	}

	/**
	 *
	 * @param array $row
	 * @return string
	 */
	protected function getPreviewData($row) {
		return $this->preparePreviewData($row[$this->previewField]);
	}


	/**
	 * Performs a cleanup of the field values before they're passed into the preview
	 *
	 *.@param	string		$str: input usually taken from bodytext or any other field
	 * @param	integer		$max: some items might not need to cover the full maximum
	 * @param	boolean		$stripTags: HTML-blocks usually keep their tags
	 * @return	string		the properly prepared string
	 */
	protected function preparePreviewData($str, $max = 2000, $stripTags = true) {
			//Enable to omit that parameter
		if ($max === null) {
			$max = 2000;
		}
		if ($stripTags) {
				//remove tags but avoid that the output is concatinated without spaces (#8375)
			$newStr = strip_tags(preg_replace('/(\S)<\//', '\1 </', $str));
		} else {
			$newStr = $str;
		}
		return htmlspecialchars(t3lib_div::fixed_lgd_cs(trim($newStr), $max));
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/templavoila/classes/preview/class.tx_templavoila_preview_type_text.php'])    {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/templavoila/classes/preview/class.tx_templavoila_preview_type_text.php']);
}

?>