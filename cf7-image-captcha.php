<?php
/**
 * Plugin Name:  Image Captcha
 * Plugin URI:   https://wordpress.org/plugins/contact-form-7-image-captcha/
 * Description:  Add a simple image captcha and Honeypot to contact form 7
 * Version:      3.3.5
 * Author:       veto
 * Author URI:   https://w3.calantas.org/imagecaptcha
 * License:      GNU General Public License v2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  imagecaptcha
 * Domain Path:  /languages
 */




/**
 * Add "Go Pro" action link to plugins table
 */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'cf7ic_plugin_action_links' );
function cf7ic_plugin_action_links( $links ) {
    return array_merge(
        array(
            'go-pro' => '<a href="https://kccomputing.net/downloads/contact-form-7-image-captcha-pro/">' . __( 'Go Pro', 'contact-form-7-image-captcha' ) . '</a>'
        ),
        $links
    );
}


/**
 * Load Textdomains
 */
add_action('init', 'cf7ic_load_textdomain');
function cf7ic_load_textdomain() {
    load_plugin_textdomain( 'contact-form-7-image-captcha', false, dirname( plugin_basename(__FILE__) ) . '/languages' );
}



/**
 * Register/Enqueue CSS on initialization
 */
add_action('init', 'cf7ic_register_style');
function cf7ic_register_style() {
    wp_register_style( 'cf7ic_style', plugins_url('/style.css', __FILE__), false, '3.2.5', 'all');
}


/**
 * Add custom shortcode to Contact Form 7
 */
add_action( 'wpcf7_init', 'add_shortcode_cf7ic' );
function add_shortcode_cf7ic() {
    wpcf7_add_form_tag( 'cf7ic', 'call_cf7ic');
}


/**
 * cf7ic shortcode
 */
