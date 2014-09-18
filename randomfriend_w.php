<?php

//appid chooser 
if( $_SERVER['REMOTE_ADDR']=="127.0.0.1")
{
   //bonotest
   $appid="YOUR-LOCAL-TEST-APP-ID";
}
else
{
   $appid="YOUR-APP-ID";
}
?>
<html>
<head>
<title>Facebook JS: Random Friend</title>
<link rel="stylesheet" type="text/css" href="style.css" />
//space for google analytics
</head>
<body>
<span><button onclick="loginlogin();">Login Facebook</button></span>
<div id="fb-root"></div>
<!--<span class="alert">Potrebbe essere necessario sbloccare i pop up per permettere il login via Facebook</span>-->
<div id="form">
<select id="sel" onchange="changesel();">
  <option value="all">All</option>
  <option value="male">M</option>
  <option value="female">F</option>
</select>
<input type="button" value="Random Friend" onclick="rf();"/>
</div>

<div id="data">Loading <img src="./loader.gif" /></div>
<script>
   var max;
   var dt;
   var s;
   s="all";
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : <?php echo $appid;?>,                 // App ID from the app dashboard
      status     : true,                                 // Check Facebook Login status
      xfbml      : true                                  // Look for social plugins on the page
    });
   
   // Additional initialization code such as adding Event Listeners goes here
   //
   FB.getLoginStatus(function(response) {
  if (response.status === 'connected') {
    // the user is logged in and has authenticated your
    // app, and response.authResponse supplies
    // the user's ID, a valid access token, a signed
    // request, and the time the access token 
    // and signed request each expire
    var uid = response.authResponse.userID;
    var accessToken = response.authResponse.accessToken;
    //alert("loggato! uid:"+uid);
  } else if (response.status === 'not_authorized') {
    // the user is logged in to Facebook, 
    // but has not authenticated your app
    //alert("non autorizzato!");
  } else {
    // the user isn't logged in to Facebook.
    //alert("utente non loggato");
  }
 });
 //
   
  // loginlogin();
   

  };

  // Load the SDK asynchronously
  (function(){
     // If we've already installed the SDK, we're done
     if (document.getElementById('facebook-jssdk')) {return;}

     // Get the first script element, which we'll use to find the parent node
     var firstScriptElement = document.getElementsByTagName('script')[0];

     // Create a new script element and set its id
     var facebookJS = document.createElement('script'); 
     facebookJS.id = 'facebook-jssdk';

     // Set the new script's source to the source of the Facebook JS SDK
     facebookJS.src = '//connect.facebook.net/en_US/all.js';

     // Insert the Facebook JS SDK into the DOM
     firstScriptElement.parentNode.insertBefore(facebookJS, firstScriptElement);
   }());


 function randfriend(max)
 {
    var min=0;
    var est = Math.floor(Math.random() * (max - min + 1)) + min;
    //alert("s="+s);
    if(s!="all"){
        //debug: // alert(dt[est].sex + "=="+s);
         while(dt[est].sex!=s){
         est = Math.floor(Math.random() * (max - min + 1)) + min;
      }
   }
   return est;
 }
 
 function rf(){
      var num = randfriend(max);
      <?php /*in futuro, forse : implementare preload immagini 10 profili successivi || ajax load */ ?>
      document.getElementById("data").innerHTML=
      "<br /><a target=\"_new\" href=\"https://facebook.com/"+dt[num].uid+"\">"
      +"<img  src=\""+dt[num].pic+"\">"
      +"<br />"+dt[num].name+"</a>";
      
      //+"<img height=\"50px\" src=\"https://graph.facebook.com/"+dt[num].uid+"/picture\">"
     
    }
    
 function changesel(){
      if(document.getElementById("sel").value=="all"){
         s="all";
      }
      else if(document.getElementById("sel").value=="female"){
         s="female";
      }
      else if(document.getElementById("sel").value=="male"){
         s="male";
      }
      else{
         //non dovremmo mai arrivare qua
      }
    }
    
    function loginlogin(){
    
    FB.login(function(response) {
   if (response.authResponse) {
     console.log('Welcome!  Fetching your information.... ');
     FB.api('/fql',
            { q:{
               "query1":"SELECT uid2 FROM friend WHERE uid1 = me()",
               "query2":"SELECT uid, name, sex, pic FROM user WHERE uid IN (SELECT uid2 FROM #query1)"} },
            function(response) {
       //console.log('Good to see you, ' + response.name + '.');
           console.log(response);
           dt = response.data[1].fql_result_set;
           console.log(dt);
       max=dt.length;
      /* var n=randfriend(max);*/
       //alert(n);
      rf();
       
       
     });
   } else {
     console.log('User cancelled login or did not fully authorize.');
   }
 });
}//fine loginlogin

</script>
<br>
<br>
<br>
//spazio per banner
</body>
</html>
