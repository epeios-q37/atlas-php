<?php
/*
    Copyright (C) 2018 Claude SIMON (http://q37.info/contact/).

    This file is part of XDHq.

    XDHq is free software: you can redistribute it and/or
    modify it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    XDHq is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
    Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with XDHq If not, see <http://www.gnu.org/licenses/>.
*/

function getAtlas() {
 if (getenv("EPEIOS_SRC") === false) {
  $atlasPath = "phar://Atlas.phar/";
 } else {
  switch (strtoupper(substr(php_uname('s'), 0, 3))) {
  case "WIN":
   $epeiosPath = "h:\\hg\\epeios\\";
   break;
  case "LIN":
   $epeiosPath = "/home/csimon/hg/epeios/";
   break;
  case "DAR":
   $epeiosPath = "/Users/csimon/hg/epeios/";
   break;
  default:
   echo "Unknown OS !!!\n";
   break;
  }

  $atlasPath = $epeiosPath . "tools/xdhq/Atlas/ZND/";
 }

 require $atlasPath . "Atlas.php";
}

getAtlas();

function readAsset($path) {
 // Due to multi-threading constraints, a global variable can not be used here.
 return Atlas::readAsset($path, "blank");
}

class Blank extends Threaded {
 public function handle($dom, $action, $id) {
  switch ($action) {
  case "":
   $dom->setLayout("", readAsset("Main.html"));
   $dom->addClass("Input", "hidden");
   break;
  case "Submit":
   $dom->setContent("Pattern", strtoupper($dom->getContent("Pattern")));
   break;
  case "HideInput":
   $dom->addClass("Input", "hidden");
   break;
  case "ShowInput":
   $dom->removeClass("Input", "hidden");
   $dom->focus("Pattern");
   break;
  default:
   die("???");
   break;
  }
 }
}

function blank() {
 return new Blank();
}

Atlas::launch( 'blank', readAsset("Head.html"), null, "blank");
?>
