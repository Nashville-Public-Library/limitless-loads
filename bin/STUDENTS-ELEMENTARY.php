<?php
// STUDENTS-ELEMENTARY.php
// James Staub
// Nashville Public Library
// Create MARC patron files for Limitless Libraries 
// To be called from STUDENTS-ELEMENTARY.exp
// PHP Script mostly cribbed from Mark Noble's work

// 20160819: corrected EXP_DATE to 07-31-2017
// 20160203: Eliminate patron note field "ATTENTION - PLEASE READ - P-type 33 accounts are to be used for Limitless Libraries school delivery only..."
// 20151215: adjust for incoming record format like
// Patron Barcodes	Patron Names	School Code	Insert vs. Overlay	Patron Notes	Patron Birth Date	Patron ZIP	Patron Guardian	Patron Address	Patron Phone	Patron Email
// 20151210: adjust for incoming record format like
//      999999|25192999999999   LAST, FIRST|LAST, FIRST M|LAST, FIRST MIDDLE    999
// 20150901 : add message field to all P TYPE 33 records
// 20150820 : changes to accommodate 2015-16 school year
// 20140910 : version 1

ini_set('memory_limit', '1024M');
require_once 'File/MARC.php';
date_default_timezone_set('America/Chicago');

$date = date('Ymd', time() );
$libraryAddsFileName = "/home/limitless/data/STUDENTS-ELEMENTARY-";
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
                'patronIDs' => $rawData[0] != 'NULL' ? preg_split("/\|/", $rawData[0]) : '',
                'patronNames' => $rawData[1] != 'NULL' ? preg_split("/\|/", $rawData[1]) : '',
                'school' => trim($rawData[2] != 'NULL' ? $rawData[2] : ''),
                'overlay' => $rawData[3] != 'NULL' ? $rawData[3] : 'INSERT',
                'patronNotes' => $rawData[4] != 'NULL' ? preg_split("/\|/", $rawData[4]) : '',
		'birth_date' => trim($rawData[5] != 'NULL' ? $rawData[5] : ''),
		'ZIP' => trim($rawData[6] != 'NULL' ? $rawData[6] : ''),
		'patronGuardians' => $rawData[7] != 'NULL' ? preg_split("/\|/", $rawData[7]) : '',
		'patronAddress' => trim($rawData[8] != 'NULL' ? $rawData[8] : ''),
		'patronPhone' => trim($rawData[9] != 'NULL' ? $rawData[9] : ''),
		'patronEmail' => trim($rawData[10] != 'NULL' ? $rawData[10] : '')
        );
        $patronData['030_BARCODE'] = array();
        foreach ($patronData['patronIDs'] as $patronID) {
                if (!is_numeric($patronID)){
                        fwrite($invalidBarcodeFileHnd, "Skipping patron {$patronData['patronNames'][0]} because barcode is not numeric {$patronID}\n");
                        $numInvalidBarcodes++;
                        continue;
                }
                $patronData['030_BARCODE'][] = $patronID;
        }
	$patronData['080_EXP_DATE'] = "07/31/2017";
	$patronData['081_AGE_GRP'] = "j"; // assume j, even though it is possible that some Elementary School students are Young Adults
	$patronData['082_REG_LIB'] = "3";
	$patronData['083_ZIP'] = (isset($patronData['ZIP']) ? (substr($patronData['ZIP'],0,2) == "37" ? substr($patronData['ZIP'],2,3) : "000") : "000");
	$patronData['084_PTYPE'] = "033";
	$patronData['085_HOLD_LIBR'] = "ps" . (isset($patronData['school']) ? $patronData['school'] : "");
	$patronData['086_MBLOCK'] = "-";
	$patronData['089_BIRTH_DATE'] = (isset($patronData['birth_date']) ? $patronData['birth_date'] : "  -  -  "); 
        $patronData['100_NAME'] = array();
        foreach ($patronData['patronNames'] as $patronName) {
                $patronData['100_NAME'][] = $patronName;
        }
	$patronData['110_GUARDIAN'] = array();
	foreach ($patronData['patronGuardians'] as $patronGuardian) {
		$patronData['110_GUARDIAN'][] = $patronGuardian;
	}
	$patronData['220_ADDRESS'] = (isset($patronData['patronAddress']) ? $patronData['patronAddress'] : "");

	if (isset($patronData['patronPhone'])) {
		$patronData['225_PHONE'] = preg_replace('/\D/','',$patronData['patronPhone']);
		$patronData['225_PHONE'] = (strlen($patronData['225_PHONE']) == 7 ? "615" . $patronData['225_PHONE'] : $patronData['225_PHONE']);
		$patronData['225_PHONE'] = preg_replace('/(\d{3})(\d{3})(\d{4})/','$1-$2-$3',$patronData['225_PHONE']);
	}
	foreach ($patronData['patronNotes'] as $patronNote) {
		$patronData['500_NOTE'][] = $patronNote;
	}
	//$patronData['500_NOTE'][] = 'ATTENTION - PLEASE READ - P-type 33 accounts are to be used for Limitless Libraries school delivery only.  Do not attach a 25192 card to this account.  Do not check out items on this account.';
	$patronData['550_EMAIL'] = strtoupper($patronData['patronEmail']);
	$patronData['600_PIN'] = date("md", strtotime($patronData['birth_date']));

	if (array_key_exists($patronData['030_BARCODE'][0], $allPatrons)){
		//already have this patron, must be a secondary address
		echo("Warning duplicate student number exists {$patronData['030_BARCODE'][0]}\n");
	}else{
		$allPatrons[$patronData['030_BARCODE'][0]] = $patronData;
	}

} //End reading raw data export

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

	//030 = barcode, repeatable
	foreach ($patronData['030_BARCODE'] as $pBarcode) {
		$record->appendField(new File_MARC_Data_Field('030',array( new File_MARC_Subfield('a', $pBarcode))));
	}
	//080 = expiration date
	$record->appendField(new File_MARC_Data_Field('080',array( new File_MARC_Subfield('a', $patronData['080_EXP_DATE']))));
	//081 = age group
	$record->appendField(new File_MARC_Data_Field('081',array( new File_MARC_Subfield('a', $patronData['081_AGE_GRP']))));
	//082 = pcode2 REG LIB : should be 3 MNPS for all patrons initially enrolled via MNPS data
	$record->appendField(new File_MARC_Data_Field('082',array(new File_MARC_Subfield('a', $patronData['082_REG_LIB']))));
	//083 = zip/PCODE3?
	$record->appendField(new File_MARC_Data_Field('083',array(new File_MARC_Subfield('a', $patronData['083_ZIP']))));
	//084 = patron type : should be 33 for all Elementary Students
	$record->appendField(new File_MARC_Data_Field('084',array( new File_MARC_Subfield('a', $patronData['084_PTYPE']))));
	//085 = hold pickup library = school 
	$record->appendField(new File_MARC_Data_Field('085',array( new File_MARC_Subfield('a', $patronData['085_HOLD_LIBR']))));
	//086 = mblock
	$record->appendField(new File_MARC_Data_Field('086',array( new File_MARC_Subfield('a', $patronData['086_MBLOCK']))));
	//089 = birthdate
	$record->appendField(new File_MARC_Data_Field('089',array( new File_MARC_Subfield('a', $patronData['089_BIRTH_DATE']))));
	//100 = name, repeatable
	foreach ($patronData['100_NAME'] as $pName) {
		$record->appendField(new File_MARC_Data_Field('100',array( new File_MARC_Subfield('a', $pName))));
	}
	//110 = guardian, repeatable
	foreach ($patronData['110_GUARDIAN'] as $pGuardian) {
		$record->appendField(new File_MARC_Data_Field('110',array( new File_MARC_Subfield('a', $pGuardian))));
	}
	//220 = address
	$record->appendField(new File_MARC_Data_Field('220',array( new File_MARC_Subfield('a', $patronData['220_ADDRESS']))));
	//225 = phone
	$record->appendField(new File_MARC_Data_Field('225',array( new File_MARC_Subfield('a', $patronData['225_PHONE']))));
	//500 = notes, repeatable
	foreach ($patronData['500_NOTE'] as $pNote) {
		$record->appendField(new File_MARC_Data_Field('500',array( new File_MARC_Subfield('a', $pNote))));
	}
	//550 = email
	$record->appendField(new File_MARC_Data_Field('550',array( new File_MARC_Subfield('a', $patronData['550_EMAIL']))));
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
