<!DOCTYPE html>
<html>
 
<?php

  // include steam key
  include("config.php"); 
 
  global $KEY;
  global $STEAMID;

  $url = "https://api.steampowered.com/ISteamUser/GetFriendList/v0001/?key=" . $KEY . "&steamid=" . $STEAMID; 
  $xml = file_get_contents($url);

  $obj = json_decode($xml, true);

  $friends[] = array();
  $master[]  = array();

  foreach($obj['friendslist']['friends'] as $friend)  {
    array_push($friends, $friend['steamid']);
  }

  // use array_push to add the SteamIDs of players that aren't on my friendslist
  array_push($friends, "561198016305721");
  array_push($friends, "561198029258748");
  array_push($friends, "561198043194261");
  array_push($friends, "561198056522377");
  array_push($friends, "561198057963176");
  array_push($friends, "561198059025420");
  array_push($friends, "561198103682933");
  array_push($friends, "561198234856584");
  array_push($friends, "561198262293303");
  array_push($friends, "561198281879760");
  array_push($friends, "561198326431359");
  array_push($friends, "561198329986125");
  array_push($friends, "561198995963731");
  array_push($friends, "561198996550548");
  array_push($friends, "561199007281262");
  array_push($friends, "561199085632748");

  // Ensure there are no duplicates in the array
  $friends = array_unique($friends);

  if(count($friends) <= 100) {

    $ids = implode(",", $friends);

    $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0001/?key=" . $KEY . "&steamids=" . $ids;
    $xml = file_get_contents($url);
    $obj = json_decode($xml, true);
    $sub = $obj['response']['players']['player'];
    // Prepare $master with player objects
    for($i = 0; $i < count($sub); $i+=1)  {
      array_push($master, $obj['response']['players']['player'][$i]);
    }
  } else {
    $start = 0;
    while($start != count($friends) - 1)  {                // When $start is the size of the array (minus 1) it means we are done
      $sub = array_slice($friends, $start, 100, false);    // Take 100 elements from $friends[$start]
      $start += count($sub);                               // Increment $start by number of elements taken (will be 100 until the last one)
      $ids = implode(",", $sub);
 
      $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0001/?key=" . $KEY . "&steamids=" . $ids;
      $xml = file_get_contents($url);
      $obj = json_decode($xml, true);
      $sub = $obj['response']['players']['player'];
      // Prepare $master with player objects
      for($i = 0; $i < count($sub); $i+=1)  {
        array_push($master, $obj['response']['players']['player'][$i]);
      } 
    }

  }

  $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0001/?key=" . $KEY . "&steamids=" . $ids;
  $xml = file_get_contents($url);
  $obj = json_decode($xml, true);
  //var_dump($obj);

  $sub = $obj['response']['players']['player'];

  for($i = 0; $i < count($sub); $i+=1)  {
    $ingame += $sub[$i]["gameid"] == "570940" ? 1 : 0;
  }

  ?>

  <head>
    <meta charset="UTF-8" name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
    <title>DSR-Community</title>
    <meta content="DSR-Community" property="go:title" />
<?php
    echo "    <meta content=\"Monitoring " . count($master) . " Steam Profiles\n" . $ingame . " playing DSR now!\" property=\"og:description\" />\n";
?>
    <meta content="http://www.xvk3.net/glue.php" property="og:url" />
    <meta content="#99FFCA" name="theme-color" />
    <style>

    * {
      box-sizing: border-box;
      margin: 0 auto;
    }

    a {
      text-decoration: none;
    }

    .container {
      display: flex;
      flex-wrap: wrap;
      width: 950px;
    }

    .container > .reg,
    .container > .dsr {
      margin: 5px;
      padding: 10px;
      background: #242322;
      border-radius: 5px;
      flex: 0 46%;
      box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
    }

    .container > div h1 {
      margin: 5px;
      color: #eba434;
      font-size: 1em;
      text-shadow: 4px 2px rgba(0, 0, 0, 0.75);
    }

    .container > div img {
      float: left;
      margin-right: 12px;
      border-radius: 15px;
    }

    .dsr img {
      /*border: 3px solid rgba(94, 214, 96, 0.5); */
      box-shadow: rgba(94, 214, 96, 0.5) 0px 0px 0px 3px,
      rgba(6, 24, 44, 0.65) 0px 4px 6px -1px,
      rgba(255, 255, 255, 0.08) 0px 1px 0px inset;
    }

    .reg p,
    .dsr p {
      color: #fff;
      margin: 5px;
    }

    .reg a,
    .dsr a {
      font-size: 1.25em;
      top: 0;
      color: #999;
      text-shadow: 4px 2px rgba(0, 0, 0, 0.25);
      text-transform: uppercase;
      font-weight: bold;
    }

    .dsr a{
      color: rgb(94, 214, 96, 0.5);
    }

    </style>
  </head>
  <body>
    <div class="container">
<?php

  // do a first pass over the array and print those who are ingame
  for($i = 0; $i < count($sub); $i+=1)  {
    $player = $obj['response']['players']['player'][$i];
    if($player['gameid'] == "570940") {
      display_dsr($player);
    }
  }
 
  // second pass for everyone else 
  for($i = 0; $i < count($sub); $i+=1)  {
    $player = $obj['response']['players']['player'][$i];
    if($player['gameid'] != "570940") {
      display($player);
    }
  }

  function display_dsr($player) {
    echo "      <div class=\"dsr\">\n";
    echo "        <img src=\"" . $player['avatarfull'] . "\" alt=\"" . $player['personaname'] . "'s Avatar\" width=\"100\" height=\"100\">\n";
    echo "        <a href=\"" . $player['profileurl'] . "\">" . $player['personaname'] . "</a>\n";
    echo "        <h1>" . $player['gameextrainfo'] . "</h1>\n";
    echo "        <p> Last Online: " . date('Y-m-d h:i:s',$player['lastlogoff']) . "</p>\n";
    echo "      </div>\n";
  }

  function display($player) {
    echo "      <div class=\"reg\">\n";
    echo "        <img src=\"" . $player['avatarfull'] . "\" alt=\"" . $player['personaname'] . "'s Avatar\" width=\"100\" height=\"100\">\n";
    echo "        <a href=\"" . $player['profileurl'] . "\">" . $player['personaname'] . "</a>\n";
    echo "        <h1>" . $player['gameextrainfo'] . "</h1>\n";
    echo "        <p> Last Online: " . date('Y-m-d h:i:s',$player['lastlogoff']) . "</p>\n";
    echo "      </div>\n";
  }
?>
    </div>
  </body>
</html>
