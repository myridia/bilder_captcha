<?php
/**
 * Plugin Name:  WP Image CAPTCHA
 * Plugin URI:   https://wpimagecaptcha.com/
 * Description:  Adds an image CAPTCHA to your "Contact Form 7" and "WPForms" forms.
 * Version:      3.3.22
 * Author:       WP Image CAPTCHA
 * Author URI:   https://wpimagecaptcha.com/
 * License:      GNU General Public License v2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  contact-form-7-image-captcha
 * Domain Path:  /languages
 */

/**
 * Add "Go Pro" and "Settings" action link to plugins table
 */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'cf7ic_plugin_action_links' );
function cf7ic_plugin_action_links( $links ) {
    return array_merge(
        array(
            'settings-free' => '<a href="admin.php?page=cf7ic_settings_menu">' . 'Settings' . '</a>',
            'go-pro' => '<a href="https://wpimagecaptcha.com/downloads/pro-plugin/">' . 'Go Pro' . '</a>'
        ),
        $links
    );
}

/**
 * Add WP Admin menu
 */
add_action('admin_menu', 'cf7ic_create_menu');
function cf7ic_create_menu() {
	add_menu_page('WP Image CAPTCHA', 'WP Image CAPTCHA', 'administrator', 'cf7ic_settings_menu', 'cf7ic_settings_cb', 'dashicons-shield-alt');
}

/**
 * Settings page within WP Admin menu
 */
function cf7ic_settings_cb(){ ?>
    <?php
    wp_enqueue_style( 'cf7ic_admin_style' );

    $wpforms_status = get_option('wpforms_status');
    ?>

	<div class="wrap">
        <h2 class="cf7ic-main-heading">WP Image CAPTCHA Settings</h2>

        <form action="options.php" name="ai1ic-form" id="ai1ic-form" method="post">
            <?php settings_fields( 'cf7ic_settings' ); ?>
            <?php do_settings_sections( 'cf7ic_settings' ); ?>

            <div class="cf7ic-main-wrapper">
                <div class="cf7ic-wrapper">
                    <h3><img class="cf7ic-form-icons" src="<?php echo plugin_dir_url(__FILE__); ?>assets/icon-cf7.svg" alt="Contact Form 7 icon"> Contact Form 7</h3>
                    <p>Add this shortcode to the form editor where the CAPTCHA should appear: <span class="cf7ic-highlight">[cf7ic]</span></p>
                    <p>Hide the CAPTCHA until a user interacts with the form, by adding "toggle": <span class="cf7ic-highlight">[cf7ic "toggle"]</span></p>
                    <img class="cf7ic-example" src="<?php echo plugin_dir_url(__FILE__); ?>assets/example-cf7.png" alt="Example Contact Form 7">
                    <div class="cf7ic-separator"></div>
                    
                    <h3><img class="cf7ic-form-icons" src="<?php echo plugin_dir_url(__FILE__); ?>assets/icon-wpf.svg" alt="WPForms icon"> WPForms</h3>
                    <table>
                        <tr>
                            <td>
                            <label id="wpforms_status">
                                <input type="checkbox" name="wpforms_status" <?php checked($wpforms_status, 'on'); ?> value="on">Enable CAPTCHA for WPForms</label>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <br>
                                <input type="submit" class="button-primary" value="Update" />
                            </td>
                        </tr>
                    </table>
                    <div class="cf7ic-separator"></div>

                    <h3><img class="cf7ic-form-icons" src="<?php echo plugin_dir_url(__FILE__); ?>assets/star.svg" alt="Review icon"> Review & FAQ</h3>
                    <p>Please share some love and leave a positive review if you like the plugin: <a target="_blank" class="" href="https://wordpress.org/support/plugin/contact-form-7-image-captcha/reviews/#new-post">Rate NOW</a></p>
                    <p>Facing language / translation issues? <a target="_blank" class="" href="https://wordpress.org/plugins/contact-form-7-image-captcha/#language%20issues%3F">See FAQ</a></p>
                </div>

                <div class="cf7ic-wrapper">
                    <h3><img class="cf7ic-form-icons" src="<?php echo plugin_dir_url(__FILE__); ?>assets/icon-cf7ic.svg" alt="Contact Form 7 icon"> Upgrade to PRO</h3>

                    <p class="cf7ic-pro-p"><?php echo '&#9733; Choose your own messages!'; ?></p>
                    <img class="cf7ic-example" src="<?php echo plugin_dir_url(__FILE__); ?>assets/pro-language.png" alt="Example Pro Language">

                    <p class="cf7ic-pro-p">&#9733; Get full customization for icons and messages!</p>
                    <img class="cf7ic-example" src="<?php echo plugin_dir_url(__FILE__); ?>assets/pro-new-colors.png" alt="Example Pro New Colors">
                    <img class="cf7ic-example" src="<?php echo plugin_dir_url(__FILE__); ?>assets/pro-color.png" alt="Example Pro Color">
                    <p class="cf7ic-pro-p">&#9733; Stronger spam protection with new icons appearing after a failed selection!</p>
                    <img class="cf7ic-example cf7ic-example-spam" src="<?php echo plugin_dir_url(__FILE__); ?>assets/pro-spam.svg" alt="Example Pro Spam Protection">
                    <p class="cf7ic-pro-p">&#9733; And many more features!</p>
                    <a target="_blank" class="button-primary cf7ic-btn-primary" href="https://wpimagecaptcha.com/downloads/pro-plugin/">Go PRO NOW</a>
                    <div class="cf7ic-review">
                        <div><h4 class="cf7ic-review-heading">All you need & really doing the job</h4></div>
                        <div class="cf7ic-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                        <div>Easy to configure, lot of options (for the pro version). The best thing is, it really works, we had a lot of spam with another honeypot and we did not want to add any Google things again or buy the expensive Akismet, so we found this neat plugin. English and german support with very good reaction time surprised in addition.</div>
                        <div class="cf7ic-reviewer">saavikam (@saavikam)</div>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php }

