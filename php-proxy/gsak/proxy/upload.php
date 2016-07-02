<?php
    include('class.uploader.php');
    
    /*
    * GSAK Upload directory
    *
    *LINUX e.g.
    *$gsak_upload_dir = '/var/uploads/'; // Last '/' required.
    *WINDOWS e.g.
    *$gsak_upload_dir = 'C:\\uploads\\'; // Last '\\' required.
    */

    $gsak_upload_dir = 'E:\\eclipse\\Workspace\\gephi-swiss-army-knife\\uploads\\'; // Last '/' required.

    $uploader = new Uploader();
    $data = $uploader->upload($_FILES['files'], array(
        'limit' => 10, //Maximum Limit of files. {null, Number}
        'maxSize' => 10, //Maximum Size of files {null, Number(in MB's)}
        'extensions' => null, //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
        'required' => false, //Minimum one file is required for upload {Boolean}
        'uploadDir' => '../uploads/', //Upload directory {String}
        'title' => array('name'), //New file name {null, String, Array} *please read documentation in README.md
        'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
        'perms' => null, //Uploaded file permisions {null, Number}
        'onCheck' => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
        'onError' => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
        'onSuccess' => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
        'onUpload' => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
        'onComplete' => null, //A callback function name to be called when upload is complete | ($file) | Callback
        'onRemove' => 'onFilesRemoveCallback' //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
    ));
    
    if($data['isComplete']){
        $files = $data['data'];
        $file_name_array = explode('/', $files['files'][0]);
        $file_name = $file_name_array[sizeof($file_name_array)-1];
        copy ($files['files'][0] , $gsak_upload_dir.$file_name);
        unlink($files['files'][0]);

        //tell gsak server that file has been uploaded
        // if ($_POST['route']=== 'graphFileUpload') {

        // } else {

        // }
        // $curl_handler = curl_init();
        // curl_setopt($curl_handler, CURLOPT_HEADER, 0);
        // curl_setopt($curl_handler, CURLOPT_VERBOSE, 0);
        // curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl_handler, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        // $url = "localhost:9090/".$_POST['route']."?"."file=".$file_name;
        // curl_setopt($curl_handler, CURLOPT_URL, $url);
        // $response = curl_exec($curl_handler);
        // curl_close($curl_handler);
        // print $response;
        print_r($file_name);
    }

    if($data['hasErrors']){
        $errors = $data['errors'];
        print_r($errors);
    }
    
    function onFilesRemoveCallback($removed_files){
        foreach($removed_files as $key=>$value){
            $file = '../uploads/' . $value;
            if(file_exists($file)){
                unlink($file);
            }
        }
        
        return $removed_files;
    }
?>
