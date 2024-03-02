function start(x)
{
    if (navigator.cookieEnabled)
    {
        //alert('cookies enabled');
        loadXMLDoc(x, 1);
        cookies_agreement_check();
    }
    else
    {
        //alert('cookies NOT enabled');
        loadXMLDoc(x, 0);
    }
}

function loadXMLDoc(x, ok) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      //document.getElementById("demo").innerHTML = this.responseText;
    }
  };
  //xhttp.open("GET", "cookie_test.php", true);
  //var x = document.cookie;
  //var x = getCookie("solz");
  //var x = <?php print($visitor_id); ?>;
  xhttp.open("GET", "cookie_test.php?cookie_ok="+ok+"&x="+x, true);     //aici se salveaza bifa de cookie ok in baza de date
  xhttp.send();
}


function cookies_agreement_check()
{
    if(getCookie('cookies_ok') === "")
    {
        document.getElementById('cookie-banner').style.display = 'inline';
    }
    else
    {
        document.getElementById('cookie-banner').style.display = 'none';
    }
}

function cookies_agreement_set_ok()
{
    setCookie('cookies_ok', 'true', 365);
    document.getElementById('cookie-banner').style.display = 'none';
}


function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}


