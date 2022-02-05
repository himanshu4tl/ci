<?php

function getClientIp()
{
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function getIpInfo($ip)
{
    return @file_get_contents('https://code.lembits.com/ipinfo/index.php?ip='.$ip);
}

function sendMail($to,$subject,$body){

    $email = \Config\Services::email();
    $email->setFrom(EMAIL_FROM, APP_NAME);
    $email->setTo($to);
    $email->setSubject($subject);
    $email->setMessage($body);
    $email->send();
}

function alertWidget($message,$class){
    if($class=='error'){
        $class='danger';
    }
    if($message){
        echo '<div class="mbg-3 alert alert-dismissible fade show alert-'.$class.' ">
                <button data-dismiss="alert" class="close close-sm" type="button">
                    <i class="fa fa-times"></i>
                </button>
                <p>'.$message.'</p>
            </div>';
    }
}

function getBredCrumb(){
    echo '';

}

function getFirstError($errors){
    return array_shift($errors);
}

function getThumbUrl($image,$path='profile/'){
    if($image!=''){$imgArr=  explode('.', $image);
        if(isset($imgArr[1])){
            $thumb=$imgArr[0].'_thumb.'.$imgArr[1];
            return getFileUrl($thumb,$path);
        }}
    return NO_IMAGE;

}
function getFileUrl($image,$path='profile/'){
    if($image!=''){
        if(file_exists(UPLOAD_PATH.$path.$image)){
            return UPLOAD_PATH.$path.$image;
        }
    }
    return UPLOAD_PATH.NO_IMAGE;
}


function  deleteFile($file,$path='profile/'){
    if($file){
        $file=UPLOAD_PATH.$path.$file;
        if(file_exists($file)){
            unlink($file);
        }
    }
}

function fileUploadByBase64($data,$path='profile/'){
    if($data!=''){
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));
        $fileName=time().'.jpeg';
        file_put_contents(UPLOAD_PATH.$path.$fileName, $data);
        return ['status'=>1,'fileName'=>$fileName];
    }
    return ['status'=>0,'message'=>'something went wrong'];
}


function getGreetingMessage() {
    $message='';
    /* This sets the $time variable to the current hour in the 24 hour clock format */
    $time = date("H");
    /* Set the $timezone variable to become the current timezone */
    $timezone = date("e");
    /* If the time is less than 1200 hours, show good morning */
    if ($time < "12") {
        $message="Good morning";
    } else
        /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
        if ($time >= "12" && $time < "17") {
            $message="Good afternoon";
        } else
            /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
            if ($time >= "17" && $time < "19") {
                $message="Good evening";
            } else
                /* Finally, show good night if the time is greater than or equal to 1900 hours */
                if ($time >= "19") {
                    $message="Good night";
                }
    return $message;
}
