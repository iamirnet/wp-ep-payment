<?php

function i_amir_net_ep_payment_action_form()
{
    header('Content-Type: application/json');
    if (isset($_POST['formID']) && !is_array($_POST['formID'])) {

        global $wpdb;
        $table_name = $wpdb->prefix . "i_amir_net_ep_payment_payment_form";
        $formID = sanitize_text_field($_POST['formID']);
        $pivot = $wpdb->get_results("SELECT * FROM `$table_name` where `form_id`={$formID}");
        if (count($pivot) > 0) {
            foreach ($pivot as $payment_form) {
                $ids[] = $payment_form->payment_id;
            }
            $payments = i_amir_net_ep_payment_payment_selects($ids);
            if (count($payments) == 1) {
                $paymentAll = [
                    "use_{$payments->name}" => "1",
                    "txt_btn{$payments->name}" => "پرداخت با {$payments->title}",
                    "payments" => [$payments->name],
                ];
            } else {
                foreach ($payments as $payment) {
                    $paymentAll["use_{$payment->name}"] = "1";
                    $paymentAll["txt_btn{$payment->name}"] = "پرداخت با {$payment->title}";
                    $paymentAll["payments"][] = $payment->name;
                }
            }
            $table_name = $wpdb->prefix . "wpefc_forms";
            $row = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$formID");
            echo json_encode(array_merge([
                "id" => $row->id,
                "title" => $row->title,
                "ref_root" => $row->ref_root,
                "current_ref" => $row->current_ref,
                "use_paypal" => $row->use_paypal,
                "txt_btnPaypal" => $row->txt_btnPaypal,
                "use_stripe" => $row->use_stripe,
                "txt_btnStripe" => $row->txt_btnStripe,
                "use_razorpay" => $row->use_razorpay,
                "txt_btnRazorpay" => $row->txt_btnRazorpay,
            ], $paymentAll));
            die();
        }
    }
}

add_action('wp_ajax_i_amir_net_ep_payment_action_form', 'i_amir_net_ep_payment_action_form');
add_action('wp_ajax_nopriv_i_amir_net_ep_payment_action_form', 'i_amir_net_ep_payment_action_form');

function i_amir_net_ep_payment_go_pay()
{
    header('Content-Type: application/json');
    global $wpdb, $zarinpal, $mellat, $payir, $saman, $sadad;
    $ipg = sanitize_text_field($_POST['ipg']);
    $payment = i_amir_net_ep_payment_payment_select($ipg);
    $formID = sanitize_text_field($_POST['formID']);
    $singleTotal = sanitize_text_field($_POST['singleTotal']);
    $ref = sanitize_text_field($_POST['ref']);
    $subTotal = sanitize_text_field($_POST['subTotal']);
    $customerInfos = ($_POST['customerInfos']);

    $table_name = $wpdb->prefix . "wpefc_forms";
    $formReq = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE id=%s LIMIT 1", $formID));
    if (count($formReq) > 0) {
        $form = $formReq[0];
        $url = $_POST['url'];
        $url = addGetParams($url, array('ipg' => $ipg,'form' => $formID, 'ref' => $form->ref_root . $form->current_ref));
        $total = $singleTotal;
        if ($subTotal > 0 && $singleTotal == 0) {
            $total = $subTotal;
        }

        if ($total > 0) {
            switch (strtolower($ipg)) {
                case 'zarinpal':
                    $result = $zarinpal->send($payment->data, $total, $form->title . ' (' . $form->ref_root . $form->current_ref . ')', $url, $payment->currency, $customerInfos->email);
                    break;
                case 'mellat':
                    $result = $mellat->send($payment->data, $total, $form->ref_root . $form->current_ref, $url, $payment->currency);
                    break;
                case 'saman':
                    $result = $saman->send($payment->data, $total, $form->ref_root . $form->current_ref, $url, $payment->currency);
                    break;
                case 'sadad':
                    $result = $sadad->send($payment->data, $total, $form->ref_root . $form->current_ref, $url, $payment->currency);
                    break;
                case 'payir':
                    $result = $payir->send($payment->data, $total, $form->ref_root . $form->current_ref, $url, $payment->currency);
                    break;
            }
            echo json_encode(array_merge($result, array('title' => $payment->title, 'name' => $payment->name)));
        }
    }
    die();
}

add_action('wp_ajax_i_amir_net_ep_payment_go_pay', 'i_amir_net_ep_payment_go_pay');
add_action('wp_ajax_nopriv_i_amir_net_ep_payment_go_pay', 'i_amir_net_ep_payment_go_pay');

function i_amir_net_ep_payment_pay_check()
{
    header('Content-Type: application/json');
    global $wpdb, $zarinpal, $mellat, $payir, $saman, $sadad;
    $postReq = $_POST['postReq'];
    $getReq = $_POST['getReq'];
    $ipg = sanitize_text_field($getReq['ipg']);
    $payment = i_amir_net_ep_payment_payment_select($ipg);
    $formID = sanitize_text_field($_POST['formID']);
    $ref = sanitize_text_field($_POST['ref']);

    $table_logs = $wpdb->prefix . "wpefc_logs";
    $order = $wpdb->get_row("SELECT * FROM $table_logs WHERE `formID`={$formID} and `ref` LIKE '{$ref}'");
    $table_form = $wpdb->prefix . "wpefc_forms";
    $form = $wpdb->get_row("SELECT * FROM $table_form WHERE id=$formID LIMIT 1");
    switch (strtolower($ipg)) {
        case 'zarinpal':
            $result = $zarinpal->verify($payment->data, $getReq['authority'], $order->totalPrice, $payment->currency);
            break;
        case 'mellat':
            $result = $mellat->verify($payment->data, $postReq['orderId'], $postReq['verifySaleOrderId'],  (float) $postReq['verifySaleReferenceId']);
            break;
        case 'saman':
            $result = $saman->verify($payment->data, $postReq['RefNum'], $postReq['State']);
            break;
        case 'sadad':
            $result = $sadad->verify($payment->data, $postReq['Token'], $postReq['ResCode']);
            break;
        case 'payir':
            $result = $payir->verify($payment->data, $getReq['token']);
            break;
    }
    if ($result['status']) {
        $order_change = array(
            "paid" => "1",
            "paymentKey" => $result['transId'],
            "status" => 'shipped',
        );
        i_amir_net_ep_payment_log_update($order->id, $order_change);
        echo json_encode(array_merge($result, array("form" => $form->title, "ref" => $order->ref, 'payment' => $payment->title)));
    } else {
        echo json_encode($result);
    }
    die();
}

add_action('wp_ajax_i_amir_net_ep_payment_pay_check', 'i_amir_net_ep_payment_pay_check');
add_action('wp_ajax_nopriv_i_amir_net_ep_payment_pay_check', 'i_amir_net_ep_payment_pay_check');

