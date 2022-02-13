<?php
//include 'forms.php';
function i_amir_net_ep_payment_payment_forms_update($payment, $forms) {
    global $wpdb;
    $table_name = $wpdb->prefix . "i_amir_net_ep_payment_payment_form";
    $pivot_records = $wpdb->get_results("SELECT * FROM `$table_name`");

    foreach ($pivot_records as $pivot_render) {
        if (array_search($pivot_render->form_id, $forms) === false)
            $wpdb->delete( $table_name, array("payment_id" => $payment, "form_id" => $pivot_render->form_id));
    }
    foreach ($forms as $form){
        $pivot_render = $wpdb->get_row("SELECT * FROM `$table_name` where payment_id=$payment && form_id=$form");
        $form = array("payment_id" => stripslashes_deep($payment), "form_id" => stripslashes_deep($form));
        if ($pivot_render)
            $wpdb->update($table_name, $form, $form);
        else
            $wpdb->insert($table_name, $form);
    }
    //return i_amir_net_ep_payment_get_forms(implode(',',$forms));
}

function i_amir_net_ep_payment_payment_forms_select($payment = null) {
    global $wpdb;
    $table_name = $wpdb->prefix . "i_amir_net_ep_payment_payment_form";
    $query = $wpdb->get_results("SELECT * FROM `$table_name` where `payment_id`={$payment}");
    return $query;
}
