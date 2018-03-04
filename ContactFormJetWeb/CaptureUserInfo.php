<?php

// Declaring the class name

class CaptureUserInfo {
    
// Defining the class variables

    private
            $user,
            $email,
            $location;
    
// Creating the constructor to accept the variables when creating a object

    function __construct($user, $email, $location) {
        $this->user = $user;
        $this->email = $email;
        $this->location = $location;
    }
    
// Defining the captureInfo() function that will add the info to the database

    function captureInfo() {
        global $wpdb;
        $wpdb->insert('userinformation', array(
            'userName' => $this->user,
            'userEmail' => $this->email,
            'userLocation' => $this->location,
            'userDateReg' => date('Y/m/d H:i:s')
        ));
    }
}
?>