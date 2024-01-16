<?php
/**
 * Plugin Name: QuadFit Sale Countdown Timer
 * Description: A simple countdown timer plugin.
 * Version: 1.0
 * Author: Daynis Olman
 */

// Enqueue the styles and scripts
function daynis_timer_assets() {
    wp_enqueue_style('daynis-timer-css', plugins_url('/css/daynis-timer.css', __FILE__));
    wp_enqueue_script('daynis-timer-js', plugins_url('/js/daynis-timer.js', __FILE__), array(), false, true);
}
add_action('wp_enqueue_scripts', 'daynis_timer_assets');

// Shortcode for the countdown timer
function daynis_timer_shortcode() {
    ob_start();
    ?>
    <div class="daynis-timer-container">
        <h1 id="headline">Offer ends in</h1>
        <div id="countdown">
            <ul>
                <li><span id="days"></span>days</li>
                <li><span id="hours"></span>Hours</li>
                <li><span id="minutes"></span>Minutes</li>
                <li><span id="seconds"></span>Seconds</li>
            </ul>
        </div>
        <div id="content" class="emoji">
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('daynis-timer', 'daynis_timer_shortcode');
