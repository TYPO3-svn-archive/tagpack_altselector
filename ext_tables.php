<?php
	// Include class for custom field
require_once(t3lib_extMgm::extPath('tagpack_altselector', 'class.tx_tagpackaltselector_tceforms.php'));

	// Define alternate TCA configuration for tag selector
t3lib_div::loadTCA('tx_tagpack_tags');
$TCA['tx_tagpack_tags']['tagpack_options']['altselector'] = $TCA['tx_tagpack_tags']['columns']['relations'];
$TCA['tx_tagpack_tags']['tagpack_options']['altselector']['exclude'] = 0;
$TCA['tx_tagpack_tags']['tagpack_options']['altselector']['config']['type'] = 'user';
$TCA['tx_tagpack_tags']['tagpack_options']['altselector']['config']['userFunc'] = 'tx_tagpackaltselector_TCEForms->selectorField';
$TCA['tx_tagpack_tags']['tagpack_options']['altselector']['config']['allowed'] = 'tx_tagpack_tags';
$TCA['tx_tagpack_tags']['tagpack_options']['altselector']['config']['prepend_tname'] = 0;
$TCA['tx_tagpack_tags']['tagpack_options']['altselector']['label'] = $TCA['tx_tagpack_tags']['ctrl']['title'];

	// Set the alternate configuration for the pages table
$TCA['tx_tagpack_tags']['tagpack_options']['alternateTCA']['pages'] = $TCA['tx_tagpack_tags']['tagpack_options']['altselector'];
?>
