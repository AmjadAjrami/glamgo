@php
    function getChecksumFromString($str, $key) {

     $salt = generateSalt_e(4);
     $finalString = $str . "|" . $salt;
     $hash = hash("sha256", $finalString);
     $hashString = $hash . $salt;
     $checksum = encrypt_e($hashString, $key);
     return $checksum;

    }

    function generateSalt_e($length) {

     $random = "";
     srand((double) microtime() * 1000000);
     $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
     $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
     $data .= "0FGH45OP89";
     for ($i = 0; $i < $length; $i++) {
     $random .= substr($data, (rand() % (strlen($data))), 1);  }
     return $random;
    }

    function encrypt_e($input, $ky) {
     $ky = html_entity_decode($ky);
     $iv = "@@@@&&&&####$$$$";
     $data = openssl_encrypt($input, "AES-128-CBC", $ky, 0, $iv);  return $data;
    }

     $sadad_checksum_array = array();
     $sadad__checksum_data = array();
     $txnDate = date('Y-m-d H:i:s');
     $email ='example@example.com';
     $secretKey = 'h9wjy5UQMKgSfuhb';
     $merchantID = '3163340';
     $sadad_checksum_array['merchant_id'] = $merchantID;
     $sadad_checksum_array['ORDER_ID'] = $order_id;
     $sadad_checksum_array['WEBSITE'] = 'www.example.com';
     $sadad_checksum_array['TXN_AMOUNT'] = $amount;
     $sadad_checksum_array['CUST_ID'] = $email;
     $sadad_checksum_array['EMAIL'] = $email;
     $sadad_checksum_array['MOBILE_NO'] = $mobile;
     $sadad_checksum_array['SADAD_WEBCHECKOUT_PAGE_LANGUAGE'] = $lang;
     $sadad_checksum_array['CALLBACK_URL'] = $callback_url;
     $sadad_checksum_array['txnDate'] = $txnDate;
     $sadad_checksum_array['productdetail'] = $items;

    $sadad__checksum_data['postData'] = $sadad_checksum_array;
    $sadad__checksum_data['secretKey'] = $secretKey;

    $sAry1 = array();

                    $sadad_checksum_array1 = array();
                    foreach($sadad_checksum_array as $pK => $pV){
                        if($pK=='checksumhash') continue;
                        if(is_array($pV)){
                            $prodSize = sizeof($pV);
                            for($i=0;$i<$prodSize;$i++){
                                foreach($pV[$i] as $innK =>
    $innV){
            $sAry1[] = "<input type='hidden' name='productdetail[$i][". $innK ."]' value='" . trim($innV)
    . "'/>";
        $sadad_checksum_array1['productdetail'][$i][$innK] =
    trim($innV);
            }
        }
                        } else {
                            $sAry1[] = "<input type='hidden' name='". $pK ."' id='". $pK ."' value='" . trim($pV) . "'/>";
    $sadad_checksum_array1[$pK] =
    trim($pV);
            }
        }
    $sadad__checksum_data['postData'] = $sadad_checksum_array1;
    $sadad__checksum_data['secretKey'] = $secretKey;  $checksum
    =
    getChecksumFromString(json_encode($sadad__checksum_data), $secretKey .
    $merchantID);
     $sAry1[] = "<input type='hidden'  name='checksumhash'
    value='" . $checksum . "'/>";

    $action_url = 'https://sadadqa.com/webpurchase';
            echo '<form action="' . $action_url . '" method="post" id="paymentform" name="paymentform" data-link="' . $action_url .'">'
            . csrf_field()
            . implode('', $sAry1)
            . '<script type="text/javascript">document.paymentform.submit();</script>' .
            '</form>';
@endphp
