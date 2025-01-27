<?php
/**
 * Frontend: WPForms generate icons and text
 */
function cf7ic_generate_CAPTCHA_dup()
{
    global $wpdb;

    // Create icons and choose 3 icons and one answer
    $captchas = array(
        'heart' => [
            'icon' => 'fa-heart',
            'title' => __('heart', 'contact-form-7-image-captcha')
        ],
        'house' => [
            'icon' => 'fa-home',
            'title' => __('house', 'contact-form-7-image-captcha')
        ],
        'star' => [
            'icon' => 'fa-star',
            'title' => __('star', 'contact-form-7-image-captcha')
        ],
        'car' => [
            'icon' => 'fa-car',
            'title' => __('car', 'contact-form-7-image-captcha')
        ],
        'cup' => [
            'icon' => 'fa-coffee',
            'title' => __('cup', 'contact-form-7-image-captcha')
        ],
        'flag' => [
            'icon' => 'fa-flag',
            'title' => __('flag', 'contact-form-7-image-captcha')
        ],
        'key' => [
            'icon' => 'fa-key',
            'title' => __('key', 'contact-form-7-image-captcha')
        ],
        'truck' => [
            'icon' => 'fa-truck',
            'title' => __('truck', 'contact-form-7-image-captcha')
        ],
        'tree' => [
            'icon' => 'fa-tree',
            'title' => __('tree', 'contact-form-7-image-captcha')
        ],
        'plane' => [
            'icon' => 'fa-plane',
            'title' => __('plane', 'contact-form-7-image-captcha')
        ]
    );

    $choice = array_rand($captchas, 3);

    shuffle($choice);
    foreach ($choice as $key) {
        $choices[$key] = $captchas[$key];
    }

    // Pick a number between 0-2 and use it to determine which array item will be used as the answer
    $human = rand(0, (count($choices) - 1));
    $kc_human = cf7ic_random_sring_generator(20);

    // START of captcha block
    $inner = '<span class="wpcf7-form-control-wrap cf7ic-wpf kc_captcha" data-name="kc_captcha"><span class="wpcf7-form-control wpcf7-cf7ic">';
    $inner .= '<span class="captcha-image cf7ic-style1" style="border:none;">';
    $inner .= '<span class="cf7ic_instructions">';
    $image = '<span>' . $choices[$choice[$human]]['title'] . '</span>';
    $inner .= sprintf(esc_html__('Please prove you are human by selecting the', 'contact-form-7-image-captcha') . ' %s:', $image);
    $inner .= '<span class="cf7ic-icon-wrapper">';

    $i = -1;
    foreach ($choices as $title => $iconSet) {
        $i++;
        $rand_string = cf7ic_random_sring_generator(20);
        if ($i == $human) {
            $value = hash('sha256', NONCE_KEY . $kc_human);
            $secrets[$title] = hash('sha256', NONCE_KEY . $kc_human);
        } else {
            $value = hash('sha256', NONCE_KEY . $rand_string);
            $secrets[$title] = hash('sha256', NONCE_KEY . $rand_string);
        }

        $icon = '<i class="fa ' . $iconSet['icon'] . '"></i>';
        $inner .= '<label><input aria-label="' . ($i + 1) . '" type="radio" name="kc_captcha" value="' . $value . '" />' . $icon . '</label>';
    }

    $inner .= '<span aria-hidden="true" class="ai1ic-fields">';
    $inner .= '<input type="hidden" name="cf7ic_exists" value="true"/>';
    $inner .= '<input type="hidden" name="kc_key" value="' . $kc_human . '">';
    $inner .= '<input type="text" name="kc_honeypot" tabindex="-1" value="">';
    $inner .= '</span></span></span></span></span></span>';
    // END of captcha block

    // Save key-secrets pair to database
    $wpdb->insert(
        ai1ic_table_name_dup(),
        array(
            'ai1ic_key' => $kc_human,
            'ai1ic_secrets' => json_encode($secrets),
            'ai1ic_time' => time(),
        )
    );

    return $inner;
    wp_die();
}

/**
 * Random string generator function
 */
function cf7ic_random_sring_generator($n)
{
    $generated_string = ""; // Variable which store final string
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"; // Create a string with the help of small letters, capital letters and digits.
    $len = strlen($domain); // Find the lenght of created string

    for ($i = 0; $i < $n; $i++) { // Loop to create random string
        $index = rand(0, $len - 1); // Generate a random index to pick characters 
        $generated_string = $generated_string . $domain[$index]; // Concatenating the character in resultant string 
    }
    return $generated_string; // Return the random generated string
}