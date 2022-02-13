<?php
global $i_amir_net_ep_payment_db_version;
$i_amir_net_ep_payment_db_version = '1.1';
function i_amir_net_ep_payment_payment_install()
{
    global $wpdb;
    global $i_amir_net_ep_payment_db_version;

    $installed_ver = get_option("i_amir_net_ep_payment_db_version");

    if ($installed_ver != $i_amir_net_ep_payment_db_version) {

        $table_name = $wpdb->prefix . "i_amir_net_ep_payment_";

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table_name}payment (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		name tinytext NOT NULL,
		title varchar(100) NOT NULL,
		currency tinytext NOT NULL,
		data text NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
        $sql .= "CREATE TABLE {$table_name}payment_form (
		payment_id int  NOT NULL,
		form_id int  NOT NULL,
		PRIMARY KEY  (payment_id,form_id)
	) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        add_option('i_amir_net_ep_payment_db_version', $i_amir_net_ep_payment_db_version);
        i_amir_net_ep_payment_payment_install_data();
    }
}

function i_amir_net_ep_payment_payment_install_data()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'i_amir_net_ep_payment_payment';

    $wpdb->insert(
        $table_name,
        array(
            'time' => current_time('mysql'),
            'name' => 'ZarinPal',
            'title' => 'زرین پال',
            'currency' => "IRT",
            'data' => '',
        )
    );
    $wpdb->insert(
        $table_name,
        array(
            'time' => current_time('mysql'),
            'name' => 'Mellat',
            'title' => 'به پرداخت ملت',
            'currency' => "IRR",
            'data' => '',
        )
    );
    $wpdb->insert(
        $table_name,
        array(
            'time' => current_time('mysql'),
            'name' => 'Sadad',
            'title' => 'سداد',
            'currency' => "IRR",
            'data' => '',
        )
    );
    $wpdb->insert(
        $table_name,
        array(
            'time' => current_time('mysql'),
            'name' => 'Saman',
            'title' => 'سامان کیش',
            'currency' => "IRR",
            'data' => '',
        )
    );
    $wpdb->insert(
        $table_name,
        array(
            'time' => current_time('mysql'),
            'name' => 'Payir',
            'title' => 'پی',
            'currency' => "IRR",
            'data' => '',
        )
    );
}

register_activation_hook(__FILE__, 'i_amir_net_ep_payment_payment_install');
register_activation_hook(__FILE__, 'i_amir_net_ep_payment_payment_install_data');

function i_amir_net_ep_payment_payment_update_db_check() {
    global $i_amir_net_ep_payment_db_version, $wpdb;
    if ( get_option( 'i_amir_net_ep_payment_db_version' ) != $i_amir_net_ep_payment_db_version ) {
        if (count(i_amir_net_ep_payment_payment_select()) > 0){
            $table_name = $wpdb->prefix . "i_amir_net_ep_payment_payment";
            $wpdb->get_results("DROP TABLE $table_name");
        }
        i_amir_net_ep_payment_payment_install();
        update_option('i_amir_net_ep_payment_db_version', $i_amir_net_ep_payment_db_version);
    }
}
add_action( 'plugins_loaded', 'i_amir_net_ep_payment_payment_update_db_check' );

function i_amir_net_ep_payment_payment_update($data,$payment) {
    global $wpdb;
    $table_name = $wpdb->prefix . "i_amir_net_ep_payment_payment";
    $query = $wpdb->update($table_name, $data, array("name" => $payment));
    if ($query)
        return false;
    else
        return true;
}
function i_amir_net_ep_payment_log_update($id, $data) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpefc_logs";
    $query = $wpdb->update($table_name, $data, array("id" => $id));
    if ($query)
        return false;
    else
        return true;
}
function i_amir_net_ep_payment_payment_selects($ids = null) {
    global $wpdb;
    $table_name = $wpdb->prefix . "i_amir_net_ep_payment_payment";
    $add = $ids ? " where id in (".implode("," , $ids).")" : null;
    $query = $wpdb->get_results("SELECT * FROM `$table_name`$add");
    return count($query) == 1 ? $query[0] : $query;
}
function i_amir_net_ep_payment_payment_select($name) {
    global $wpdb;
    $table_name = $wpdb->prefix . "i_amir_net_ep_payment_payment";
    $query = $wpdb->get_row("SELECT * FROM `$table_name` where `name` LIKE '{$name}'");
    return $query;
}
