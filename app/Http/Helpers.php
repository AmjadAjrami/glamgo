<?php

use App\Models\Favorite;
use App\Models\ItemAction;
use App\Models\ItemView;
use App\Models\Rate;
use App\Models\Support;
use Carbon\Carbon;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

function locale()
{
    return Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();
}

function locales()
{
    $arr = [];
    foreach (LaravelLocalization::getSupportedLocales() as $key => $value) {
        $arr[$key] = __('' . $value['name']);
    }
    return $arr;
}

function is_rtl($string)
{
    $rtl_chars_pattern = '/[\x{0590}-\x{05ff}\x{0600}-\x{06ff}]/u';
    return preg_match($rtl_chars_pattern, $string);
}

function store_file($file)
{
    $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
    $filename .= substr($file->getClientOriginalName(), strrpos($file->getClientOriginalName(), '.'));
    $filename = $filename = $file->move(storage_path('app/public/files'), $filename);

    return $filename->getBasename();
}

/**
 * @param $status
 * @param $message
 * @param $data
 * @param $validator
 * @param int $code
 * @return \Illuminate\Http\JsonResponse
 */
function mainResponse_2($status, $message, $data, $validator, $code = 200)
{
    if (isset(json_decode(json_encode($data, true), true)['data'])) {
        $pagination = json_decode(json_encode($data, true), true);
        $data = $pagination['data'];
    }
    $errors = [];
    foreach ($validator as $key => $value) {
        $errors[] = ['field_name' => $key, 'message' => $value];
    }

    $newData = ['code' => $code, 'status' => $status, 'message' => __($message), 'data' => $data, 'errors' => $errors];

    return response()->json($newData);
}

/**
 * @param $status
 * @param $message
 * @param $data
 * @param $validator
 * @param int $code
 * @param null $pages
 * @return \Illuminate\Http\JsonResponse
 */
function mainResponse($status, $message, $data, $validator, $code = 200, $data_pages = null)
{
    if (isset(json_decode(json_encode($data, true), true)['data'])) {
        $pagination = json_decode(json_encode($data, true), true);
        $data = $pagination['data'];
        $pages = [
            'current_page' => $pagination['current_page'],
            'first_page_url' => $pagination['first_page_url'],
            'from' => $pagination['from'],
            'last_page' => $pagination['last_page'],
            'last_page_url' => $pagination['last_page_url'],
            'next_page_url' => $pagination['next_page_url'],
            'path' => $pagination['path'],
            'per_page' => $pagination['per_page'],
            'prev_page_url' => $pagination['prev_page_url'],
            'to' => $pagination['to'],
            'total' => $pagination['total'],
        ];
    } else {
        $pages = [
            'current_page' => 0,
            'first_page_url' => '',
            'from' => 0,
            'last_page' => 0,
            'last_page_url' => '',
            'next_page_url' => null,
            'path' => '',
            'per_page' => 0,
            'prev_page_url' => null,
            'to' => 0,
            'total' => 0,
        ];
    }

    if ($data_pages != null) {
        $pages = [
            'current_page' => $data_pages['current_page'],
            'first_page_url' => $data_pages['first_page_url'],
            'from' => $data_pages['from'],
            'last_page' => $data_pages['last_page'],
            'last_page_url' => $data_pages['last_page_url'],
            'next_page_url' => $data_pages['next_page_url'],
            'path' => $data_pages['path'],
            'per_page' => $data_pages['per_page'],
            'prev_page_url' => $data_pages['prev_page_url'],
            'to' => $data_pages['to'],
            'total' => $data_pages['total'],
        ];
    }

    $errors = [];
    foreach ($validator as $key => $value) {
        $errors[] = ['field_name' => $key, 'message' => $value];
    }

    $newData = ['code' => $code, 'status' => $status, 'message' => __($message), 'data' => $data, 'pages' => $pages, 'errors' => $errors];

    return response()->json($newData);
}

/**
 * @param $status
 * @param $message
 * @param $data
 * @param $validator
 * @param int $code
 * @param null $pages
 * @return \Illuminate\Http\JsonResponse
 */
function mainResponseHome($status, $message, $sidebar, $data, $validator, $code = 200, $pages = null)
{
    if (isset(json_decode(json_encode($data, true), true)['data'])) {
        $pagination = json_decode(json_encode($data, true), true);
        $data = $pagination['data'];
        $pages = [
            'current_page' => $pagination['current_page'],
            'first_page_url' => $pagination['first_page_url'],
            'from' => $pagination['from'],
            'last_page' => $pagination['last_page'],
            'last_page_url' => $pagination['last_page_url'],
            'next_page_url' => $pagination['next_page_url'],
            'path' => $pagination['path'],
            'per_page' => $pagination['per_page'],
            'prev_page_url' => $pagination['prev_page_url'],
            'to' => $pagination['to'],
            'total' => $pagination['total'],
        ];
    } else {
        $pages = [
            'current_page' => 0,
            'first_page_url' => '',
            'from' => 0,
            'last_page' => 0,
            'last_page_url' => '',
            'next_page_url' => null,
            'path' => '',
            'per_page' => 0,
            'prev_page_url' => null,
            'to' => 0,
            'total' => 0,
        ];
    }

    $errors = [];
    foreach ($validator as $key => $value) {
        $errors[] = ['field_name' => $key, 'message' => $value];
    }

    $newData = ['code' => $code, 'status' => $status, 'message' => __($message), 'sidebar' => $sidebar, 'data' => $data, 'pages' => $pages, 'errors' => $errors];

    return response()->json($newData);
}

