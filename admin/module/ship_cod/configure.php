<?php
$cmd = get_param ('cmd');
$mod_id = 'ship_cod';
switch ($cmd)
{
	case 'save':
		$display_name = get_param ('display_name');
		$fee = get_param ('fee');
		if (!empty ($display_name)) sql_query ("UPDATE ".$db_prefix."module SET mod_name='$display_name' WHERE mod_id='$mod_id' LIMIT 1");
		update_mod_config ($mod_id, 'fee', $fee);
		admin_die ('admin_ok');
	break;


	default:
		// uses module_ez_config template
		$tpl = load_tpl ('var', $tpl_section['module_ez_config']);

		// load the configuration values
		$row = sql_qquery ("SELECT * FROM ".$db_prefix."module WHERE mod_id='$mod_id' LIMIT 1");

		// init some stuffs
		$row['config_title'] = 'Shipping Module: Cash on Delivery';
		$row['mod_id'] = $mod_id;
		$row['hidden_values'] = create_hidden_form ('what', 'module').create_hidden_form ('mod_id', $row['mod_id']).create_hidden_form ('cmd', 'save');

		// 1. configuration items
		$items = array (
			array ('config_label' => 'Display name', 'config_value' => create_varchar_form ('display_name', $row['mod_name'])),
			array ('config_label' => 'Convenience Fee '.$lang['l_cur_name'], 'config_value' => create_varchar_form ('fee', $module_config[$mod_id]['fee']))
		);

		// 2. create block of items
		$row['block_configuration'] = '';
		foreach ($items as $k => $v) $row['block_configuration'] .= quick_tpl ($tpl_block['configuration'], $v);

		// output
		$txt['main_body'] = quick_tpl ($tpl, $row);
	break;
}
?>