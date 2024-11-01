<?php

/*

Plugin Name: Simple Website Review
Plugin URI: http://roderickpughmarketing.com
Version: 1.0
Author: Roderick Pugh Marketing
Description: Plugin to add a website review feature to your website.

*/

/* 
Copyright (C) 2015  Roderick Pugh Marketing

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if (!class_exists("simple_website_review")) {
    class simple_website_review extends WP_Widget {

        var $adminOptionsName = "simple_website_review_admin_options";

        //Construct
        public function __construct() {
            add_shortcode( 'simple-website-review', array( $this , 'review_page' ));
            parent::WP_Widget(false, $name = __('Simple Website Review Widget'),array( 'description' => __( 'Customisable widget linking to website review page', 'wpb_widget_domain' ), ) );
        }

        //Enqueue
        function addHeaderCode() {
            if (function_exists('wp_enqueue_script')) {
                wp_enqueue_script('simple_website_review', plugins_url( 'js/script.js', __FILE__ ), array('prototype'), '0.1');
            }
            $localize = array(
                'ajaxurl' => admin_url( 'admin-ajax.php' )
            );
            wp_localize_script('simple_website_review', 'SWR', $localize);

            echo '<link type="text/css" rel="stylesheet" href="' . plugins_url( 'css/style.css', __FILE__ ) . '">';
        }//End function addHeaderCode

        function getAdminOptions() {
            $swrAdminOptions = array(
                'google'            => '',
                'freeindex'         => '',
                'whatclinic'        => '',
                'yelp'              => '',
                'thomsonlocal'      => '',
            );
            $devOptions = get_option($this->adminOptionsName);
            if (!empty($devOptions)) {
                foreach ($devOptions as $key => $option)
                    $swrAdminOptions[$key] = $option;
            }              
            update_option($this->adminOptionsName, $swrAdminOptions);
            return $swrAdminOptions;

        }//End function getAdminOptions

        function init() {
            $this->getAdminOptions();
        }//End function init

        //Prints out the admin page
        function printAdminPage() {
            $devOptions = $this->getAdminOptions();                    
            if (isset($_POST['update_simple_website_reviewSettings'])) {

                if (isset($_POST['simple_website_review_google'])) {
                    $devOptions['google'] = $_POST['simple_website_review_google'];
                } 
                if (isset($_POST['simple_website_review_freeindex'])) {
                    $devOptions['freeindex'] = $_POST['simple_website_review_freeindex'];
                }
                if (isset($_POST['simple_website_review_whatclinic'])) {
                    $devOptions['whatclinic'] = $_POST['simple_website_review_whatclinic'];
                }
                if (isset($_POST['simple_website_review_yelp'])) {
                    $devOptions['yelp'] = $_POST['simple_website_review_yelp'];
                }
                if (isset($_POST['simple_website_review_thomsonlocal'])) {
                    $devOptions['thomsonlocal'] = $_POST['simple_website_review_thomsonlocal'];
                }

                update_option($this->adminOptionsName, $devOptions); ?>
                <div class="updated"><p><strong><?php _e("Settings Updated.", "simple_website_review");?></strong></p></div>
                <?php
            }//End if ?>

            <div class=wrap>
                <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                    <h2>Simple Website Review</h2>
                    <p>
                        Here you can add links to your review site pages to direct customers to.<br />
                        <b>Example: www.yelp.co.uk/biz/company-name</b><br />
                        <b>(URL does NOT need http:// added before it)</b>
                    </p>
                    <p>
                        Once these have been filled in, create a new page named website-review and add this shortcode to implement the review options: [simple-website-review]
                    </p>
                    <p>
                        There is also a widget included with this plugin to link people to the website review page.
                    </p>
                    <h3>Google</h3>
                    <input type="text" name="simple_website_review_google" value="<?php _e(apply_filters('format_to_edit',$devOptions['google']), 'simple_website_review') ?>">
                    
                    <h3>Freeindex</h3>
                    <input type="text" name="simple_website_review_freeindex" value="<?php _e(apply_filters('format_to_edit',$devOptions['freeindex']), 'simple_website_review') ?>">

                    <h3>WhatClinic</h3>
                    <input type="text" name="simple_website_review_whatclinic" value="<?php _e(apply_filters('format_to_edit',$devOptions['whatclinic']), 'simple_website_review') ?>">

                    <h3>Yelp</h3>
                    <input type="text" name="simple_website_review_yelp" value="<?php _e(apply_filters('format_to_edit',$devOptions['yelp']), 'simple_website_review') ?>">

                    <h3>Thomson Local</h3>
                    <input type="text" name="simple_website_review_thomsonlocal" value="<?php _e(apply_filters('format_to_edit',$devOptions['thomsonlocal']), 'simple_website_review') ?>">

                    <div class="submit">
                        <input type="submit" name="update_simple_website_reviewSettings" value="<?php _e('Save', 'simple_website_review') ?>" />
                    </div>
                </form>
            </div>
            <?php
        }//End function printAdminPage()  

        //Shortcode to print on review page
        function review_page( $atts ){ ?>
            <div class="review-page">

                <!-- Question Page -->
                <div class="question">
                    <h2>What kind of feedback would you like to leave?</h2>
                    <?php echo '<div class="positive" ><img src="'. plugins_url( 'images/happy.png', __FILE__ ) .'"> Positive?</div>'; ?>
                    or 
                    <?php echo '<div class="negative"><img src="'. plugins_url( 'images/sad.png', __FILE__ ) .'"> Negative?</div>'; ?>
                </div>

                <!-- Positive Page -->
                <div class="links">
                    <h2>Please use these links to leave a review!</h2>
                    <?php
                    $devOptions = $this->getAdminOptions();
                    foreach ($devOptions as $key => $value) {
                        if($value <> ''){ ?>
                            <div class="simple-website-review swr-<?php echo $key; ?>">
                                <a href="http://<?php echo $value; ?>" target="_blank">
                                    <?php echo '<img src="'. plugins_url( 'images/' . $key, __FILE__ ) .'.jpg" alt="simple-website-review-<?php echo $key; ?>" />'; ?>
                                </a>
                            </div>
                        <?php }
                    } ?>
                    <a href="#" class="back">Back</a>
                </div>

                <!-- Negative Page -->
                <div class="form">
                    <div class="simple-contact-form">
                        <h2>Please fill in this form to let us know what problem you had</h2>
                        <form id="scuf">
                            <label>Name:</label><br />
                            <input id="swrn" class="text" type="text" name="swrn"/><br />
                            <label>E-mail:</label><br />
                            <input id="swre" class="text" type="text" name="swre"/><br />
                            <label>Subject:</label><br />
                            <input id="swrsj" class="text" type="text" name="swrsj"/><br />
                            <label>Message:</label><br />
                            <textarea id="swrm" class="textarea" name="swrm"></textarea><br />
                            <input name="action" type="hidden" value="simple_website_review_contact_form" />
                            <?php wp_nonce_field( 'swr_html', 'swr_nonce' ); ?>
                            <input id="swrs" class="button-send" type="submit" name="swrs" value="Send Message"/>
                            <?php echo '<img class="swr-ajax" src="'. plugins_url( 'images/load.gif', __FILE__ ) .'" alt="Sending Message">'; ?>
                            <div class="formmessage"><p></p></div>
                        <form>
                    </div>
                    <a href="#" class="back">Back</a>
                </div>
            </div>
        <?php }//End function review_page

        // WIDGET form creation
        function form($instance) { 

            if( $instance) {
                 $link1 = esc_attr($instance['link1']);
                 $textarea = esc_textarea($instance['textarea']);
                 $image_style = esc_attr($instance['image_style']);
            } else {
                 $link1 = '/website-review';
                 $textarea = '';
                 $image_style = 'image1';
            }
            ?>
        
            <p>
                <label for="<?php echo $this->get_field_id('textarea'); ?>"><?php _e('Textarea:', 'simple_website_review'); ?></label>
                <textarea class="widefat" id="<?php echo $this->get_field_id('textarea'); ?>" name="<?php echo $this->get_field_name('textarea'); ?>"><?php echo $textarea; ?></textarea>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('link1'); ?>"><?php _e('Link to page:', 'simple_website_review'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('link1'); ?>" name="<?php echo $this->get_field_name('link1'); ?>" type="text" value="<?php echo $link1; ?>" />
            </p> 
            <p>
                <label for="<?php echo $this->get_field_id('image1'); ?>"><?php _e('Customer Feedback Image:'); ?></label>
                <input class="" id="<?php echo $this->get_field_id('image1'); ?>" name="<?php echo $this->get_field_name('image_style'); ?>" type="radio" value="image1" <?php if($image_style === 'image1'){ echo 'checked="checked"'; } ?> />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('image2'); ?>"><?php _e('Client Feedback Image:'); ?></label>
                <input class="" id="<?php echo $this->get_field_id('image2'); ?>" name="<?php echo $this->get_field_name('image_style'); ?>" type="radio" value="image2" <?php if($image_style === 'image2'){ echo 'checked="checked"'; } ?> />
                
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('image3'); ?>"><?php _e('Patient Feedback Image:'); ?></label>
                <input class="" id="<?php echo $this->get_field_id('image3'); ?>" name="<?php echo $this->get_field_name('image_style'); ?>" type="radio" value="image3" <?php if($image_style === 'image3'){ echo 'checked="checked"'; } ?> />
                
            </p>
            <?php

        }

        // widget update
        function update($new_instance, $old_instance) {
            $instance = $old_instance;
          // Fields
            $instance['link1'] = strip_tags($new_instance['link1']);
            $instance['textarea'] = strip_tags($new_instance['textarea']);
            $instance['image_style'] = strip_tags($new_instance['image_style']);
            return $instance;
        }
     
        // widget display
        function widget($args, $instance) {
             extract( $args );

           // these are the widget options
           $link1 = $instance['link1'];
           $textarea = $instance['textarea'];
           $image_style = $instance['image_style'];

           echo $before_widget;

           // Display the widget
           echo '<div class="swr_widget_box">';

           //Display Image
           echo '<div class="swr_widget_left">';
           if ($image_style == 'image1') {
                echo '<img src="'. plugins_url( 'images/customer-feedback.png', __FILE__ ) .'">';
           }
           if ($image_style == 'image2') {
                echo '<img src="'. plugins_url( 'images/client-feedback.png', __FILE__ ) .'">';
           }
           if ($image_style == 'image3') {
                echo '<img src="'. plugins_url( 'images/patient-feedback.png', __FILE__ ) .'">';
           }
           echo '</div>';

            // Check if textarea is set
           echo '<div class="swr_widget_right">';
           if( $textarea ) {
             echo '<p class="swr_widget_textarea">'.$textarea.'</p>';
           }

           // Check if text is set
           if( $link1 ) {
              echo '<a href="'.$link1.'" class="swr_widget_link">Let us know >></a>';
           }

           echo '</div>';
           echo '</div>';
           echo '<div class="clear"></div>';
           echo $after_widget;

        }

    }//End Class simple_website_review
} //End if


if (class_exists("simple_website_review")) {
    $swr = new simple_website_review();
}//End if

function swr_ajax_simple_website_review_contact_form() {
    if ( isset( $_POST['swr_nonce'] ) && wp_verify_nonce( $_POST['swr_nonce'], 'swr_html' ) ) {
        $name = sanitize_text_field($_POST['swrn']);
        $email = sanitize_email($_POST['swre']);
        $subject = sanitize_text_field($_POST['swrsj']);
        $message = wp_kses_data($_POST['swrm']);
        $headers[] = 'From: ' . $name . ' <' . $email . '>' . "\r\n";
        $headers[] = 'Content-type: text/html' . "\r\n";
        $to = get_option( 'admin_email' );

        wp_mail( $to, $subject, $message, $headers );
    }
    die();
}//End function swr_ajax_simple_website_review_contact_form

//Initialize the admin panel
if (!function_exists("simple_website_review_ap")) {
    function simple_website_review_ap() {
        global $swr;
        if (!isset($swr)) {
            return;
        }
        if (function_exists('add_options_page')) {
            add_options_page('Simple Website Review', 'Simple Website Review', 9, basename(__FILE__), array(&$swr, 'printAdminPage'));
        }
    }   
}

//Actions
if (isset($swr)) {
    //Actions
    add_action( plugins_url( 'simple-website-review.php', __FILE__ ),  array(&$swr, 'init'));
    add_action('admin_menu', 'simple_website_review_ap');
    add_action('wp_head', array(&$swr, 'addHeaderCode'), 1);
    add_action( 'wp_ajax_simple_website_review_contact_form', 'swr_ajax_simple_website_review_contact_form' );
    add_action( 'wp_ajax_nopriv_simple_website_review_contact_form', 'swr_ajax_simple_website_review_contact_form' );
    add_action('widgets_init', create_function('', 'return register_widget("simple_website_review");'));
}