function fcmNotification($token, $id, $title, $content, $body, $type, $device, $reference_id = 0, $icon = null, $firebase_id = null, $is_active = null,
                         $reservation_id = null, $offer_id = null, $order_id = null,
                         $owner_id = null, $owner_name = null, $owner_type = null, $owner_image = null, $admin_id = null)
{
    if (count($token) < 1)
        return null;

    $msg = [
        'id' => $id,
        'title' => $title,
        'content' => $content,
        'body' => $body,
        'type' => $type,
        'reference_id' => $reference_id,
        'icon' => $icon,
        'owner_id' => $owner_id,
        'owner_type' => $owner_type,
        'owner_name' => $owner_name,
        'owner_image' => $owner_image,
        'reservation_id' => $reservation_id,
        'offer_id' => $offer_id,
        'order_id' => $order_id,
        'admin_id' => $admin_id,
        'firebase_id' => $firebase_id,
        'is_active' => $is_active,
        'sound' => 'mySound',
    ];

    if ($device == 'ios') {
        $fields = [
            'registration_ids' => $token,
            'notification' => $msg,
        ];
    } else {
        $fields = [
            'registration_ids' => $token,
            'data' => $msg,
        ];
    }

    $headers = [
        'Authorization: key=' . 'AAAAO98-AwA:APA91bE94CDdPfWVpDj61kQVJS3lptRe4zUOWdQOq8O6jaS_auHe-1fJzF-c_Bk0tdlbqXg45Wn8XCsDGcnmIG72TPnrGvKev04LW7SEgKXqRBYVrMHGMxukM3nztR2fB7fcR5KjsEFX',
        'Content-Type: application/json'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);

    curl_close($ch);
    return $result;
}


function sendMessage($mobile, $message)
{
    $headers = [
        'MIME-Type:application/x-www-form-urlencoded',
    ];

    $fields = [
        'to' => $mobile,
        'message' => $message,
        'sender' => 'GlamGo',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.maqsam.com/v2/sms');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_USERPWD, 'crgOKnnCB0YolPsyQOPT' . ':' . 'Lfg0GhelIrZh4Dxa1WQw');
    $result = curl_exec($ch);

    curl_close($ch);

    return $result;
}


function getDistanceInKM($lat1, $lon1, $lat2, $lon2)
{
    $pi80 = M_PI / 180;
    $latitude1 = $lat1 * $pi80;
    $longitude1 = $lon1 * $pi80;
    $latitude2 = $lat2 * $pi80;
    $longitude2 = $lon2 * $pi80;

    $r = 6372.797; // radius of Earth in km 6371;
    $dlat = $latitude2 - $latitude1;
    $dlon = $longitude2 - $longitude1;

    $a = sin($dlat / 2) * sin($dlat / 2) + cos($latitude1) * cos($latitude2) * sin($dlon / 2) * sin($dlon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $km = $r * $c;

    return round($km);
}

function check_number($mobile)
{
    $persian = array('٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠');
    $num = range(9, 0);
    $mobile = str_replace(' ', '', $mobile);
    $mobile = str_replace($persian, $num, $mobile);
//    $mobile = substr($mobile, -9);

//    if (preg_match("/^[5][0-9]{8}$/", $mobile)) {
    return $mobile;
//    } else {
//        return FALSE;
//    }
}

function getDistance($lat1, $lon1, $lat2, $lon2)
{
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    return number_format(round($miles * 1.609344, 2), 2);
}

function getAddress($latitude, $longitude)
{
    //google map api url
    $url = "https://maps.google.com/maps/api/geocode/json?latlng=$latitude,$longitude&key=";

    // send http request
    $geocode = file_get_contents($url);
    $json = json_decode($geocode);
//    dd($json);
    $address = $json->results[0]->formatted_address;
    return $address;
}

function FileSizeConvert($bytes)
{
    $bytes = floatval($bytes);
    $arBytes = array(
        0 => array(
            "UNIT" => "TB",
            "VALUE" => pow(1024, 4)
        ),
        1 => array(
            "UNIT" => "GB",
            "VALUE" => pow(1024, 3)
        ),
        2 => array(
            "UNIT" => "MB",
            "VALUE" => pow(1024, 2)
        ),
        3 => array(
            "UNIT" => "KB",
            "VALUE" => 1024
        ),
        4 => array(
            "UNIT" => "B",
            "VALUE" => 1
        ),
    );

    foreach ($arBytes as $arItem) {
        if ($bytes >= $arItem["VALUE"]) {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", ",", strval(round($result, 2))) . " " . $arItem["UNIT"];
            break;
        }
    }
    return $result;
}

function generate_token()
{
    $data = [
        'sadadId' => '3163340',
        'secretKey' => 'h9wjy5UQMKgSfuhb',
        'domain' => 'web.qlamsa.com',
        'type' => 'sandbox',
    ];

    $headers = [
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($data)),
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.sadadqatar.com/api-v4/userbusinesses/getsdktoken');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $result = curl_exec($ch);

    curl_close($ch);

    return $result;
}
//
//function getChecksumFromString($str, $key)
//{
//    $salt = generateSalt_e(4);
//    $finalString = $str . "|" . $salt;
//    $hash = hash("sha256", $finalString);
//    $hashString = $hash . $salt;
//    $checksum = encrypt_e($hashString, $key);
//    return $checksum;
//}
//
//function generateSalt_e($length)
//{
//    $random = "";
//    srand((double) microtime() * 1000000);
//    $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
//    $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
//    $data .= "0FGH45OP89";
//    for ($i = 0; $i < $length; $i++) {
//        $random .= substr($data, (rand() % (strlen($data))), 1);
//    }
//    return $random;
//}
//
//function encrypt_e($input, $ky)
//{
//    $ky = html_entity_decode($ky);
//    $iv = "@@@@&&&&####$$$$";
//    $data = openssl_encrypt($input, "AES-128-CBC", $ky, 0, $iv);
//    return $data;
//}