function call_cf7ic( $tag ) {  
    $tag = new WPCF7_FormTag( $tag );
    $toggle = '';
    if($tag['raw_values']) {
        $toggle = $tag['raw_values'][0];
    }

    wp_enqueue_style( 'cf7ic_style' ); // enqueue css

    // Create an array to hold the image library
    $captchas = array(
        __( 'das Herz', 'contact-form-7-image-captcha') => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M415 24c-53 0-103 42-127 65-24-23-74-65-127-65C70 24 16 77 16 166c0 72 67 133 69 135l187 181c9 8 23 8 32 0l187-180c2-3 69-64 69-136 0-89-54-142-145-142z"/></svg>',
        __( 'das Haus', 'contact-form-7-image-captcha') => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M488 313v143c0 13-11 24-24 24H348c-7 0-12-5-12-12V356c0-7-5-12-12-12h-72c-7 0-12 5-12 12v112c0 7-5 12-12 12H112c-13 0-24-11-24-24V313c0-4 2-7 4-10l188-154c5-4 11-4 16 0l188 154c2 3 4 6 4 10zm84-61l-84-69V44c0-6-5-12-12-12h-56c-7 0-12 6-12 12v73l-89-74a48 48 0 00-61 0L4 252c-5 4-5 12-1 17l25 31c5 5 12 5 17 1l235-193c5-4 11-4 16 0l235 193c5 5 13 4 17-1l25-31c4-6 4-13-1-17z"/></svg>',
        __( 'den Stern', 'contact-form-7-image-captcha')  => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M259 18l-65 132-146 22c-26 3-37 36-18 54l106 103-25 146c-5 26 23 46 46 33l131-68 131 68c23 13 51-7 46-33l-25-146 106-103c19-18 8-51-18-54l-146-22-65-132a32 32 0 00-58 0z"/></svg>',
        __( 'das Auto', 'contact-form-7-image-captcha')   => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M500 168h-55l-8-21a127 127 0 00-120-83H195a127 127 0 00-120 83l-8 21H12c-8 0-14 8-11 16l8 24a12 12 0 0011 8h29a64 64 0 00-33 56v48c0 16 6 31 16 42v62c0 13 11 24 24 24h48c13 0 24-11 24-24v-40h256v40c0 13 11 24 24 24h48c13 0 24-11 24-24v-62c10-11 16-26 16-42v-48c0-24-13-45-33-56h29a12 12 0 0011-8l8-24c3-8-3-16-11-16zm-365 2c9-25 33-42 60-42h122c27 0 51 17 60 42l15 38H120l15-38zM88 328a32 32 0 010-64c18 0 48 30 48 48s-30 16-48 16zm336 0c-18 0-48 2-48-16s30-48 48-48 32 14 32 32-14 32-32 32z"/></svg>',
        __( 'die Tasse', 'contact-form-7-image-captcha')   => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M192 384h192c53 0 96-43 96-96h32a128 128 0 000-256H120c-13 0-24 11-24 24v232c0 53 43 96 96 96zM512 96a64 64 0 010 128h-32V96h32zm48 384H48c-47 0-61-64-36-64h584c25 0 11 64-36 64z"/></svg>',
        __( 'die Flagge', 'contact-form-7-image-captcha')  => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M350 99c-54 0-98-35-166-35-25 0-47 4-68 12a56 56 0 004-24C118 24 95 1 66 0a56 56 0 00-34 102v386c0 13 11 24 24 24h16c13 0 24-11 24-24v-94c28-12 64-23 114-23 54 0 98 35 166 35 48 0 86-16 122-41 9-6 14-15 14-26V96c0-23-24-39-45-29-35 16-77 32-117 32z"/></svg>',
        __( 'den Schlüssel', 'contact-form-7-image-captcha')   => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M512 176a176 176 0 01-209 173l-24 27a24 24 0 01-18 8h-37v40c0 13-11 24-24 24h-40v40c0 13-11 24-24 24H24c-13 0-24-11-24-24v-78c0-6 3-13 7-17l162-162a176 176 0 11343-55zm-176-48a48 48 0 1096 0 48 48 0 00-96 0z"/></svg>',
        __( 'den LKW', 'contact-form-7-image-captcha') => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M624 352h-16V244c0-13-5-25-14-34L494 110c-9-9-21-14-34-14h-44V48c0-26-21-48-48-48H48C22 0 0 22 0 48v320c0 27 22 48 48 48h16a96 96 0 00192 0h128a96 96 0 00192 0h48c9 0 16-7 16-16v-32c0-9-7-16-16-16zM160 464a48 48 0 110-96 48 48 0 010 96zm320 0a48 48 0 110-96 48 48 0 010 96zm80-208H416V144h44l100 100v12z"/></svg>',
        __( 'den Baum', 'contact-form-7-image-captcha')  => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M377 375l-83-87h34c21 0 32-25 17-40l-82-88h33c21 0 32-25 18-40L210 8c-10-11-26-11-36 0L70 120c-14 15-3 40 18 40h33l-82 88c-15 15-4 40 17 40h34L7 375c-15 16-4 41 17 41h120c0 33-11 49-34 68-12 9-5 28 10 28h144c15 0 22-19 10-28-20-16-34-32-34-68h120c21 0 32-25 17-41z"/></svg>',
        __( 'das Flugzeug', 'contact-form-7-image-captcha') => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M472 200H360L256 6a12 12 0 00-10-6h-58c-8 0-14 7-12 15l34 185H100l-35-58a12 12 0 00-10-6H12c-8 0-13 7-12 14l21 106L0 362c-1 7 4 14 12 14h43c4 0 8-2 10-6l35-58h110l-34 185c-2 8 4 15 12 15h58a12 12 0 0010-6l104-194h112c57 0 104-25 104-56s-47-56-104-56z"/></svg>',
    );



    $choice = array_rand( $captchas, 3);
    foreach($choice as $key) {
        $choices[$key] = $captchas[$key];
    }

    // Pick a number between 0-2 and use it to determine which array item will be used as the answer
    $human = rand(0,2);

    if($toggle == 'toggle') {
        $style = 'style="display: none;"';
        add_action('wp_footer', 'cf7ic_toggle');
        $toggle_class = ' cf7ic-toggle';
    } else { 
        $style = '';
        $toggle_class = '';
    }

    $output = ' 
    <span class="captcha-image" '.$style.'>
        <span class="cf7ic_instructions">';
            $output .= __('Bitte beweisen Sie, dass Sie ein Mensch sind und wählen Sie ', 'contact-form-7-image-captcha');
            $output .= '<span> '.$choice[$human].'</span>';
            $output .= __('.', 'contact-form-7-image-captcha').'</span>';
        $i = -1;
        foreach($choices as $title => $image) {
            $i++;
            if($i == $human) { $value = "kc_human"; } else { $value = "bot"; };
            $output .= '<label><input type="radio" name="kc_captcha" value="'. $value .'" />'. $image .'</label>';
        }
    $output .= '
    </span>
    <span style="display:none">
        <input type="text" name="kc_honeypot">
    </span>';

    return '<span class="wpcf7-form-control-wrap kc_captcha'.$toggle_class.'" data-name="kc_captcha"><span class="wpcf7-form-control wpcf7-radio">'.$output.'</span></span>';
}


