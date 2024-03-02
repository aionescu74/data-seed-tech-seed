<?php
session_start();
require_once("connection.inc");
require_once("enc_functions.inc");
//error_reporting(E_ALL);
//////////////////////////////////////////////////////////////////
//                          COUNTER       			//
// Se seteaza doua aspecte:
// - un cookie valabil un an ==> id-ul unic al vizitatorului
// - sesion_id care ar trebui sa expire la inchiderea browser-ului ==> returning visitor
//      ==> daca apare cu acelasi cookie inseamna ca e aceeasi vizita
//
// Doua vizite de la acelasi IP dar cu SessionID diferit inseamna ca browserul 
// nu accepta cookies si acel SessionID nu este salvat la client.
// 
// Salvarea faptului ca accepta cookies se salveaza in care este apelat din fiecare pafin pe load body prin AJAX.
// Pagina apelata prin AJAX este cookie_test.php.
// Vezi si scripturi.js.
// 
// Daca se salveaza doi visitors la refresh-ul unei pagini, trebuie verificat in
// edit-ul de pe simplliq daca nu apare un punct la inceputul fisierului PHP pe care facem refresh.
// 
// Utilitar: pagina cookie.php
//////////////////////////////////////////////////////////////////
//$ip             = @$_SERVER[REMOTE_ADDR];
$ip = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : '127.0.0.1';
//$referrer       = @$_SERVER[HTTP_REFERER];
$referrer = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '';
//$remote_host    = @$_SERVER[REMOTE_HOST];
$remote_host = isset($_SERVER["REMOTE_HOST"]) ? $_SERVER["REMOTE_HOST"] : '';


//$Page       = $PHP_SELF;
$Page         = $_SERVER['PHP_SELF'];
//print($Page);
$p              = explode("/", $Page);
//print_r($p);
$Page         = $p[sizeof($p)-1];
$Folder         = $p[sizeof($p)-2];
//print($Folder);

if($Folder == "seed")
    $Folder = "";

$Site = $_SERVER['HTTP_HOST'];

//////////////////////////////////////////////////////////////////
//                          VISITOR       			//
//////////////////////////////////////////////////////////////////
if(!isset($_COOKIE["visitor_id"]))  //in loc de un nume human-readable precum "dataseed", "solz", "visitor_id" etc. trebuie folosit un cod de genul "65e24529b898x"
{
    //print("Setting cookie...");
    //$token = openssl_random_pseudo_bytes(14);
    $token = uniqid();
    $query          = "INSERT INTO visitors (SessionID, FirstVisitTime, IP, Page, Referrer, RemoteHost, token) "
            . "VALUES ('".session_id()."', NOW(), '".$ip."', '".$Page."', '".$referrer."', '".$remote_host."', '".$token."');";
    //print($query);
    $conn -> query($query);
    $visitor_id     = $conn->insert_id;
    
    $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
    //print ($domain);

    setcookie("visitor_id", encode($visitor_id), time() + (365*24*60*60*1000), "/", $domain, false);  //in loc de un nume human-readable precum "dataseed", "solz", "visitor_id" etc. trebuie folosit un cod de genul "65e24529b898x"
    
    // aici se seteaza tokenul de validare a vizitatorului (daca modifica ceva manual nu ar trebui sa mearga):
    setcookie("token" . $visitor_id, $token, 0, "/", $domain, false);     // expires 0 means when the browser is closed
    
    //$_SESSION["visitor_id"] = $visitor_id;
}
else 
{
    $visitor_id = $_COOKIE["visitor_id"];
    $visitor_id = decode($visitor_id);
    //print($visitor_id);
    //print(session_id());
    
    $token = $_COOKIE["token" . $visitor_id];
    //$token = $_COOKIE["token"];
    print($token);
    print("<br>");
    
    $query = "SELECT SessionID, IP, token FROM visitors WHERE ID = '" . $visitor_id . "';";
    //print($query);
    $result = $conn -> query($query);
    
    if (mysqli_num_rows($result) === 0)
    {
         print("E11. Wrong visitor!");
         die;
    }
    else
    {
        if ($row = $result->fetch_object())
        {
            print($row->token);     //in loc de un nume human-readable precum "token", "solz" etc. trebuie folosit un cod de genul "z1e24529b3421a"
            print("<br>");

            if($row->token != $token)
            {
                print("E12. Unknown error!");
                die;
            }
        }
    }
    
    
    
    //PRELUNGIRE VALABILITATE:
    //setcookie("visitor_id", $visitor_id, time() + (365*24*60*60*1000), "/");
    
    //STERGERE COOKIE:
    //setcookie("visitor_id", $visitor_id, time() - (365*24*60*60*1000), "/"); // sterge cookie
}

//print(time() - (365*24*60*60*1000));

$query          = "INSERT INTO counter (VisitorID, SessionID, VisitTime, IP, Page, Referrer, RemoteHost) "
        . "VALUES (".$visitor_id.", '".session_id()."', NOW(), '".$ip."', '".$Page."', '".$referrer."', '".$remote_host."')";

$conn -> query($query);
$counter_id     = $conn->insert_id;


$enc = encode($visitor_id);
$dec = decode($enc);
print("VisitorID: ". $visitor_id." -> ".$enc." -> ".$dec);
