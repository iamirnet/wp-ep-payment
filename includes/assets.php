<?php
$dir = __DIR__.'/../assets/';

function i_amir_net_ep_payment_assets()
{
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_enqueue_style('bitcoder-ir-style', plugin_dir_url(__DIR__) . 'assets/css/style.css', array(), 1.0, 'all');
    wp_enqueue_script('bitcoder-ir-script', plugin_dir_url(__DIR__) . 'assets/js/script.js', array('jquery'), time(), 'all');;

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'i_amir_net_ep_payment_assets');

function epPaymentRequest() {
    ?>
    <script type="text/javascript">
        var epPaymentRequest = <?php echo json_encode(array('get' => $_GET, 'post' => $_POST)); ?>;
    </script>
    <?php
}
add_action('wp_footer', 'epPaymentRequest');


