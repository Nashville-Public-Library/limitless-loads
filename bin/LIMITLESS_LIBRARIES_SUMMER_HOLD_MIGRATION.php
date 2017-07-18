<?php
/**
 * Description goes here
 *
 * @category Nashville Limitless Libraries
 * @author Mark Noble <mark@marmot.org>
 * @author James Staub <james.staub@nashville.gov>
 * Date: 20170503
  */
date_default_timezone_set('America/Chicago');

$aPickups = array(
	"Public School-Margaret Allen Middle"	=>	"do",
	"Public School-Amqui Elementary"	=>	"ma",
	"Public School-Antioch High"	=>	"se",
	"Public School-Antioch Middle"	=>	"se",
	"Public School-Antioch Middle Sch"	=>	"se",
	"Public School-Jere Baxter Middle"	=>	"ma",
	"Public School-Lakeview Elementary"	=>	"se",
	"Public School-Bellevue Middle"	=>	"bl",
	"Public School-Bellevue Middle Sc"	=>	"bl",
	"Public School-Bellshire Elementary"	=>	"ma",
	"Public School-Nashville Big Picture High-delivery via Martin"	=>	"rp",
	"Public School-Norman Binkley Elementary"	=>	"ep",
	"Public School-Norman Binkley Ele"	=>	"ep",
	"Public School-Davis Early Learning Center"	=>	"bx",
	"Public School-Buena Vista Elementary"	=>	"mn",
	"Public School-Caldwell Elementary"	=>	"mn",
	"Public School-Cameron College Prep"	=>	"pr",
	"Public School-Cane Ridge High"	=>	"se",
	"Public School-Cane Ridge Elementary"	=>	"se",
	"Public School-Carter Lawrence Elementary"	=>	"eh",
	"Public School-Casa Azafran Early Learning Center"	=>	"tl",
	"Public School-Chadwell Elementary"	=>	"ma",
	"Public School-Charlotte Park Elementary"	=>	"rp",
	"Public School-Academy at Old Cockrill-delivery via Martin Ce"	=>	"rp",
	"Public School-Cockrill Elementary"	=>	"rp",
	"Public School-Cole Elementary"	=>	"ep",
	"Public School-Cole Elementary Sc"	=>	"ep",
	"Public School-Hattie Cotton Elementary"	=>	"ea",
	"Public School-Crieve Hall Elementary"	=>	"ep",
	"Public School-Croft Middle"	=>	"ep",
	"Public School-Cumberland Elementary"	=>	"bx",
	"Public School-Nashville School of the Arts"	=>	"mn",
	"Public School-Dodson Elementary"	=>	"hm",
	"Public School-Donelson Middle"	=>	"do",
	"Public School-Donelson Middle Sc"	=>	"do",
	"Public School-Dupont Elementary"	=>	"oh",
	"Public School-Dupont Hadley Middle"	=>	"oh",
	"Public School-Dupont Tyler Middle"	=>	"hm",
	"Public School-Eakin Elementary"	=>	"gh",
	"Public School-John Early Middle"	=>	"lo",
	"Public School-East Nashville Magnet High"	=>	"ea",
	"Public School-East Nashville HS"	=>	"ea",
	"Public School-East Nashville Magnet Middle"	=>	"ea",
	"Public School-Fall-Hamilton Elementary"	=>	"eh",
	"Public School-JE Moss Elementary"	=>	"se",
	"Public School-Gateway Elementary"	=>	"go",
	"Public School-Glencliff Elementary"	=>	"tl",
	"Public School-Glencliff High"	=>	"tl",
	"Public School-Glendale Elementary"	=>	"gh",
	"Public School-Glengarry Elementary"	=>	"tl",
	"Public School-Glenn Elementary"	=>	"ea",
	"Public School-Glenview Elementary"	=>	"tl",
	"Public School-Glenview Elementar"	=>	"tl",
	"Public School-Goodlettsville Elementary"	=>	"go",
	"Public School-Goodlettsville Middle"	=>	"go",
	"Public School-Goodlettsville Mid"	=>	"go",
	"Public School-Gower Elementary"	=>	"bl",
	"Public School-Gra-Mar Middle"	=>	"in",
	"Public School-Granbery Elementary"	=>	"ep",
	"Public School-Alex Green Elementary"	=>	"bx",
	"Public School-Julia Green Elementary"	=>	"gh",
	"Public School-Harpeth Valley Elementary"	=>	"bl",
	"Public School-Harris Hillman School"	=>	"gh",
	"Public School-Haynes Middle"	=>	"mn",
	"Public School-Haywood Elementary"	=>	"ep",
	"Public School-Head Magnet Middle"	=>	"wp",
	"Public School-Hermitage Elementary"	=>	"hm",
	"Public School-Hermitage Elementa"	=>	"hm",
	"Public School-Cambridge Early Learning Center"	=>	"se",
	"Public School-Hickman Elementary"	=>	"hm",
	"Public School-Academy at Hickory Hollow-delivery via Martin"	=>	"se",
	"Public School-HG Hill Middle"	=>	"bl",
	"Public School-Hillsboro High"	=>	"gh",
	"Public School-Hillwood High"	=>	"bl",
	"Public School-Cora Howe School"	=>	"in",
	"Public School-Hume-Fogg Magnet High"	=>	"mn",
	"Public School-Hull-Jackson Montessori"	=>	"lo",
	"Public School-Hunter's Lane High"	=>	"ma",
	"Public School-Hunters Lane"	=>	"ma",
	"Public School-Inglewood Elementary"	=>	"in",
	"Public School-Andrew Jackson Elementary"	=>	"oh",
	"Public School-Andrew Jackson Ele"	=>	"oh",
	"Public School-Joelton Elementary"	=>	"bx",
	"Public School-Joelton Middle"	=>	"bx",
	"Public School-Jones Paideia Magnet Elementary"	=>	"lo",
	"Public School-Tom Joy Elementary"	=>	"in",
	"Public School-AZ Kelley Elementary"	=>	"se",
	"Public School-MLK Jr. Magnet"	=>	"wp",
	"Public School-Martin Luther"	=>	"wp",
	"Public School-JFK Middle"	=>	"se",
	"Public School-John F. Kennedy Mi"	=>	"se",
	"Public School-Robert E Lillard Elementary"	=>	"bx",
	"Public School-Robert E Lillard"	=>	"bx",
	"Public School-LEAD Academy High"	=>	"pr",
	"Public School-LEAD Academy"	=>	"pr",
	"Public School-Litton Middle"	=>	"in",
	"Public School-Lockeland Elementary"	=>	"ea",
	"Public School-Ruby Major Elementary"	=>	"hm",
	"Public School-McGavock Elementary"	=>	"do",
	"Public School-McGavock High"	=>	"do",
	"Public School-McKissack Middle"	=>	"ha",
	"Public School-McMurray Middle"	=>	"ep",
	"Public School-Madison Middle"	=>	"ma",
	"Public School-Madison Middle Sch"	=>	"ma",
	"Public School-Maplewood High"	=>	"ma",
	"Public School-Marshall Middle"	=>	"se",
	"Public School-Maxwell Elementary"	=>	"se",
	"Public School-Meigs Magnet Middle"	=>	"mn",
	"Public School-Dan Mills Elementary"	=>	"in",
	"Public School-Dan Mills Elementa"	=>	"in",
	"Public School-Middle College High-delivery via Martin Center"	=>	"rp",
	"Public School-JT Moore Middle"	=>	"gh",
	"Public School-Thomas A Edison Elementary"	=>	"se",
	"Public School-Mt. View Elementary"	=>	"se",
	"Public School-Apollo Middle"	=>	"se",
	"Public School-Murrell School-delivery via Martin Center"	=>	"eh",
	"Public School-Napier Elementary"	=>	"pr",
	"Public School-Neely's Bend Elementary"	=>	"ma",
	"Public School-Old Center Elementary"	=>	"go",
	"Public School-Oliver Middle"	=>	"ep",
	"Public School-Academy at Opry Mills-delivery via Martin Cent"	=>	"hm",
	"Public School-Overton High"	=>	"ep",
	"Public School-Paragon Mills Elementary"	=>	"ep",
	"Public School-Paragon Mills"	=>	"ep",
	"Public School-Park Avenue Elementary"	=>	"rp",
	"Public School-Pearl-Cohn High"	=>	"ha",
	"Public School-Pennington Elementary"	=>	"do",
	"Public School-Percy Priest Elementary"	=>	"gh",
	"Public School-Percy Priest Eleme"	=>	"gh",
	"Public School-Martin Professional Development Center"	=>	"mn",
	"Public School-Rosebank Elementary"	=>	"ea",
	"Public School-Rose Park Middle"	=>	"eh",
	"Public School-Ross Early Learning Center"	=>	"ea",
	"Public School-Shayne Elementary"	=>	"ep",
	"Public School-Shwab Elementary"	=>	"mn",
	"Public School-Smith Springs Elementary"	=>	"se",
	"Public School-Stanford Montessori Elementary"	=>	"do",
	"Public School-Stanford Montessor"	=>	"do",
	"Public School-Stratford STEM High"	=>	"in",
	"Public School-Stratton Elementary"	=>	"ma",
	"Public School-Sylvan Park Elementary"	=>	"rp",
	"Public School-Sylvan Park Paidei"	=>	"rp",
	"Public School-Sylvan Park  Elementary"	=>	"rp",
	"Public School-Tulip Grove Elementary"	=>	"hm",
	"Public School-Tulip Grove Elemen"	=>	"hm",
	"Public School-Tulip Grove  Elementary"	=>	"hm",
	"Public School-Tusculum Elementary"	=>	"ep",
	"Public School-Two Rivers Middle"	=>	"do",
	"Public School-Una Elementary"	=>	"se",
	"Public School-Warner Elementary"	=>	"ea",
	"Public School-Waverly-Belmont Elementary"	=>	"eh",
	"Public School-West End Middle"	=>	"gh",
	"Public School-Westmeade Elementary"	=>	"bl",
	"Public School-IT Creswell Arts Middle"	=>	"bx",
	"Public School-Robert Churchwell Elementary"	=>	"no",
	"Public School-Whites Creek High"	=>	"bx",
	"Public School-Whitsitt Elementary"	=>	"tl",
	"Public School-Wright Middle"	=>	"tl",
);

