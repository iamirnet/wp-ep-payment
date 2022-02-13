<?php
function i_amir_net_ep_payment_sadad()
{
    if (!current_user_can('i_amir_net_ep_payment_manage')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    if ($_POST){
        $data = array('TerminalId' => stripslashes_deep($_POST['TerminalId']), 'MerchantId' => stripslashes_deep($_POST['MerchantId']), 'key' => stripslashes_deep($_POST['key']));
        i_amir_net_ep_payment_payment_update(
            array("title" => stripslashes_deep($_POST['title']),"data" => json_encode($data), "currency" => stripslashes_deep($_POST['currency'])),
            "Sadad"
        );
        $payment = i_amir_net_ep_payment_payment_select("Sadad");
        i_amir_net_ep_payment_payment_forms_update($payment->id,$_POST['ep_forms']);
    }
    $payment = i_amir_net_ep_payment_payment_select("Sadad");
    $forms = i_amir_net_ep_payment_get_forms();
    $selectors =  i_amir_net_ep_payment_payment_forms_select($payment->id);
    foreach ($selectors as $selector) {
        $selected[] = $selector->form_id;
    }
    foreach ($forms as $form) {
        if ($selectors)
            $checked = array_search($form->id, $selected) !== false ? "selected" : null;
        $select_data .= "<option value=\"{$form->id}\" $checked>{$form->title}</option>";
    }
    $data = json_decode($payment->data);
    ?>
    <div class="wrap">

        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

        <form method="post" action="<?php echo esc_html(admin_url('admin.php?page=i_amir_net_ep_sadad')); ?>">
            <table class="form-table" role="presentation">

                <tbody>
                <tr>
                    <th scope="row">
                        <label for="title">نام درگاه</label>
                    </th>
                    <td>
                        <input name="title" type="text" id="title" value="<?php echo $payment->title; ?>" placeholder="نام درگاه را وارد کنید" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="TerminalId">شماره ترمینال</label>
                    </th>
                    <td>
                        <input name="TerminalId" type="text" id="TerminalId" value="<?php echo $data->TerminalId; ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="MerchantId">مرچنت</label>
                    </th>
                    <td>
                        <input name="MerchantId" type="text" id="MerchantId" value="<?php echo $data->MerchantId; ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="key">شناسه (key)</label>
                    </th>
                    <td>
                        <input name="key" type="text" id="key" value="<?php echo $data->key; ?>" class="regular-text">
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="currency">واحد پول</label></th>
                    <td>
                        <select name="currency" id="currency">
                            <option value="IRT" <?php echo $payment->currency == "IRT" ? "selected" : null; ?>>تومان</option>
                            <option value="IRR" <?php echo $payment->currency == "IRR" ? "selected" : null; ?>>ریال</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="ep_forms">E&P فرم ها</label></th>
                    <td>
                        <select name="ep_forms[]" id="ep_forms" multiple>
                            <?php echo $select_data;?>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>

            <?php
            wp_nonce_field('acme-settings-save', 'acme-custom-message');
            submit_button();
            ?>
        </form>

    </div><!-- .wrap -->
    <?php
}
