<?php
//////////////////////////////////////////////////////////////
//          IN $key_value SE PUN PARAMATRII CALL-ULUI       //
//////////////////////////////////////////////////////////////
function CallAPI($method, $url, $key_value = false, $headers="", $body="", $token="", &$responseContentType = "", &$httpCode = "")
{
    //print("From json_functions.inc, CallAPI, linia 7: ");
    //print($method);
    //print_r($key_value);
    //print($url);
    //print($headers);
    //print($body);
    //print("token: " . $token);
    //print("<br/><br/>");
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLINFO_HEADER_OUT, true);

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($key_value)
            {
                //curl_setopt($curl, CURLOPT_POSTFIELDS, $key_value);
                //print('aaa');
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => false,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => $method,
                    CURLOPT_POSTFIELDS => http_build_query($key_value)
                    //CURLOPT_POSTFIELDS => array('grant_type' => 'password','client_id' => '60ecb2f5f81b653076556fa7','client_secret' => 'j9SL2isjobfHOaAAcI35Va2zDzkJ6e2XVQ0hkfFug3Ht','username' => 'aionescu74@yahoo.com','password' => 'B10zbx1c.'),
                  ));
            }
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        case "DELETE":
            //print("From json_functions.inc, CallAPI, linia 49: ");
            
            curl_setopt($curl, CURLOPT_POST, 1);
            

            if ($key_value)
            {
                //print($method);
                //curl_setopt($curl, CURLOPT_POSTFIELDS, $key_value);
                //print('aaa');
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => false,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => $method,
                    CURLOPT_POSTFIELDS => http_build_query($key_value)
                    //CURLOPT_POSTFIELDS => array('grant_type' => 'password','client_id' => '60ecb2f5f81b653076556fa7','client_secret' => 'j9SL2isjobfHOaAAcI35Va2zDzkJ6e2XVQ0hkfFug3Ht','username' => 'aionescu74@yahoo.com','password' => 'B10zbx1c.'),
                  ));
            }
            break;
        default:
            //print($method);
            if ($key_value)
            {
               $url = sprintf("%s?%s", $url, http_build_query($key_value));
            }
            //print($url);
    }
    

    // Optional Authentication:
    //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    //curl_setopt($curl, CURLOPT_USERPWD, "username:password");
    if($headers != "")
    {
        //$headers = array(
        //    'Content-type: application/xml',
        //    'Authorization: gfhjui',
        //);
        //curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/plain', $headers)); 
        
        if(is_array($headers))
        {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        else
        {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array($headers));
        }
        
        //print_r(array($headers));
    }
    
    if($body != "")
    {
        //curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        //curl_setopt($curl, CURLOPT_POST, true);
        //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/plain')); 
        //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($body))); 
        //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($body))); 
        //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: 164')); 
        //curl_setopt($curl, CURLOPT_POSTFIELDS, array($body));
        curl_setopt_array($curl, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_POSTFIELDS     => $body
            //CURLOPT_POSTFIELDS => array('grant_type' => 'password','client_id' => '60ecb2f5f81b653076556fa7','client_secret' => 'j9SL2isjobfHOaAAcI35Va2zDzkJ6e2XVQ0hkfFug3Ht','username' => 'aionescu74@yahoo.com','password' => 'B10zbx1c.'),
          ));
        //$params = http_build_query(array($body));
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        //print('aaa');
    }
    
    
    if($token != "")
    {
        //print('aaa');
        $authorization = "Authorization: Bearer ".$token;
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
    }
    
    //curl_setopt($curl, CURLOPT_POST, 1); // Specify the request method as POST
    //curl_setopt($curl, CURLOPT_POSTFIELDS, $post); // Set the posted fields
    //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redire

    
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    //print($url);
    $result = curl_exec($curl);
    //print($result);
    
    //curl_setopt($curl, CURLOPT_VERBOSE, true);
    $information = curl_getinfo($curl);
    //$information = curl_getinfo($curl, CURLINFO_HEADER_OUT);
    //print("From json_functions, linia 149: ");
    //print_r($information);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    //print('HTTP_RESPONSE: ');
    //print($httpCode);
    //print('<br>');
    //$ssl = curl_getinfo($curl, CURLINFO_SSL_VERIFYRESULT);
    //print($ssl);
    
    //var_dump(curl_getinfo($curl, CURLINFO_HEADER_OUT));
    
    //curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2TLS);
    
    $responseContentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
    //print($responseContentType);
    //print("<br/><br/><br/>");
    //print_r($result);
    //
    //print("URL: " . $url . "<br/>");
    //print("ERROR: " . curl_errno($curl). " - " .curl_error($curl));

    curl_close($curl);
    return $result;
    
}



