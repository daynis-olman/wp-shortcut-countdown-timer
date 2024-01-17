<?php
/**
 * Plugin Name: Daynis Shortcode Countdown Timer
 * Description: A simple configurable WordPress countdown timer plugin.
 * Version: 1.0
 * Author: Daynis Olman
 */

// Enqueue the styles
function daynis_timer_assets() {
    wp_enqueue_style('daynis-timer-css', plugins_url('/css/daynis-timer.css', __FILE__));
    // No need to enqueue the JS file separately as it will be included directly in the shortcode
}
add_action('wp_enqueue_scripts', 'daynis_timer_assets');

// Add menu item for settings page
function daynis_timer_menu() {
    add_options_page('Daynis Countdown Timer Settings', 'Countdown Timer', 'manage_options', 'daynis-timer', 'daynis_timer_settings_page');
}
add_action('admin_menu', 'daynis_timer_menu');

// Settings page for the plugin
function daynis_timer_settings_page() {
    // Get plugin options
    $countdown_date = get_option('daynis_timer_countdown_date', '2024-01-31'); // Default value if not set
    $hide_plugin = get_option('daynis_timer_hide_plugin', '0'); // Default value if not set
    $offer_text = get_option('daynis_timer_offer_text', 'OFFER ENDS IN'); // Default value if not set
    $background_color = get_option('daynis_timer_background_color', '#c4ff0d'); // Default background color
    $text_color = get_option('daynis_timer_text_color', '#333'); // Default text color

    if (isset($_POST['submit'])) {
        // Save settings if the form is submitted
        $countdown_date = sanitize_text_field($_POST['daynis_timer_countdown_date']);
        $hide_plugin = isset($_POST['daynis_timer_hide_plugin']) ? '1' : '0';
        $offer_text = sanitize_text_field($_POST['daynis_timer_offer_text']);
        $background_color = sanitize_hex_color($_POST['daynis_timer_background_color']);
        $text_color = sanitize_hex_color($_POST['daynis_timer_text_color']);

        update_option('daynis_timer_countdown_date', $countdown_date);
        update_option('daynis_timer_hide_plugin', $hide_plugin);
        update_option('daynis_timer_offer_text', $offer_text);
        update_option('daynis_timer_background_color', $background_color);
        update_option('daynis_timer_text_color', $text_color);
    }
    ?>
    <div class="wrap">
        <h1>Daynis Timer Settings</h1>
        <form method="post" action="">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Countdown Date:</th>
                    <td><input type="date" name="daynis_timer_countdown_date" value="<?php echo esc_attr($countdown_date); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Hide Plugin:</th>
                    <td><input type="checkbox" name="daynis_timer_hide_plugin" <?php echo checked('1', $hide_plugin); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Countdown Title Text:</th>
                    <td><input type="text" name="daynis_timer_offer_text" value="<?php echo esc_attr($offer_text); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Background Color:</th>
                    <td><input type="color" name="daynis_timer_background_color" value="<?php echo esc_attr($background_color); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Text Color:</th>
                    <td><input type="color" name="daynis_timer_text_color" value="<?php echo esc_attr($text_color); ?>" /></td>
                </tr>
            </table>
            <?php submit_button('Save Settings', 'primary', 'submit'); ?>
        </form>
    </div>
    <?php
}

// Register and handle the settings
function daynis_timer_settings() {
    register_setting('daynis-timer-options', 'daynis_timer_countdown_date');
    register_setting('daynis-timer-options', 'daynis_timer_hide_plugin');
    register_setting('daynis-timer-options', 'daynis_timer_offer_text');
    register_setting('daynis-timer-options', 'daynis_timer_background_color');
    register_setting('daynis-timer-options', 'daynis_timer_text_color');
}
add_action('admin_init', 'daynis_timer_settings');

// Shortcode for the countdown timer
function daynis_timer_shortcode() {
    $countdown_date = get_option('daynis_timer_countdown_date', '2024-01-31'); // Default value if not set
    $hide_plugin = get_option('daynis_timer_hide_plugin', '0'); // Default value if not set
    $offer_text = get_option('daynis_timer_offer_text', 'OFFER ENDS IN'); // Default value if not set
    $background_color = get_option('daynis_timer_background_color', '#c4ff0d'); // Default background color
    $text_color = get_option('daynis_timer_text_color', '#333'); // Default text color

    if ($hide_plugin === '1') {
        return ''; // Hide the plugin if the checkbox is checked
    }

    ob_start();
    ?>
    <style>
        .daynis-timer-container {
            background-color: <?php echo esc_attr($background_color); ?>;
            color: <?php echo esc_attr($text_color); ?>;
        }
    </style>
    <div class="daynis-timer-container">
        <h1 id="headline"><?php echo esc_html($offer_text); ?></h1>
        <div id="countdown" data-countdown-date="<?php echo esc_attr($countdown_date); ?>">
            <ul>
                <li><span id="days"></span>days</li>
                <li><span id="hours"></span>Hours</li>
                <li><span id="minutes"></span>Minutes</li>
                <li><span id="seconds"></span>Seconds</li>
            </ul>
        </div>
        <div id="content" class="emoji">
            <!-- Emoji or message can go here -->
        </div>
    </div>
    <script>
        (function () {
            const second = 1000,
                  minute = second * 60,
                  hour = minute * 60,
                  day = hour * 24;

            const countdownDate = new Date('<?php echo $countdown_date; ?>').getTime();

            const x = setInterval(function() {    
                const now = new Date().getTime(),
                      distance = countdownDate - now;

                document.getElementById("days").innerText = Math.floor(distance / (day)),
                document.getElementById("hours").innerText = Math.floor((distance % (day)) / (hour)),
                document.getElementById("minutes").innerText = Math.floor((distance % (hour)) / (minute)),
                document.getElementById("seconds").innerText = Math.floor((distance % (minute)) / second);

                if (distance < 0) {
                    document.getElementById("headline").innerText = "The date has arrived!";
                    document.getElementById("countdown").style.display = "none";
                    document.getElementById("content").style.display = "block";
                    clearInterval(x);
                }
            }, 0)
        }());
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('daynis-timer', 'daynis_timer_shortcode');