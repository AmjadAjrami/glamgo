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
     $secretKey = 'Tj3MjrCSsK4GrBZv';
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

//    $action_url = 'https://sadadqa.com/webpurchase';
//            echo '<form action="' . $action_url . '" method="post" id="paymentform" name="paymentform" data-link="' . $action_url .'">'
//            . csrf_field()
//            . implode('', $sAry1)
//            . '<script type="text/javascript">document.paymentform.submit();</script>' .
//            '</form>';
@endphp

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300&display=swap" rel="stylesheet">

<style>
    .sadadpaynow{
        background: #cd6a77 !important;
        border: 1px #cd6a77 solid !important;
    }
    .logo {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 55%;
        margin-bottom: 50px;
        margin-top: 50px;
    }
    .logo img{
        width: 200px !important;
    }
    html{
        @if($lang === 'ARB')
            direction: rtl;
        @else
            direction: ltr;
        @endif
    }
    body{
        float: left !important;
        margin-left: 30px !important;
        margin-right: 30px !important;
        font-size: 20px !important;
        font-family: 'Almarai', sans-serif;
    }
    input::placeholder {
        font: 1rem/3 'Almarai', sans-serif;
        padding-right: 10px !important;
    }
    .card-js{
        margin-top: 20px !important;
    }
    .card-js input, .card-js select {
        font-size: 20px !important;
        font-weight: bold !important;
        height: 45px !important;
        font-family: 'Almarai', sans-serif;
    }
    input#the-card-name-id,
    input.expiry,
    input.cvc{
        padding-right: 10px !important;
    }
    .card-js .icon{
        top: 15px !important;
    }
    label.sadadCardLbl{
        font-family: 'Almarai', sans-serif;
    }
    button{
        font-size: 20px !important;
        margin-top: 20px !important;
        font-weight: bold !important;
    }
    input[type="checkbox"]{
        margin-left: 10px !important;
    }
</style>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var sadadGetChecksum = function () {
        // afterChecksumSubmit($('#sadad_div').html());
        afterChecksumSubmit('<form id="sadadFinalForm">' + $('#sadadFinalForm1').html() + '</form>');
    };
</script>
<div class="logo">
    <img src="{{ asset('logo.png') }}">
</div>
<div id="sadad_cc_container" data-sd-lang="{{ $lang }}" data-i-color="#cd6a77" data-apple-enable="1" data-apple-sadadid="3163340" data-apple-bizname="Glamgo App Electronic" data-apple-domain="glamgoapp.com" data-cbfunc="sadadGetChecksum"></div>
<div id="sadad_div">
    <?php echo '<form id="sadadFinalForm1">'. implode('', $sAry1) .'</form>'; ?>
</div>
<script src="https://sadadqa.com/jslib/sadad1.js"></script>
