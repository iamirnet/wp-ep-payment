<?php
function i_amir_net_ep_payment_get_forms($forms = null) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpefc_forms";
    if ($forms)
        $query = $wpdb->get_results("SELECT * FROM `$table_name` WHERE `id` IN ($forms) ORDER BY `id` DESC");
    else
        $query = $wpdb->get_results("SELECT * FROM `$table_name` ORDER BY `id` DESC");
    return $query;
}
