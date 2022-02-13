<?php
add_action('admin_menu', 'i_amir_net_ep_payment_manage_admin_menu');
function i_amir_net_ep_payment_manage_admin_menu()
{
    add_menu_page('E&P Payment', 'E&P Payment', 'i_amir_net_ep_payment_manage', 'i_amir_net_ep_payment_ep', '', 'dashicons-cart', 15);
    add_submenu_page('i_amir_net_ep', 'درباره ما', 'درباره ما', 'i_amir_net_ep_payment_manage', 'i_amir_net_ep', 'i_amir_net_ep_payment_about');
    add_submenu_page('i_amir_net_ep', 'زرین پال', 'زرین پال', 'i_amir_net_ep_payment_manage', 'i_amir_net_ep_zarinpal', 'i_amir_net_ep_payment_zarinpal');
    add_submenu_page('i_amir_net_ep', 'پی آی آر', 'پی آی آر', 'i_amir_net_ep_payment_manage', 'i_amir_net_ep_payir', 'i_amir_net_ep_payment_payir');
    add_submenu_page('i_amir_net_ep', 'سداد', 'سداد', 'i_amir_net_ep_payment_manage', 'i_amir_net_ep_sadad', 'i_amir_net_ep_payment_sadad');
    add_submenu_page('i_amir_net_ep', 'به پرداخت', 'به پرداخت', 'i_amir_net_ep_payment_manage', 'i_amir_net_ep_mellat', 'i_amir_net_ep_payment_mellat');
    add_submenu_page('i_amir_net_ep', 'سامان کیش', 'سامان کیش', 'i_amir_net_ep_payment_manage', 'i_amir_net_ep_saman', 'i_amir_net_ep_payment_saman');

}
