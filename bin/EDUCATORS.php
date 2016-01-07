<?php
// EDUCATORS.php
// James Staub
// Nashville Public Library
// Create MARC patron files for Limitless Libraries new educators
// To be called from EDUCATORS.exp
// PHP Script mostly cribbed from Mark Noble's work

// 20151118: adjust for incoming record format like
//	999999|25192999999999	LAST, FIRST|LAST, FIRST M|LAST, FIRST MIDDLE	999
// 20150804: adjustments for 2015-2016 school year
//	+ EXP DATE 080 = 07-31-2016
//	+ include HOLD LIBR in 085
//	+ MBLOCK 086 = -
//	+ remove MESSAGE 400 "This educator card is not yet ready for patron use..."
//	+ PIN 600 = 2015
// 20140910: first draft

//echo($argv[1]);

require_once 'File/MARC.php';
date_default_timezone_set('America/Chicago');

$date = date('Ymd', time() );
$libraryAddsFileName = "/home/limitless/data/EDUCATORS-";
if (isset($argv[1]) && $argv[1] == "manual") {
	$libraryAddsFileName .= "MANUAL-";
}
$libraryAddsFileName .= "ADD-" . $date;

$invalidBarcodeFile = "/home/limitless/data/invalid-barcodes.txt";
($invalidBarcodeFileHnd = fopen($invalidBarcodeFile, 'w')) or die ("$invalidBarcodeFile cannot be opened");

$numInvalidBarcodes = 0;
ini_set("auto_detect_line_endings", true);
$allPatrons = array();

$exportFhnd = fopen("$libraryAddsFileName.csv", 'r');
//Skip the first line
//$rawData = fgetcsv($exportFhnd, 0);
while (($rawData = fgetcsv($exportFhnd, 0, "\t")) !== FALSE) {
	$patronData = array(
		'educatorIDs' => $rawData[0] != 'NULL' ? preg_split("/\|/", $rawData[0]) : '',
		'educatorNames' => $rawData[1] != 'NULL' ? preg_split("/\|/", $rawData[1]) : '',
		'school' => trim($rawData[2] != 'NULL' ? $rawData[2] : ''),
		'overlay' => $rawData[3] != 'NULL' ? $rawData[3] : 'INSERT',
		'educatorNotes' => $rawData[4] != 'NULL' ? preg_split("/\|/", $rawData[4]) : ''
//		'birth_date' => trim($rawData[5] != 'NULL' ? $rawData[5] : '')
	);
	$patronData['030_BARCODE'] = array();
	foreach ($patronData['educatorIDs'] as $educatorID) {
		if (!is_numeric($educatorID)){
			fwrite($invalidBarcodeFileHnd, "Skipping patron {$patronData['educatorNames'][0]} because barcode is not numeric {$educatorID}\n");
			$numInvalidBarcodes++;
			continue;
		}
		$patronData['030_BARCODE'][] = $educatorID;
	}
	$patronData['080_EXP_DATE'] = "07-31-2016";
	$patronData['081_AGE_GRP'] = "a";
	$patronData['082_REG_LIB'] = "3";
	$patronData['084_PTYPE'] = "30";
	$patronData['085_HOLD_LIBR'] = "ps" . $patronData['school'];
	$patronData['086_MBLOCK'] = "-";
//	$patronData['089_BIRTH_DATE'] = $patronData['birth_date'];
	$patronData['089_BIRTH_DATE'] = "  -  -  ";
//	$patronData['100_NAME'] = ($patronData['name_middle'] != 'NULL' ? $patronData['name_last'] . ", " . $patronData['name_first'] . " " . $patronData['name_middle'] : $patronData['name_last'] . ", " . $patronData['name_first']);
	$patronData['100_NAME'] = array();
	foreach ($patronData['educatorNames'] as $educatorName) {
		$patronData['100_NAME'][] = $educatorName;
	}
//	$patronData['400_MESSAGE'] = "This educator card is not yet ready for patron use.  Please troubleshoot using the Limitless Libraries Back to School Guide posted on the NPL Intranet. See Resources > Limitless Libraries > LL Back to School Guide 2014 and/or Limitless Libraries 2014 Educator Card Policies.";
	$patronData['500_NOTE'][0] = "MNPS EDUCATOR " . $patronData['030_BARCODE'][0] . "; AUTOMATED " . $patronData['overlay'] . " " . date('m/d/Y', time() );
	foreach ($patronData['educatorNotes'] as $educatorNote) {
		$patronData['500_NOTE'][] = $educatorNote;
	}
	$patronData['600_PIN'] = "2015";

	if (array_key_exists($patronData['030_BARCODE'][0], $allPatrons)){
		//already have this patron, must be a secondary address
		echo("Warning duplicate employee id exists {$patronData['030_BARCODE'][0]}\n");
	}else{
		$allPatrons[] = $patronData;
	}

} //End reading raw data export
fclose($exportFhnd);

