<?php

/**
 * Backend: add custom shortcode to CF7
 */
add_action('wpcf7_init', 'add_shortcode_cf7ic');
function add_shortcode_cf7ic()
{
    wp_enqueue_style('cf7ic_style'); // Enqueue CSS

    wpcf7_add_form_tag('cf7ic', 'call_cf7ic');
}

/**
 * Frontend: CF7 [cf7ic] shortcode
 */
function call_cf7ic($tag)
{
    $tag = new WPCF7_FormTag($tag);
    $toggle = '';
    if ($tag['raw_values']) {
        $toggle = $tag['raw_values'][0];
    }

    $language_map = [
        'heart' => __('heart', 'contact-form-7-image-captcha'),
        'house' => __('house', 'contact-form-7-image-captcha'),
        'star' => __('star', 'contact-form-7-image-captcha'),
        'car' => __('car', 'contact-form-7-image-captcha'),
        'cup' => __('cup', 'contact-form-7-image-captcha'),
        'flag' => __('flag', 'contact-form-7-image-captcha'),
        'key' => __('key', 'contact-form-7-image-captcha'),
        'truck' => __('truck', 'contact-form-7-image-captcha'),
        'tree' => __('tree', 'contact-form-7-image-captcha'),
        'plane' => __('plane', 'contact-form-7-image-captcha'),
        'text' => __('Please prove you are human by selecting the', 'contact-form-7-image-captcha'),
        'dot' => __('.', 'contact-form-7-image-captcha')
    ];

    // Create an array to hold the image library
    $captchas = array(
        $language_map['heart'] => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M415 24c-53 0-103 42-127 65-24-23-74-65-127-65C70 24 16 77 16 166c0 72 67 133 69 135l187 181c9 8 23 8 32 0l187-180c2-3 69-64 69-136 0-89-54-142-145-142z"/></svg>',
        $language_map['house'] => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M488 313v143c0 13-11 24-24 24H348c-7 0-12-5-12-12V356c0-7-5-12-12-12h-72c-7 0-12 5-12 12v112c0 7-5 12-12 12H112c-13 0-24-11-24-24V313c0-4 2-7 4-10l188-154c5-4 11-4 16 0l188 154c2 3 4 6 4 10zm84-61l-84-69V44c0-6-5-12-12-12h-56c-7 0-12 6-12 12v73l-89-74a48 48 0 00-61 0L4 252c-5 4-5 12-1 17l25 31c5 5 12 5 17 1l235-193c5-4 11-4 16 0l235 193c5 5 13 4 17-1l25-31c4-6 4-13-1-17z"/></svg>',
        $language_map['star'] => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M259 18l-65 132-146 22c-26 3-37 36-18 54l106 103-25 146c-5 26 23 46 46 33l131-68 131 68c23 13 51-7 46-33l-25-146 106-103c19-18 8-51-18-54l-146-22-65-132a32 32 0 00-58 0z"/></svg>',
        $language_map['car'] => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M500 168h-55l-8-21a127 127 0 00-120-83H195a127 127 0 00-120 83l-8 21H12c-8 0-14 8-11 16l8 24a12 12 0 0011 8h29a64 64 0 00-33 56v48c0 16 6 31 16 42v62c0 13 11 24 24 24h48c13 0 24-11 24-24v-40h256v40c0 13 11 24 24 24h48c13 0 24-11 24-24v-62c10-11 16-26 16-42v-48c0-24-13-45-33-56h29a12 12 0 0011-8l8-24c3-8-3-16-11-16zm-365 2c9-25 33-42 60-42h122c27 0 51 17 60 42l15 38H120l15-38zM88 328a32 32 0 010-64c18 0 48 30 48 48s-30 16-48 16zm336 0c-18 0-48 2-48-16s30-48 48-48 32 14 32 32-14 32-32 32z"/></svg>',
        $language_map['cup'] => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M192 384h192c53 0 96-43 96-96h32a128 128 0 000-256H120c-13 0-24 11-24 24v232c0 53 43 96 96 96zM512 96a64 64 0 010 128h-32V96h32zm48 384H48c-47 0-61-64-36-64h584c25 0 11 64-36 64z"/></svg>',
        $language_map['flag'] => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M350 99c-54 0-98-35-166-35-25 0-47 4-68 12a56 56 0 004-24C118 24 95 1 66 0a56 56 0 00-34 102v386c0 13 11 24 24 24h16c13 0 24-11 24-24v-94c28-12 64-23 114-23 54 0 98 35 166 35 48 0 86-16 122-41 9-6 14-15 14-26V96c0-23-24-39-45-29-35 16-77 32-117 32z"/></svg>',
        $language_map['key'] => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M512 176a176 176 0 01-209 173l-24 27a24 24 0 01-18 8h-37v40c0 13-11 24-24 24h-40v40c0 13-11 24-24 24H24c-13 0-24-11-24-24v-78c0-6 3-13 7-17l162-162a176 176 0 11343-55zm-176-48a48 48 0 1096 0 48 48 0 00-96 0z"/></svg>',
        $language_map['truck'] => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M624 352h-16V244c0-13-5-25-14-34L494 110c-9-9-21-14-34-14h-44V48c0-26-21-48-48-48H48C22 0 0 22 0 48v320c0 27 22 48 48 48h16a96 96 0 00192 0h128a96 96 0 00192 0h48c9 0 16-7 16-16v-32c0-9-7-16-16-16zM160 464a48 48 0 110-96 48 48 0 010 96zm320 0a48 48 0 110-96 48 48 0 010 96zm80-208H416V144h44l100 100v12z"/></svg>',
        $language_map['tree'] => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M377 375l-83-87h34c21 0 32-25 17-40l-82-88h33c21 0 32-25 18-40L210 8c-10-11-26-11-36 0L70 120c-14 15-3 40 18 40h33l-82 88c-15 15-4 40 17 40h34L7 375c-15 16-4 41 17 41h120c0 33-11 49-34 68-12 9-5 28 10 28h144c15 0 22-19 10-28-20-16-34-32-34-68h120c21 0 32-25 17-41z"/></svg>',
        $language_map['plane'] => '<svg width="50" height="50" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M472 200H360L256 6a12 12 0 00-10-6h-58c-8 0-14 7-12 15l34 185H100l-35-58a12 12 0 00-10-6H12c-8 0-13 7-12 14l21 106L0 362c-1 7 4 14 12 14h43c4 0 8-2 10-6l35-58h110l-34 185c-2 8 4 15 12 15h58a12 12 0 0010-6l104-194h112c57 0 104-25 104-56s-47-56-104-56z"/></svg>',
    );

    $choice = array_rand($captchas, 3);
    foreach ($choice as $key) {
        $choices[$key] = $captchas[$key];
    }

    // Pick a number between 0-2 and use it to determine which array item will be used as the answer
    $human = rand(0, 2);

    if ($toggle == 'toggle') {
        $style = 'style="display: none;"';
        add_action('wp_footer', 'cf7ic_toggle');
        $toggle_class = ' cf7ic-toggle';
    } else {
        $style = '';
        $toggle_class = '';
    }

    $output = '<span class="captcha-image" ' . $style . '><span class="cf7ic_instructions">';
    $output .= $language_map['text'];
    $output .= '<span> ' . $choice[$human] . '</span>';
    $output .= $language_map['dot'] . '</span>';

    $i = -1;
    foreach ($choices as $title => $image) {
        $i++;
        if ($i == $human) {
            $value = "kc_human";
        } else {
            $value = "bot";
        };
        $output .= '<label><input aria-label="' . ($i + 1) . '" type="radio" name="kc_captcha" value="' . $value . '" />' . $image . '</label>';
    }

    $output .= '
    </span>
    <span style="display:none">
        <input type="text" name="kc_honeypot">
    </span>';

    return '<span class="wpcf7-form-control-wrap kc_captcha' . $toggle_class . '" data-name="kc_captcha"><span class="wpcf7-form-control wpcf7-radio">' . $output . '</span></span>';
}