$holdsToFixFile = "../data/20170504B LL holds migration - patron barcodes.csv";
$holdsToFixFhnd = fopen($holdsToFixFile, 'r');
while (($rawData = fgetcsv($holdsToFixFhnd, 1000, ",", "'")) !== FALSE) {
	set_time_limit(600);
	$barcode = $rawData[1];
	$pin = $rawData[2];

	$patronHoldsRaw = file_get_contents('https://catalog.library.nashville.org/API/UserAPI?method=getPatronHolds&username=' . urlencode($barcode) . "&password=$pin");
	$patronHolds = json_decode($patronHoldsRaw);
	$numFailures = 0;
	$numUpdates = 0;
	foreach ($patronHolds->result->holds->unavailable as $hold) {
		if (substr($hold->location,0,14) == 'Public School-') {
			$return = file_get_contents('https://catalog.library.nashville.org/API/UserAPI?method=changeHoldPickUpLocation&username=' . urlencode($barcode) . "&password=$pin&holdId={$hold->cancelId}&location=" . $aPickups[$hold->location]);
			$response = json_decode($return);
			if (!$response->result->success){
				$numFailures++;
			}else{
				$numUpdates++;
//var_dump($return);
			}
		}
	}

foreach ($patronHolds->result->holds->available as $hold){
//		if ($hold->location != 'Lafayette Public Library'){
			echo ("$barcode,{$hold->itemId},{$hold->recordId}<br/>\r\n");
//		}
	}

	if ($numFailures > 0){
		echo("Failed to update location for patron $barcode<br/>\r\n");
	}elseif ($numUpdates > 0){
		echo("Updated $numUpdates holds for patron $barcode<br/>\r\n");
	}else{
		echo("Everything was fine for for patron $barcode<br/>\r\n");
	}
}
fclose($holdsToFixFhnd);
