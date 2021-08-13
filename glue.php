<!DOCTYPE html>
<html>
  
  <head>
    <meta charset="UTF-8" name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
    <title>Community Glue</title>
  </head>
  <body>
  <?php

  // include steam key
  include("config.php"); 
 
  global $KEY;
  global $STEAMID;

  $url = "https://api.steampowered.com/ISteamUser/GetFriendList/v0001/?key=" . $KEY . "&steamid=" . $STEAMID; 
  $xml = file_get_contents($url);

  $obj = json_decode($xml, true);

  $friends[] = array();

  foreach($obj['friendslist']['friends'] as $friend)  {
    array_push($friends, $friend['steamid']);
  }

  // use array_push to add the SteamIDs of players that aren't on my friendslist
  //array_push($friends, "SteamID of somebody");

  $ids = "";

  if(count($friends) > 100) {
    echo "too many people, dm Mich\n";
    die();
    // ugh gonna have to split it up into a series of 100 steamids
  } else {
    $ids = implode(",", $friends);
    //echo $ids;
  }

  $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0001/?key=" . $KEY . "&steamids=" . $ids;
  $xml = file_get_contents($url);
  $obj = json_decode($xml, true);
  var_dump($obj);

  $sub = $obj['response']['players']['player'];
  //echo count($sub);

  // container div here?

  // do a first pass over the array and print those who are ingame
  for($i = 0; $i < count($sub); $i+=1)  {
    $player = $obj['response']['players']['player'][$i];
    if($player['gameid'] == "570940") {
      display_dsr($player);
    }
  }
  
  for($i = 0; $i < count($sub); $i+=1)  {
    $player = $obj['response']['players']['player'][$i];
    if($player['gameid'] != "570940") {
      display($player);
    }
  }

  function display_dsr($player) {
    echo "<div width=\"300px\">";
    echo "<img src=\"" . $player['avatarfull'] . "\" alt=\"" . $player['personaname'] . "'s Avatar\" width=\"100\" height=\"100\">";
    echo "<a href=\"" . $player['profileurl'] . "\">" . $player['personaname'] . "</a>";
    echo "<h1>" . $player['gameextrainfo'] . "</h1>";
    echo "<p> Last Online: " . date('Y-m-d h:i:s',$player['lastlogoff']) . "</p>";
    echo "</div>\n";
  }

  function display($player) {
    echo "<div width=\"300px\">";
    echo "<img src=\"" . $player['avatarfull'] . "\" alt=\"" . $player['personaname'] . "'s Avatar\" width=\"100\" height=\"100\">";
    echo "<a href=\"" . $player['profileurl'] . "\">" . $player['personaname'] . "</a>";
    echo "<h1>" . $player['gameextrainfo'] . "</h1>";
    echo "<p> Last Online: " . date('Y-m-d h:i:s',$player['lastlogoff']) . "</p>";
    echo "</div>\n";

  }

/* 

  old way

  $master_array[] = array();

  foreach($obj['friendslist']['friends'] as $elem)  {

    $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0001/?key=" . $KEY . "&steamids=" . $elem['steamid'];
    $xml = file_get_contents($url);
    $obj = json_decode($xml, true);
    $sub = $obj['response']['players']['player'][0];
    array_push($master_array, $sub['steamid'], $sub);
  }
    $arr[] = array();
    array_push($arr, "steamid", $sub['steamid']);
    array_push($arr, "personaname", $sub['personaname']);
    array_push($arr, "profileurl", $sub['profileurl']);
    if(array_key_exists("gameextrainfo", $sub) && !empty($sub['gameextrainfo']))  {
      array_push($arr, "gameextrainfo", $sub['gameextrainfo']);
    }
    array_push($arr, "lastlogoff", $sub['lastlogoff']);
    array_push($arr, "profilestate", $sub['profilestate']);

    array_push($master_array, $sub['steamid'], $arr);
    echo $sub['steamid'] . "\n";
  }

  foreach($master_array as $friend) {
    echo $friend['personaname'] . "\n";

  }

    echo "<div>";
    echo "<img src=\"" . $sub['avatarfull'] . "\" alt=\"" . $sub['personaname'] . "'s Avatar\" width=\"100\" height=\"100\">";
    echo "<a href=\"" . $sub['profileurl'] . "\">" . $sub['personaname'] . "</a>";
    echo "<h1>" . $sub['gameextrainfo'] . "</h1>";

    echo "<p> Last Online: " . date('Y-m-d h:i:s',$sub['lastlogoff']) . "</p>";
    
    echo "</div>\n";

    // 'avatarfull'
    // 'personaname'
    // 'profileurl'
    // 'gameextrainfo'
    // 'lastlogoff'


  }*/



  ?>

  </body>

</html>
