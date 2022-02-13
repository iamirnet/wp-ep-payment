<?php
function i_amir_net_ep_payment_mellat()
{
    if (!current_user_can('i_amir_net_ep_payment_manage')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    if ($_POST){
        $data = array('terminalId' => stripslashes_deep($_POST['terminalId']), 'userName' => stripslashes_deep($_POST['userName']), 'userPassword' => stripslashes_deep($_POST['userPassword']));
        i_amir_net_ep_payment_payment_update(
            array("title" => stripslashes_deep($_POST['title']),"data" => json_encode($data), "currency" => stripslashes_deep($_POST['currency'])),
            "Mellat"
        );
        $payment = i_amir_net_ep_payment_payment_select("Mellat");
        i_amir_net_ep_payment_payment_forms_update($payment->id,$_POST['ep_forms']);
    }
    $payment = i_amir_net_ep_payment_payment_select("Mellat");
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

        <form method="post" action="<?php echo esc_html(admin_url('admin.php?page=i_amir_net_ep_mellat')); ?>">
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
                        <label for="terminalId">شماره ترمینال</label>
                    </th>
                    <td>
                        <input name="terminalId" type="text" id="terminalId" value="<?php echo $data->terminalId; ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="userName">نام کاربری</label>
                    </th>
                    <td>
                        <input name="userName" type="text" id="userName" value="<?php echo $data->userName; ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="userPassword">رمز عبور</label>
                    </th>
                    <td>
                        <input name="userPassword" type="text" id="userPassword" value="<?php echo $data->userPassword; ?>" class="regular-text">
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