/**
 * Custom validator
 */
function cf7ic_check_if_spam( $result, $tag ) {

    // Because the validator is triggered on every submission, look through tags to see if cf7ic is being used
    $key = array_search('cf7ic', array_column($tag, 'type'));

    if(!empty($key) ) { // If cf7ic is being used on the form, run validation
        $tag = new WPCF7_FormTag( $tag );
        $tag->name = "kc_captcha";

        $kc_val1 = isset( $_POST['kc_captcha'] ) ? trim( $_POST['kc_captcha'] ) : '';   // Get selected icon value
        $kc_val2 = isset( $_POST['kc_honeypot'] ) ? trim( $_POST['kc_honeypot'] ) : ''; // Get honeypot value

        if(!empty($kc_val1) && $kc_val1 != 'kc_human' ) {
            $tag->name = "kc_captcha";
            $error = __('Bitte beweisen Sie, dass Sie ein Mensch sind und wählen Sie', 'contact-form-7-image-captcha');
            $result->invalidate( $tag, $error );
        }
        if(empty($kc_val1) ) {
            $tag->name = "kc_captcha";
            $error = __('Bitte wählen Sie das richtige Symbol.', 'contact-form-7-image-captcha');
            $result->invalidate( $tag, $error );
        }
        if(!empty($kc_val2) ) {
            $tag->name = "kc_captcha";
            $result->invalidate( $tag, wpcf7_get_message( 'spam' ) );
        }
    }
    return $result;
}
add_filter('wpcf7_validate','cf7ic_check_if_spam', 99, 2); // If "Contact Form 7 – Conditional Fields" plugin is installed and active



// Add Contact Form Tag Generator Button
add_action( 'wpcf7_admin_init', 'cf7ic_add_tag_generator', 55 );

function cf7ic_add_tag_generator() {
    $tag_generator = WPCF7_TagGenerator::get_instance();
    $tag_generator->add( 'cf7ic', __( 'Image Captcha', 'contact-form-7-image-captcha' ),
        'cf7ic_tag_generator', array( 'nameless' => 1 ) );
}

function cf7ic_tag_generator( $contact_form, $args = '' ) {
    $args = wp_parse_args( $args, array() ); ?>
    <div class="control-box">
        <fieldset>
            <legend>Coming soon to <a href="https://kccomputing.net/downloads/contact-form-7-image-captcha-pro/" target="_blank">Contact Form 7 Image Captcha Pro</a>, edit the styling directly from this box.</legend>
        </fieldset>
    </div>
    <div class="insert-box">
        <input type="text" name="cf7ic" class="tag code" readonly="readonly" onfocus="this.select()" />
        <div class="submitbox">
            <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
        </div>
    </div>
<?php
}


function cf7ic_toggle(){
    echo '<script type="text/javascript">
        document.addEventListener("focusin", cf7ic_display);

        function cf7ic_display(event){
            if(event.target.form.querySelector(".cf7ic-toggle")) {
                event.target.form.querySelector(".captcha-image").style.display = "block";
            }
        }

        document.addEventListener( "wpcf7mailsent", function( event ) {
            event.target.querySelector(".captcha-image").style.display = "none";
            document.activeElement.blur();
        }, false );
    </script>';
};