add_action('admin_init', 'cf7ic_register_settings');
function cf7ic_register_settings() {
    register_setting('cf7ic_settings','wpforms_status');
    register_setting('cf7ic_settings','cf7ic-ajax');
}

/**
 * Redirect after plugin activation
 * See: https://developer.wordpress.org/reference/functions/register_activation_hook/#process-flow
 */
register_activation_hook(__FILE__, 'cf7ic_plugin_activate');
function cf7ic_plugin_activate() {
    add_option('cf7ic_plugin_do_activation_redirect', true);
}

add_action('admin_init', 'cf7ic_plugin_redirect');
function cf7ic_plugin_redirect() {
    if (get_option('cf7ic_plugin_do_activation_redirect', false)) {
        cf7ic_set_timestamp();
        
        delete_option('cf7ic_plugin_do_activation_redirect');

        if (!isset($_GET['activate-multi'])) {
            wp_redirect("admin.php?page=cf7ic_settings_menu");
        }
    }
}

/**
 * Load textdomains
 */
add_action('init', 'cf7ic_load_textdomain');
function cf7ic_load_textdomain() {
    load_plugin_textdomain( 'contact-form-7-image-captcha', false, dirname( plugin_basename(__FILE__) ) . '/languages' );
}

/**
 * Register CSS and JS on initialization
 */
add_action('init', 'cf7ic_register_style');
function cf7ic_register_style() {
    // Frontend
    wp_register_style( 'cf7ic_style', plugins_url('/css/cf7ic-style.css', __FILE__), false, '3.3.7', 'all'); // Used for: CF2, WPForms
    wp_register_style( 'cf7ic_fontawesome_style', plugins_url('/css/fontawesome.css', __FILE__), false, '3.3.7', 'all'); // Used for: WPForms
    wp_add_inline_script( 'cf7ic_script', 'const cf7ic_ajax_url = "' . admin_url('admin-ajax.php').'";', 'before' ); // Used for: WPForms, defines JS AJAX URL

    // Backend
    wp_register_style( 'cf7ic_admin_style', plugins_url('/css/cf7ic-admin-style.css', __FILE__), false, '3.3.7', 'all');
}

/**
 * Set timestamp within options table
 */
function cf7ic_set_timestamp() {
    $cf7ic_timestamp = get_option('cf7ic_timestamp', false);

    if (!$cf7ic_timestamp) {
        add_option('cf7ic_timestamp', time());
    }
}

// Includes
include 'includes/module-cf7.php'; // CF7
include 'includes/module-wpforms.php'; // WPForms
include 'includes/captcha-generator.php'; // Used for WPForms, generates the CAPTCHA