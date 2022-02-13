<?php
function i_amir_net_ep_payment_about()
{
    if (!current_user_can('i_amir_net_ep_payment_manage')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    echo '<div class="wrap">';
    echo "<h2>About Bitcoder</h2>";
    echo '<p>Hi, </p><a href="https://bitcoder.ir">Visit my website to see my portfolio</a>';
    echo '</div>';
}
