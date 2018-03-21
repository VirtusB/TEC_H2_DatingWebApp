<?php

require_once 'core/init.php';

if (Input::exists()) {
    $file = $_FILES['img_input'];

    $fileName = $_FILES['img_input']['name'];
    $fileTmpName = $_FILES['img_input']['tmp_name'];
    $fileSize = $_FILES['img_input']['size'];
    $fileError = $_FILES['img_input']['error'];
    $fileType = $_FILES['img_input']['type'];
    
    // get file extension
    $fileExt = explode('.', $fileName);
    // get lowercase file extension and get the last item from $fileExt array with end()
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png');
    
    
  
        
        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 1000000) {
                    //$fileNameNew = uniqid('', true) . "." . $fileActualExt;
                    //$fileDestination = 'uploads/' . $fileNameNew;
                    //move_uploaded_file($fileTmpName, $fileDestination);
                    $image = file_get_contents($fileTmpName);
                    $encoded_image = base64_encode($image);
                    Redirect::to('opret');
                    // fil success

                } else {
                    // fil for stor                   
                }
            } 
            else {
                // fejl ved upload
            }
        } else {
            // fil ikke tilladt
        }
    } 
    
    