/////////////////////////////////////////////
//              CALL SEED API              //
/////////////////////////////////////////////
function CallSeedAPI($url, $tabel, $method, $id, $body, &$json)
{
/*
$headers = array(
    'Authorization: Basic '. base64_encode('admin:B10zbx1c.'),
);
 */
$headers = array(
            'Content-type: application/json',
            'Accept:application/json', 
            'Authorization: Basic YWRtaW46QjEwemJ4MWMu');   //contine base64_encode('admin:B10zbx1c.')

//$body       = "";
//$parameters = array('tabel' => 'bvb_companii');


$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, $url.'api/?tabel='.$tabel.'&id='.$id.'');
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_TIMEOUT, 30);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
//curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
//curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        
//curl_setopt($curl, CURLOPT_USERPWD, base64_encode('admin:B10zbx1c.'));
//$authorization = "Authorization: Bearer ".$token;
//curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
//curl_setopt($curl, CURLOPT_HTTPHEADER, array('tabel' , $tabel));
//curl_setopt($curl, CURLOPT_POST, true); 


$json = curl_exec($curl);
//print($response);
//$json_beautified = str_replace(array("{", "}", '","'), array("{<br />&nbsp;&nbsp;&nbsp;&nbsp;", "<br />}", '",<br />&nbsp;&nbsp;&nbsp;&nbsp;"'), $response);
//echo json_encode($response, JSON_PRETTY_PRINT);
//print($json_beautified);


//$information = curl_getinfo($curl, CURLINFO_HEADER_OUT);
//print_r($information);
//$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//print('HTTP_RESPONSE: ');
//print($httpCode);

curl_close($curl);
//return $json;

$arr = json_decode($json, true);
//print_r($arr);

$pointer = ".records";                            // returneaza Array
$arr = getPointerValue($arr, $pointer);
return $arr;
}



/**
 * Convert an array to XML
 * @param array $array
 * @param SimpleXMLElement $xml
 * 
 * POATE FI APELATA CAND UN REST INTOARCE JSON
 * SI IL PUTEM LUCRA CA XML
 */
function arrayToXml($array, &$xml)
{
    foreach ($array as $key => $value) {
        if(is_int($key)){
            $key = "e";
        }
        if(is_array($value)){
            $label = $xml->addChild($key);
            arrayToXml($value, $label);
        }
        else {
            $xml->addChild($key, $value);
        }
        
        //print($xml->asXML());
        //print("\n\r\n\r");
    }
    //return $xml;
}




//////////////////////////////////////////////////////
//          C. DACA ACCESAM UN XML                  //
//              (CA LA BNR):                        //
//////////////////////////////////////////////////////
//$xml = file_get_contents($url);
//print_r($xml);





//////////////////////////////////////////////////////
//           D. JSON VALUE BY POINTER:              //
//////////////////////////////////////////////////////
//           D.1. FUNCTIE RECURSIVA:                //
//           Trebuie precedata de json_decode
//////////////////////////////////////////////////////
//$pointer = ".i[indicator='I4'].val_den_indicator";
//$pointer = ".i[val_den_indicator='CHELTUIELI TOTALE'].val_indicator";
//$pointer = ".j[1].a";
//$pointer = ".an";
//$arr = json_decode($json, true);
//print_r($arr);

