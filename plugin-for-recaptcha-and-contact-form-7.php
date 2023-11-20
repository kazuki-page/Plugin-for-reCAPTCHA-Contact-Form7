<?php

/*
Plugin Name: Plugin for reCAPTCHA and Contact Form 7
Description: reCAPTCHAとContact Form 7用のプラグイン
Version: 1.0
Author: Kazuki Sanada
Author URI: kazuki.page
*/

if ( ! defined( 'ABSPATH' ) ) exit; /*phpファイルのURLに直接アクセスされても中身を見られないようにする*/

add_action('init', 'PluginForReCaptchaAndContactForm7::init');

class PluginForReCaptchaAndContactForm7
{
    static function init()
    {
        return new self();
    }

    function __construct()
    {
        load_recaptcha_js();
        wpcf7_file_control();
        if (is_admin() && is_user_logged_in()) {
            // メニュー追加
            add_action('admin_menu', [$this, 'set_plugin_menu']);

        }
    }
    function set_plugin_menu()
    {
        add_menu_page(
            'Plugin for reCAPTCHA and Contact Form 7',           /* ページタイトル*/
            'Plugin for reCAPTCHA and Contact Form 7',           /* メニュータイトル */
            'manage_options',         /* 権限 */
            'plugin-for-recaptcha-and-contact-form-7',    /* ページを開いたときのURL */
            [$this, 'show_about_plugin'],       /* メニューに紐づく画面を描画するcallback関数 */
            'dashicons-format-gallery', /* アイコン see: https://developer.wordpress.org/resource/dashicons/#awards */
            99                          /* 表示位置のオフセット */
        );
    }
    function show_about_plugin() {
        ?>
               <div class="wrap">
                    <h1>Plugin for reCAPTCHA and Contact Form 7</h1>
                        <h2>このプラグインの使い方（How to use）</h2>
                            <p>このプラグインは有効化するとすべての機能がオンになります。<br>機能をオフにしたい際はプラグインを無効化してください。</p>
                            <p>（Activating this plugin turns on all features.<br>If you want to turn off the function, please disable the plugin.）</>
                        <h2>機能（Features of this plugin）<h2>
                            <p>・リキャプチャのバッジを画面の手前側に表示する<br>（Display the recapture badge on the front of the screen.）</p>
                            <p>・問い合わせページ以外でreCAPTCHAを読み込ませない<br>（Prevent reCAPTCHA from loading on pages other than the inquiry page.）</p>
                            <p>・contact form 7 のファイルを必要な場合に飲み読み込む<br>（Load contact form 7 file only when needed.）</p>
                </div>
        <?php
    }
} // end of class

// リキャプチャのバッジを手前に表示するCSSを読み込む

function add_plugin_css() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'style_1', $plugin_url . 'style.css' );
}

add_action( 'wp_enqueue_scripts', 'add_plugin_css' );

// お問い合わせページを除き、「reCAPTCHA」を読み込ませない
 
function load_recaptcha_js() {
    if ( ! is_page( 'contact' ) ) {
     wp_deregister_script( 'google-recaptcha' );
    }
   }
add_action( 'wp_enqueue_scripts', 'load_recaptcha_js',100 );
   
// contact form 7 のファイルを必要な場合のみ読み込む
function wpcf7_file_control()
{
    if( !is_page("contact") ){
        wp_dequeue_style('contact-form-7');
        wp_dequeue_script('contact-form-7');
    }
}
add_action("wp_enqueue_scripts", "wpcf7_file_control");
?>
