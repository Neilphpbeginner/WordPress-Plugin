<?php

// Adding the comments that will be displayed in the plugin page on wordpress admin page

/*
 * Plugin Name: Contact Form JetWeb
 * Description: Filling in user information and submitting the data. If the user entered in all the data a conformation email will be sent to the user confirming resgistration with database intergration.
 * Version: 1.0.1
 * Author: Neil C Lemmer
 */

include 'CaptureUserInfo.php';

// Defining the bootstrap function that will add bootstrap to the plugin

function addBootStrapCss() {
// CSS
    wp_enqueue_style('myplugin-style', plugin_dir_url(__FILE__) . "css/bootstrap.min.css");
}

// Using the add_action function to add the addBootStrapCss function to the plugin.

add_action('wp_enqueue_scripts', 'addBootStrapCss');

// Defining the htmlFormLayout function that will be displayed in the browser.

function htmlFormLayout() {
    echo '<form action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="post">';
    echo '  <div class="form-group"> ';
    echo '    <label for="exampleInputEmail1">Email: </label>';
    echo '    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" value="' . (isset($_POST["email"]) ? esc_attr($_POST["email"]) : '') . '">';
    echo '  </div>';
    echo '  <div class="form-group">';
    echo '    <label for="exampleInputEmail1">Name: </label>';
    echo '    <input type="text" name="username" class="form-control" id="exampleInputEmail1" placeholder="Customer Name" value="' . (isset($_POST["username"]) ? esc_attr($_POST["username"]) : '') . '">';
    echo '  </div>';
    echo '  <div class="form-group">';
    echo '    <label for="exampleInputEmail1">Location: </label>';
    echo '    <input type="text" name="location" class="form-control" id="exampleInputEmail1" placeholder="Customer Location"  value="' . (isset($_POST["location"]) ? esc_attr($_POST["location"]) : '') . '">';
    echo '  </div>';
    echo '  <div class="form-group">';
    echo '    <?php echo  $errormessage ?>';
    echo '  </div>';
    echo '  <input type="submit" name="submit"class="btn btn-primary">';
    echo '</form>';
}

// Defining the sendConformation() function that will send the mail if the user entered in all the information correctly.

function sendConformationMail() {

//  Checking to see if the submit button was pressed

    if (isset($_POST['submit'])) {

//  Declaring variables that will be used if the form was submitted correctly.

        $userName = $_POST['username'];
        $userEmail = $_POST['email'];
        $userLocation = $_POST['location'];

//  If statement used to see if the user entered his or her name.

        if (empty($userName) && !empty($userEmail) && !empty($userLocation)) {
            echo '<div>';
            echo '<p>Please enter in your name.</p>';
            echo '</div>';
        }

//  If statement used to see if the user entered his or her email.

        if (!empty($userName) && empty($userEmail) && !empty($userLocation)) {
            echo '<div>';
            echo '<p>Please enter in your email.</p>';
            echo '</div>';
        }

//  If statement used to see if the user entered his or her location.

        if (!empty($userName) && !empty($userEmail) && empty($userLocation)) {
            echo '<div>';
            echo '<p>Please enter in your location.</p>';
            echo '</div>';
        }

//  If all information is entered in corretly a mail will be sent to the user confirming registration.

        if (!empty($userName) && !empty($userEmail) && !empty($userLocation)) {
            $subject = "Welcome to JetWeb.";
            $message = "Hello " . $userName . " . Thank you for registring at our webpage. You current loaction is. " . $userLocation;
            if (wp_mail($userEmail, $subject, $message)) {
                $capture = new CaptureUserInfo($userName, $userEmail, $userLocation);
                $capture->captureInfo();
                echo '<div>';
                echo '<p>Thanks for contacting us, expect a response soon.</p>';
                echo '</div>';
            } else {
                echo 'An unexpected error occurred';
            }
        }
    }
}

// Defining the shortcode() function

function shortcode() {
    ob_start();
    sendConformationMail();
    htmlFormLayout();
    return ob_get_clean();
}

// Using the add_shortcode function to add the shortcode() Function.

add_shortcode("JetWeb_Contact_Form", "shortcode");