function getPointerValue($arr, $pointer, $curr_position = 1)
{
    //print_r($arr);
    //print($arr['an']);
    
    $elemente = explode( '.', $pointer );
    //print(sizeof($elemente));
    //print_r($elemente);
    //print($curr_position);
    //print(".");
    

    if($curr_position < sizeof($elemente)-1)
    {
        //print_r($arr);
        $curr_element = $elemente[$curr_position];
        //print($curr_position);
        //print($curr_element);
        
        
        $q = getCriteriuCautare($curr_element);
        //print_r($q);
        // verificam daca e lista:
        if($q['denumirea_listei'] === "")               // inseamna ca NU e lista
        {
            //print("VALOARE=");
            if($q['valoarea_cautata'] != "")            // nefiind lista, valoarea cautata este numele elementului
            {
                //print_r($arr[$q['valoarea_cautata']]);
                return getPointerValue($arr[$q['valoarea_cautata']], $pointer, $curr_position + 1);
            }
            else                                        // inseamna ca suntem la elemetul 0 si ne da eroare de index -> deci returnam intregul array
            {
                return getPointerValue($arr, $pointer, $curr_position + 1);
            }
        }
        else                                            // inseamna ca e o lista si cautam in ea
        {
            if($q['valoarea_cautata'] === "")           // lista nefiltrata
            {
                //print_r($arr);
                //print_r($arr[$q['denumirea_listei']]);
                return getPointerValue($arr[$q['denumirea_listei']], $pointer, $curr_position + 1);
            }
            else                                        // lista filtrata
            {
                if($q['campul_de_cautare'] != "")       // cautare normala: [key='value']
                {
                    //print_r($arr);
                    //print($q['campul_de_cautare'] . "=" . $q['valoarea_cautata']);
                    $key = array_search($q['valoarea_cautata'], array_column($arr[$q['denumirea_listei']], $q['campul_de_cautare']));
                    return getPointerValue($arr[$q['denumirea_listei']][$key], $pointer, $curr_position + 1);
                }
                else                                    // lista nefiltrata dupa nr de ordine: [0]
                {
                    return getPointerValue($arr[$q['denumirea_listei']][$q['valoarea_cautata']], $pointer, $curr_position + 1);
                    
                }
            }
        }
        //print("<br>");
        //nefiind ultimul element din lista, mergem mai departe
        //getPointerValue($arr, $pointer, $curr_position + 1);
        //return $curr_element;
    }
    //elseif($curr_position === sizeof($elemente)-1)
    else
    {
        $curr_element = $elemente[$curr_position];
        //print($curr_position);
        //print($curr_element);
        
        $q = getCriteriuCautare($curr_element);
        //print_r($q);
        // verificam daca e lista:
        if($q['denumirea_listei'] === "")                       // inseamna ca NU e lista
        {
            if($q['valoarea_cautata'] != "")                    // nefiind lista, valoarea cautata este numele elementului  
            {
                //print(" => VALOAREA CAUTATA: " . $q['valoarea_cautata']);
                //print_r($arr[$q['valoarea_cautata']]);
                //print_r($arr);
                
                return($arr[$q['valoarea_cautata']]);
            }
            //print_r($arr[$q['valoarea_cautata']]);
            //print($q['valoarea_cautata']);
        }
        else                                                    // inseamna ca e o lista
        {
            //print("TBD");
            if($q['valoarea_cautata'] != "")                    // preluam sub-array-ul dupa valoarea cautara
            {
                //print(" => VALOAREA CAUTATA: " . $q['valoarea_cautata']);
                //print_r($arr[$q['valoarea_cautata']]);
                //print_r($arr);
                
                return($arr[$q['denumirea_listei']][$q['valoarea_cautata']]);
            }
            else                                                // lista nefiltrata dupa nr de ordine: ex: [0]
            {
                //print_r($arr);
                return($arr[$q['denumirea_listei']]);
                //print("<br>");
            }
            //print_r($arr);
            //print($q['campul_de_cautare'] . "=" . $q['valoarea_cautata']);
            //$key = array_search($q['valoarea_cautata'], array_column($arr[$q['denumirea_listei']], $q['campul_de_cautare']));
            //return $arr[$q['denumirea_listei']][$q['valoarea_cautata']];
            
        }
        
    }
    
}


function getCriteriuCautare($element)
{
    $cautare = array("denumirea_listei" => "", "campul_de_cautare" => "", "valoarea_cautata" => "");
    
    $pos1 = strpos($element, "[");
    //print($pos1);
    
    if($pos1 != 0)          // inseamna ca e o lista
    {
        $denumirea_listei = trim(substr($element, 0, $pos1), " \n\r\t\v\0\," );
        //print ($denumirea_listei);
        //print("<br/>");


        $str1 = substr($element, $pos1 + 1, strlen($element));
        //print ($str1);

        $pos2 = strpos($str1, "]");
        $src = trim(substr($str1, 0, $pos2), " \n\r\t\v\0\," );
        //print($src);
        //print("<br/>");

        if($src != "")
        {
            $pos_egalului = strpos($src, "=");
            if($pos_egalului != "")
            {
                $campul_de_cautare = trim(substr($src, 0, $pos_egalului), " \n\r\t\v\0\," );
                //print ($campul_de_cautare);
                //print("<br/>");

                $valoarea_cautata = trim(substr($src, $pos_egalului + 1, strlen($src)), " \n\r\t\v\0\,\'" );
                //print ($valoarea_cautata);
                //print("<br/>");

                //$cautare = array("denumirea_listei" => "", "campul_de_cautare" => "", "valoarea_cautata" => "");
                $cautare['denumirea_listei']  = $denumirea_listei;
                $cautare['campul_de_cautare'] = $campul_de_cautare;
                $cautare['valoarea_cautata']  = $valoarea_cautata;
            }
            else
            {
                $cautare['campul_de_cautare'] = "";
                $cautare['denumirea_listei']  = $denumirea_listei;
                $cautare['valoarea_cautata']  = trim($src, " \n\r\t\v\0\," );
                
                //print(trim($src, " \n\r\t\v\0\," ));
            }
        }
        else
        {
            $cautare['denumirea_listei']  = substr($element, 0, $pos_egalului);
            $cautare['campul_de_cautare'] = "";
            $cautare['valoarea_cautata']  = "";
        }
        //print_r($cautare);
        //print($cautare['denumirea_listei'] . "+" . $cautare['campul_de_cautare']);
    }
    else
    {
        //print("aaa");
        $cautare['denumirea_listei']  = "";
        $cautare['campul_de_cautare'] = "";
        $cautare['valoarea_cautata']  = $element;
    }
    
    return($cautare);
}