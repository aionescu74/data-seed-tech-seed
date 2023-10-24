<?php
//require_once("./messaging.inc");  TREBUIE MUTAT IN FISIERUL UNDE ESTE NEVOIE ALTFEL NU MERGE CRON

function execRestPipeStep($conn, $rest_pipe_code, $rest_pipe_step_code, $token, &$responseContentType, &$httpCode)
{
    //testare
    //$query = "INSERT INTO seed_jobs_log VALUES (default, 'execRestPipeStep', now(), 'OK', '1234');";
    //$result = $conn -> query($query);
    
    $query = "SELECT * 
                FROM seed_http_rest_pipe_steps
                WHERE rest_pipe_code = '".$rest_pipe_code."'
                AND rest_pipe_step_code = '".$rest_pipe_step_code."'";
    $result   = $conn -> query($query);
    $row      = $result -> fetch_object();
    $url      = $row->source_url;
    $method   = $row->method;
    $headers  = $row->headers;
    $body     = $row->body;

    $query1 = "SELECT param_key as k, default_value as v
                FROM seed_http_rest_pipe_step_params
                WHERE rest_pipe_step_code = '".$rest_pipe_step_code."'";
    /*
    $query1 = "SELECT param_key as k, default_value as v 
                FROM seed_http_rest_pipe_steps
                INNER JOIN seed_http_rest_pipe_step_params ON seed_http_rest_pipe_steps
                WHERE rest_pipe_code = '".$rest_pipe_code."'
                AND rest_pipe_step_code = '".$rest_pipe_step_code."'";
     * 
     */
    $result1 = $conn -> query($query1);
    //$row1 = $result1 -> fetch_object();
    $arr = array();
    while($row1 = $result1 -> fetch_object())
    {
        $k = $row1->k;
        $v = $row1->v;
        $arr[$k] = $v;
    }
    //print_r($arr);
    //$arr = array('device_id' => '70:ee:50:65:48:24', 'get_favorites' => 'false');
    //$arr = array('an' => '2020', 'cui' => '361579');      //BRD
    
    

    $jsonORxml = CallAPI($method, $url, $arr, $headers, $body, $token, $responseContentType, $httpCode);
    //$jsonORxml = CallAPI($method, $url, $arr);
    //print_r($jsonORxml);
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    // ADD HERE LOGGING FOR STEP EXECUTION
    //  -- IN THIS MOMENT WE DON'T SAVE JSON/XML RESPONSE --
    // insert into seed_rest_pipe_step_history...
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    
    return($jsonORxml);
}



