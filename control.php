<?php
    require_once("define.php");
    require_once("mysql.class.php");
	require_once("make_string.php");    
	require_once("db_functions.php");

    function account_login($user_id) {

        $strEcho = "";
	    $user_info = getUserInfoByuser_id($user_id);	
        
        if (count($user_info) > 0) {            
            $strEcho = "<status>OK</status>";
        	$strEcho .= "<serverTime>".time()."</serverTime>";
            $strEcho .= "<userInfo>".make_echo_string_user_info($user_info[0])."</userInfo>";
            return $strEcho;            
        }
        else {
            $strEcho = make_echo_string_error_msg("This user name is not registered.");
            return $strEcho;
        }
    }

    function account_signup($user_id, $name, $gender, $age, $email, $image, $occupation, $organization, $industry, $location,
			$experience, $skill, $education, $about) {
        
        $strEcho = "";
        $user_info_by_user_id = getUserInfoByUserName($user_id);

        if (count($user_info_by_user_id) > 0) {

            $strEcho = make_echo_string_error_msg("This user name is already existed.");
            return $strEcho;
        }
        else {                         
            // sign up via twitter
            $target_path = "upload/profile/";
			$target_path = $target_path . basename($image['name']);
		
            if(move_uploaded_file($image['tmp_name'], $target_path)) {
                // echo "The file ". basename( $_FILES['myfile']['name']) . " has been uploaded";
				$user_id = addNewUserAccount($user_id, $name, $gender, $age, $email, $target_path, $occupation, $organization,
							$industry, $location, $experience, $skill, $education, $about);
                $strEcho = "<status>OK</status>";
                $user_info = getUserInfoByUserID($user_id );
				$strEcho .= "<serverTime>".time()."</serverTime>";
                $strEcho .= "<userInfo>".make_echo_string_user_info($user_info[0])."</userInfo>";
            } else {
                // echo "There was an error uploading the file, please try again!";
                $strEcho = make_echo_string_error_msg("There was an error uploading the profile image, please try again!");
            }
            return $strEcho;            
        }
    }   
	
    function get_user_profile($user_id) {

        $strEcho = "<status>OK</status>";
        $strEcho .= "<data>";
        $strEcho .= "<serverTime>".time()."</serverTime>";

        $user_array = getUserInfoByuser_id($user_id);

        if ($user_array == null) {
            $strEcho .= "</data>";
            return $strEcho;
        }

        $strEcho .= "<userInfo>".make_echo_string_user_info($user_array[0])."</userInfo>";
        $strEcho .= "</data>";

        return $strEcho;
    }
	
	function get_user_match_skill($user_id, $skill) {

        $strEcho = "<status>OK</status>";
        $strEcho .= "<data>";
        $strEcho .= "<serverTime>".time()."</serverTime>";
		
		$arySkill = explode(":", $skill);
		foreach ($arySkill as &$value) {
			getUserFromSkill();
		}

        $user_array = getUserInfoByuser_id($user_id);

        if ($user_array == null) {
            $strEcho .= "</data>";
            return $strEcho;
        }

        $strEcho .= "<userInfo>".make_echo_string_user_info($user_array[0])."</userInfo>";
        $strEcho .= "</data>";

        return $strEcho;
    }
    
    function update_profile($user_id, $name, $gender, $age, $email, $image, $occupation, $organization, $industry, $location,
			$experience, $skill, $education, $about) {
        
        $strEcho = "";
        $user_info_by_user_id = getUserInfoByuser_id($user_id);

        if (count($user_info_by_user_id ) == 0) {
            $strEcho = make_echo_string_error_msg("Can not find your user profile.");
            return $strEcho;
        }
        
        $target_path = "upload/profile/";
	    $target_path = $target_path . basename($image['name']);
	
        if(move_uploaded_file($image['tmp_name'], $target_path)) {
            // echo "The file ". basename( $_FILES['myfile']['name']) . " has been uploaded";
			updateUserAccount($user_id, $name, $gender, $age, $email, $target_path, $occupation, $organization, $industry, 
					$location, $experience, $skill, $education, $about);

	    $strEcho = "<status>OK</status>";
            $user_info = getUserInfoByuser_id($user_id );
            $strEcho .= "<serverTime>".time()."</serverTime>";
            $strEcho .= "<userInfo>".make_echo_string_user_info($user_info[0])."</userInfo>";
        } else {
            // echo "There was an error uploading the file, please try again!";
            $strEcho = make_echo_string_error_msg("There was an error uploading the profile image, please try again!");
        }
        return $strEcho;
    }
    
?>