<?php
/**
 * Backend: add [cf7ic-demo] shortcode above submit button on WPForms forms
 */
function ai1ic_add_to_wpforms_dup()
{
    echo apply_shortcodes('[cf7ic-demo]');
}

$wpforms_status = get_option('wpforms_status');
if ($wpforms_status == 'on') {
    add_action('wpforms_display_submit_before', 'ai1ic_add_to_wpforms_dup', 30);
}

/**
 * Backend: WPForms image CAPTCHA validation
 * @link https://wpforms.com/developers/how-to-add-coupon-code-field-validation-on-your-forms/
 */
function ai1ic_wpforms_validation_dup($entry, $form_data)
{
    global $wpdb;

    $wpdb->query('DELETE FROM ' . ai1ic_table_name_dup() . ' WHERE ai1ic_time < ' . (time() - 86400)); // Clean DB by removing records over a day old

    $kc_val1 = isset($_POST['kc_captcha']) ? trim($_POST['kc_captcha']) : ''; // Get selected icon value
    $kc_val2 = isset($_POST['kc_honeypot']) ? trim($_POST['kc_honeypot']) : ''; // Get honeypot value
    $kc_key = isset($_POST['kc_key']) ? trim($_POST['kc_key']) : ''; // Get original key value

    if (!ai1ic_get_database_entry_dup($kc_key)) { // If record doesn't exist, form failed 
        wpforms()->process->errors[$form_data['id']]['footer'] = esc_html__('The captcha was incorrect. Please reload the page.', 'contact-form-7-image-captcha');
    } else if (!empty($kc_val1) && $kc_val1 != hash('sha256', NONCE_KEY . $kc_key)) {
        wpforms()->process->errors[$form_data['id']]['footer'] = esc_html__('Please select the correct icon.', 'contact-form-7-image-captcha');
    } else if (empty($kc_val1)) {
        wpforms()->process->errors[$form_data['id']]['footer'] = esc_html__('Please select an icon.', 'contact-form-7-image-captcha');
    } else if (!empty($kc_val2)) {
        wpforms()->process->errors[$form_data['id']]['footer'] = esc_html__('This message has been marked as spam.', 'contact-form-7-image-captcha');
    } else { // Delete used key from database
        $wpdb->delete(ai1ic_table_name_dup(), array('ai1ic_key' => $kc_key));
    }    
}

function cf7ic_wpforms_check_enabled()
{
    $wpforms_status = get_option('wpforms_status');

    if ($wpforms_status == 'on') {
        add_action('wpforms_process_before', 'ai1ic_wpforms_validation_dup', 10, 2);
    }
}
add_action('init', 'cf7ic_wpforms_check_enabled');

/**
 * Frontend: WPForms [cf7ic-demo] shortcode
 */
add_shortcode('cf7ic-demo', 'call_cf7ic_pro_dup');
function call_cf7ic_pro_dup($tag)
{
    wp_enqueue_style('cf7ic_style'); // Enqueue CSS
    wp_enqueue_style('cf7ic_fontawesome_style'); // Enqueue CSS
    wp_enqueue_script('cf7ic_script'); // Enqueue script

    return cf7ic_generate_CAPTCHA_dup();
}

/**
 * Backend: Helper function to return table name
 */
function ai1ic_table_name_dup()
{
    global $wpdb;
    return $wpdb->prefix . 'ai1ic';
}

/**
 * Backend: Helper function to return table entry
 */
function ai1ic_get_database_entry_dup($key = '')
{
    global $wpdb;
    $record = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . ai1ic_table_name_dup() . ' WHERE ai1ic_key="%s"', array($key)), ARRAY_A);
    if (empty($record['ai1ic_key'])) {
        return false;
    }
    $record['ai1ic_secrets'] = json_decode($record['ai1ic_secrets'], true);
    return $record;
}

/**
 * Backend: Create table to save hash with icon title
 */
add_action('admin_init', 'ai1ic_install_dup');
function ai1ic_install_dup()
{
    global $wpdb;

    $sql = 'CREATE TABLE IF NOT EXISTS ' . ai1ic_table_name_dup() . ' (
        ai1ic_key VARCHAR(190) NOT NULL,
        ai1ic_secrets TEXT NOT NULL,
        ai1ic_time INT(10) UNSIGNED NOT NULL,
        PRIMARY KEY  (ai1ic_key),
        KEY ai1ic_time (ai1ic_time)
    )
    ' . $wpdb->get_charset_collate();

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    
    // Test: see if this will only create table if not exist https://developer.wordpress.org/reference/functions/maybe_create_table/
    maybe_create_table(ai1ic_table_name_dup(), $sql);
}