function cf7ic_toggle()
{
    echo '<script type="text/javascript">
        document.addEventListener("focusin", cf7ic_display);

        function cf7ic_display(event){
            if(event.target.form && event.target.form.querySelector(".cf7ic-toggle")) {
                event.target.form.querySelector(".captcha-image").removeAttribute("style");
            }
        }

        document.addEventListener( "wpcf7mailsent", function( event ) {
            if (event.target.querySelector(".captcha-image")) {
                event.target.querySelector(".captcha-image").style.display = "none";
                document.activeElement.blur();
            }
        }, false );
    </script>';
}

/**
 * Backend: CF7 custom validator
 * If "Conditional Fields for Contact Form 7" plugin is installed and active, it uses filter priority 2 and 30
 * Therefore we use filter priority 99
 * @link https://wordpress.org/plugins/cf7-conditional-fields/
 */
add_filter('wpcf7_validate', 'cf7ic_check_if_spam', 99, 2); // 
function cf7ic_check_if_spam($result, $tag)
{

    // Because the validator is triggered on every submission, look through tags to see if cf7ic is being used
    $key = array_search('cf7ic', array_column($tag, 'type'));

    if (!empty($key)) { // If cf7ic is being used on the form, run validation
        $tag = new WPCF7_FormTag($tag);
        $tag->name = "kc_captcha";

        $kc_val1 = isset($_POST['kc_captcha']) ? trim($_POST['kc_captcha']) : ''; // Get selected icon value
        $kc_val2 = isset($_POST['kc_honeypot']) ? trim($_POST['kc_honeypot']) : ''; // Get honeypot value

        if (!empty($kc_val1) && $kc_val1 != 'kc_human') {
            $tag->name = "kc_captcha";
            $error = __('Please select the correct icon.', 'contact-form-7-image-captcha');
            $result->invalidate($tag, $error);
        }
        if (empty($kc_val1)) {
            $tag->name = "kc_captcha";
            $error = __('Please select an icon.', 'contact-form-7-image-captcha');
            $result->invalidate($tag, $error);
        }
        if (!empty($kc_val2)) {
            $tag->name = "kc_captcha";
            $result->invalidate($tag, wpcf7_get_message('spam'));
        }
    }

    return $result;
}

/**
 * Backend: CF7 add 7 tag generator button (WP Admin > Contact > Contact Forms > select one > see buttons at the top)
 */
add_action('wpcf7_admin_init', 'cf7ic_add_tag_generator', 55); // A
function cf7ic_add_tag_generator()
{
    $tag_generator = WPCF7_TagGenerator::get_instance();
    $tag_generator->add(
        'cf7ic',
        'WP Image CAPTCHA',
        'cf7ic_tag_generator',
        array('version' => '2')
    );
}

function cf7ic_tag_generator($contact_form, $args = '')
{
    $args = wp_parse_args($args, array()); ?>
     <footer class="insert-box">
        <div class="flex-container">
            <input type="text" name="cf7ic" class="tag code" readonly="readonly" onfocus="this.select()" value="[cf7ic]" />
            <button type="button" class="button button-primary insert-tag"><?php echo esc_html(__('Insert Tag', 'contact-form-7')); ?></button>
        </div>
    </footer>
<?php
}
