<?php
// part of qEngine
require "./includes/user_init.php";
require './includes/checkout_lib.php';

$mode = get_param ('mode');
$xpress = get_param ('xpress');

// shipping mode
$ship_area = $config['cart']['ship_area'];

switch ($mode)
{
	case 'logout':
		kick_user ();
	break;


	case 'lost':
		qvc_init (3);
		$tpl_mode = 'lost';
		$txt['main_body'] = quick_tpl (load_tpl ('lost.tpl'), $txt);
		generate_html_header ("$config[site_name] $config[cat_separator] Lost Password");
	break;


	case 'reset':
		qvc_init (3);
		$tpl_mode = 'reset';
		$row['user_id'] = get_param ('user_id');
		$row['reset'] = get_param ('reset');
		$txt['main_body'] = quick_tpl (load_tpl ('lost.tpl'), $row);
		generate_html_header ("$config[site_name] $config[cat_separator] Reset Password");
	break;


	case 'register':
	case 'xpress':
	case 'address':
		qvc_init (3);
		if (($mode != 'address') && ($isLogin)) redir ($config['site_url'].'/profile.php');
		if (($mode == 'address') && (!$isLogin)) redir ($config['site_url'].'/profile.php');
		if (!$row = load_form ('register'))
		{
			if ($mode == 'address')
				$row = sql_qquery ("SELECT * FROM ".$db_prefix."user WHERE user_id='$current_user_id' LIMIT 1");
			else
				$row = create_blank_tbl ($db_prefix.'user');
		}

		// area
		$allow_city = $allow_state = $allow_country = true;
		if ($config['cart']['ship_area'] == 'local')
		{
			$allow_city = $allow_state = $allow_country = false;
			$row['bill_city'] = $row['ship_city'] = $config['site_city'];
			$row['bill_state'] = $row['ship_state'] = $config['site_state'];
			$row['bill_country'] = $row['ship_country'] = $config['site_country'];
		}
		elseif ($config['cart']['ship_area'] == 'state')
		{
			$allow_state = $allow_country = false;
			$row['bill_state'] = $row['ship_state'] = $config['site_state'];
			$row['bill_country'] = $row['ship_country'] = $config['site_country'];
		}
		elseif ($config['cart']['ship_area'] == 'nation')
		{
			$allow_country = false;
			$row['bill_country'] = $row['ship_country'] = $config['site_country'];
		}
		else
		{
			$country_def = get_country_list ();
			$row['bill_country_select'] = create_select_form ('bill_country', $country_def, $row['bill_country'] ? $row['bill_country'] : $config['site_country']);
			$row['ship_country_select'] = create_select_form ('ship_country', $country_def, $row['ship_country'] ? $row['ship_country'] : $config['site_country']);
		}
		$txt = array_merge ($txt, $row);

		if ($mode == 'register')
		{
			$tpl_mode = 'register';
			$txt['main_body'] = quick_tpl (load_tpl ('register.tpl'), $txt);
		}
		elseif ($mode == 'address')
		{
			$tpl_mode = 'address';
			$txt['main_body'] = quick_tpl (load_tpl ('address.tpl'), $txt);
		}
		else // xpress c.o
		{
			$tpl_mode = 'xpress';
			sql_query ("DELETE FROM ".$db_prefix."user WHERE user_id='$current_user_id' LIMIT 1");
			if (!$config['cart']['allow_xpress']) msg_die ('Express checkout is not enabled.');
			$txt['main_body'] = quick_tpl (load_tpl ('register.tpl'), $txt);
		}

		if ($mode == 'address')
			generate_html_header ("$config[site_name] $config[cat_separator] My Addresses");
		else
			generate_html_header ("$config[site_name] $config[cat_separator] Registration");
	break;
case 'booth':




if ($isLogin)
		{
			// using xpress?
			$row = sql_qquery ("SELECT * FROM ".$db_prefix."user WHERE user_id='$current_user_id' AND user_passwd='++XPRESS++' LIMIT 1");
			$xpress = true;

			// no xpress & no login => redir
			if (empty ($row))
			{
				ip_config_update ('redir', $config['site_url']."/checkout.php?step=2&weight=$weight&total=$total&item=$item");
				redir ($config['site_url'].'/profile.php?xpress=1');
			}
		}

		// -- checking cart (if no item, exit!)
		$cart = get_cart ('foo');
		if (!$cart['item_num']) msg_die ($lang['msg']['no_item_in_cart']);

		// addresses
		$user = get_user_info ($current_user_id);
		$user['bill_address'] = 'test';
		$user['ship_address'] = 'test';
		$user['ship_country'] = 'usa';

		// shippers
		if ($cart['all_digital']) $all_digital = true; else $all_digital = false;
		if ($cart['all_digital'] && ($config['cart']['hide_ship'])) $shipping_option = false; else $shipping_option = true;
		$tpl = load_tpl ('checkout2.tpl');
		$txt['block_courier_item'] = ''; $i = 0;

		if ($shipping_option)
		{
			$t = get_courier_fee (array ('order_items' => $cart['item_num'], 'order_total' => $cart['total'], 'order_weight' => $cart['weight'], 'all_digital' => $cart['all_digital']), $user);
			foreach ($t as $val)
			{
				$val['i'] = $i++;

				if (empty ($val['fee']))
				{
					$val['fee'] = '('.$lang['l_courier_free'].')';
					if (count ($t) == 1) $val['selected'] = 'checked="checked"'; else $val['selected'] = '';
				}
				else
				{
					$val['fee'] = num_format ($val['fee'], 0, 1);
					if (count ($t) == 1) $val['selected'] = 'checked="checked"'; else $val['selected'] = '';
				}

				$txt['block_courier_item'] .= quick_tpl ($tpl_block['courier_item'], $val);
			}
		}
		else
			$t = get_courier_fee (array ('order_items' => $item, 'order_total' => $total, 'order_weight' => $weight, 'all_digital' => true), $user, 'ship_free');


		// payment
		$txt['block_pay_item'] = ''; $i = 0;
		$t = get_payment_method ();
		foreach ($t as $val)
		{
			$val['i'] = $i++;
			$val['fee'] = $val['fee'] ? num_format ($val['fee'], 0, 1) : '-';
			if (count ($t) == 1) $val['selected'] = 'checked="checked"'; else $val['selected'] = '';
			$txt['block_pay_item'] .= quick_tpl ($tpl_block['pay_item'], $val);
		}

		// display
		$txt = array_merge ($txt, $user);
		$txt['main_body'] = quick_tpl ($tpl, $txt);
		generate_html_header ("$config[site_name] $config[cat_separator] Checkout 1/2");
		flush_tpl ();
	break;

	case 'act':
		$row['user_id'] = get_param ('user_id');
		$row['act'] = get_param ('act');
		$txt['main_body'] = quick_tpl (load_tpl ('act.tpl'), $row);
		generate_html_header ("$config[site_name] $config[cat_separator] Account Activation");
	break;


	default:
		if (!$isLogin)
		{
			// login form
			qvc_init (3);
			$profile_mode = 'login';
			if (!$config['cart']['allow_xpress']) $xpress = FALSE;
			$txt['url'] = get_param ('url');
			$txt['main_body'] = quick_tpl (load_tpl ('login.tpl'), $txt);
			generate_html_header ("$config[site_name] $config[cat_separator] Login");
		}
		else
		{
			// get ID
			$res = sql_query ("SELECT * FROM ".$db_prefix."user WHERE user_id = '$current_user_id' LIMIT 1");
			$row = sql_fetch_array ($res);
			$txt['main_body'] = quick_tpl (load_tpl ('profile.tpl'), $row);
			generate_html_header ("$config[site_name] $config[cat_separator] My Profile");
		}
	break;
}

flush_tpl ();
?>