echo("Loaded " . count($allPatrons) . " from data extract, $numInvalidBarcodes Barcodes were removed because they were invalid.\n");

$patronsWritten = 0;
$finalFile = $libraryAddsFileName . ".mrc";
$finalFileHnd = fopen($finalFile, 'wb');
$numBlocked = 0;
foreach ($allPatrons as $patronData){

	$record = new File_MARC_Record();
	$leader = $record->getLeader();
	$leader[5] = 'n';
	$leader[6] = 'a';
	$leader[7] = 'm';
	$record->setLeader($leader);

	//030 = barcode
	foreach ($patronData['030_BARCODE'] as $patronBarcode) {
		$record->appendField(new File_MARC_Data_Field('030',array( new File_MARC_Subfield('a', $patronBarcode))));
	}
	//080 = expiration date
	$record->appendField(new File_MARC_Data_Field('080',array( new File_MARC_Subfield('a', $patronData['080_EXP_DATE']))));
	//081 = age group
	$record->appendField(new File_MARC_Data_Field('081',array( new File_MARC_Subfield('a', $patronData['081_AGE_GRP']))));
	//082 = pcode2 REG LIB : should be 3 MNPS for all educators initially enrolled via MNPS data
	$record->appendField(new File_MARC_Data_Field('082', array(new File_MARC_Subfield('a', $patronData['082_REG_LIB']))));
	//084 = patron type : should be 30 for all MNPS educators
	$record->appendField(new File_MARC_Data_Field('084',array( new File_MARC_Subfield('a', $patronData['084_PTYPE']))));
	//085 = hold pickup library = school
	// in 2014-2015, initially set to "ps   " because we do not trust MNPS data to correctly locate educators. When an educator activates her account, she selects her pickup location
	// in 2015-2016, we are going to trust the patrons to correct their pickup location if necessary
	$record->appendField(new File_MARC_Data_Field('085',array( new File_MARC_Subfield('a', $patronData['085_HOLD_LIBR']))));
	//086 = mblock
	$record->appendField(new File_MARC_Data_Field('086',array( new File_MARC_Subfield('a', $patronData['086_MBLOCK']))));
	//089 = birthdate
	$record->appendField(new File_MARC_Data_Field('089',array( new File_MARC_Subfield('a', $patronData['089_BIRTH_DATE']))));
	//100 = name
	foreach ($patronData['100_NAME'] as $patronName) {
		$record->appendField(new File_MARC_Data_Field('100',array( new File_MARC_Subfield('a', $patronName))));
	}
	//400 = message
	//$record->appendField(new File_MARC_Data_Field('400',array( new File_MARC_Subfield('a', $patronData['400_MESSAGE']))));
	//500 = note
	foreach ($patronData['500_NOTE'] as $patronNote) {
		$record->appendField(new File_MARC_Data_Field('500',array( new File_MARC_Subfield('a', $patronNote))));
	}
	//600 = pin
	$record->appendField(new File_MARC_Data_Field('600',array( new File_MARC_Subfield('a', $patronData['600_PIN']))));

	//Write the record to the file
	$rawRecord = $record->toRaw();
	fwrite($finalFileHnd, $rawRecord);

	$patronsWritten++;;
}
fclose($finalFileHnd);
fclose($invalidBarcodeFileHnd);
//End of processing
?>