function execRestPipe($conn, $rest_pipe_code, $token, &$responseContentType)
{
    //print_r($key_value);
    //print($url);
    //print($headers);
    //print($body);
    //print("Token: " . $token);
    //print("<br/><br/>");
    
    //testare
    //$query = "INSERT INTO seed_jobs_log VALUES (default, 'execRestPipe', now(), 'OK', '".$rest_pipe_code."');";
    //$conn -> query($query);
    
    
    
    
    $query = "SELECT *
                FROM seed_http_rest_pipe_steps
                WHERE rest_pipe_code = '".$rest_pipe_code."'
                ORDER BY execution_order";
    //print($query);

    $result = $conn -> query($query);
    //$row = $result -> fetch_object();
    //$token      = "";
    
    
    
    //testare
    //$query = "INSERT INTO seed_jobs_log VALUES (default, 'execRestPipe', now(), 'OK', '1234');";
    //$conn -> query($query);
    
    //print('aa1');
    
    $httpCode   = "";
    $response   = "200";
    while($row = $result -> fetch_object())
    {
        //print('aa2');
        //testare
        //$query = "INSERT INTO seed_jobs_log VALUES (default, 'execRestPipe...', now(), 'OK', '".$row->rest_pipe_step_code."');";
        //$conn -> query($query);
        
        $rest_pipe_step_code = $row->rest_pipe_step_code;
        if($row->token != "" && $token != "")
        {
            $token = $row->token;
        }
        
        $jsonORxml = execRestPipeStep($conn, $rest_pipe_code, $rest_pipe_step_code, $token, $responseContentType, $httpCode);
        //print('aa3');
        
        //print($responseContentType);
        if($httpCode != $response)
        {
            $response = $httpCode;
        }
        //print("<br/><br/><br/>");

        if($responseContentType ==="text/xml; charset=utf-8")   // daca e XML
        {
            //print($jsonORxml);
            $xmlStr = str_replace('xmlns=', 'ns=', $jsonORxml);
            $xml = simplexml_load_string($xmlStr);
            //print_r($xml);

            //$v = (string) current($xml->xpath("/DataSet/Body/Cube/Rate[@currency='EUR']"));

            //print_r($v);
        }
        else                                                        // daca e JSON
        {
            $arr = json_decode($jsonORxml, true);
            //print_r($arr);
        }


        ///////////////////////////////////////////////////////////////////
        //              TBD: neterminata pe tokenizer:
        ///////////////////////////////////////////////////////////////////
        if($row->tokenizer == 1 && $row->token == "")
        {
            //TBD: varianta cu XML nu e tratata
            $pointer = ".access_token";
            $token = getPointerValue($arr, $pointer);
            //print("<br>Token = " . $token);
        }
        else
        {
            $query_tabel = "SELECT DISTINCT tabel
                    FROM seed_http_rest_pipe_step_mapping
                    WHERE rest_pipe_step_code = '".$rest_pipe_step_code."'";
            $result_tabel = $conn -> query($query_tabel);

            while($row_tabel = $result_tabel -> fetch_object())  // daca avem mai multe tabele de inserare, iteram prin ele:
            {
                $fields = "";
                $values = "";
                $i = 1;

                $query1 = "SELECT *
                    FROM seed_http_rest_pipe_step_mapping
                    WHERE rest_pipe_step_code = '".$rest_pipe_step_code."'
                    AND tabel = '".$row_tabel->tabel."'";
                //print($query1);
                $result1 = $conn -> query($query1);

                while($row1 = $result1 -> fetch_object())
                {
                    $pointer = $row1->pointer;
                    if($responseContentType ==="text/xml; charset=utf-8")   // daca e XML
                    {
                        $xxx = (string) current($xml->xpath($pointer));
                    }
                    else                                                    // daca e JSON
                    {
                        $xxx = getPointerValue($arr, $pointer);
                    }
                    if($i < $result1->num_rows)
                    {   
                        $fields = $fields . $row1->camp . ", ";
                        $values = $values . "'" . $xxx . "', ";
                    }
                    else
                    {
                        $fields = $fields . $row1->camp;
                        $values = $values . "'" . $xxx . "'";
                    }
                    $i++;
                }

                $insert = "insert into " . $row_tabel->tabel . " (".$fields.") values (".$values.");";
                //print($insert);
                $conn -> query($insert);
                
                
                
                //testare
                //$query = "INSERT INTO seed_jobs_log VALUES (default, 'before_messaging', now(), 'OK', '1234');";
                //$conn -> query($query);
                //////////////////////////////////////////////////////////////////////////////////////////////////
                //                           Messaging                                                          //
                //                           DEOCAMDATA NU SE TRIMIT EMAILURI LA EXECUTAREA UNEUI REST PIPE     //
                //////////////////////////////////////////////////////////////////////////////////////////////////
                //$body = "{\"type\": \"note\", \"title\": \"REST Pipe step run: ". $rest_pipe_step_code ."\", \"body\": \"". $values . "\"}";
                //print($body);
                //$r = sendPushBullet($body);
                //print($r);
                
            }
        }
    }
    
    //testare
    //$query = "INSERT INTO seed_jobs_log VALUES (default, 'execRestPipe', now(), 'OK', '1234');";
    //$conn -> query($query);
    
    return($response);
}

