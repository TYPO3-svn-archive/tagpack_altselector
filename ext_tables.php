<?php
	// Include class for custom field
require_once(t3lib_extMgm::extPath('tagpack_altselector', 'class.tx_tagpackaltselector_tceforms.php'));

	// Define alternate TCA configuration for tag selector
$alternateConfiguration = array(
								'label' => $TCA['tx_tagpack_tags']['ctrl']['title'],
								'exclude' => 0,
								'config' => array(
									'type' => 'user',
									'userFunc' => 'tx_tagpackaltselector_TCEForms->selectorField',
								)
							);

	// Set the alternate configuration for the pages table
$T3_VAR['EXT']['tagpack']['TCA']['alternate_config']['pages'] = $alternateConfiguration;
?>
