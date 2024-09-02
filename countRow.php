<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$globalAgence = []; // Initialize an empty array to store the sheet names and their values
/**
 * jereny sao de tsy excel le fichier na tsy miexiste
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excelFile'])) {
    $file = $_FILES['excelFile'];

    // jereny loa sao de misy ereur ilay zavatra natao upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("Upload failed with error code " . $file['error']);
    }
    // anarany dossier ande amindrana an ilay fichier
    $uploadDir = 'uploads/';
    
    // mijery ra miexiste ilay dossier uploads, ra tsy miexiste dia foronina
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); // Creates the directory with read/write/execute permissions
    }

    // afindra anaty le dossier uploads le fichier loany
    $filePath = $uploadDir . basename($file['name']);
    move_uploaded_file($file['tmp_name'], $filePath);

    
    // manao chargement an ilay fichier natao upload anaty dossier uploads 
    $spreadsheet = IOFactory::load($filePath);
    
    // Obtenir toutes les feuilles du fichier
    $sheetNames = $spreadsheet->getSheetNames();

    // // fafana anaty dosssier uploads ny fichier, ra tsy tiana fafana de afaka esorina ito
    
    

foreach ($sheetNames as $sheetIndex => $sheetName) {
    // Select the sheet by its index
    $worksheet = $spreadsheet->getSheet($sheetIndex);
    
    // Initialize an array to store the rows of the current sheet
    $sheetData = [];

    // Read the data from the sheet
    foreach ($worksheet->getRowIterator() as $rowIndex => $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false); // Allows reading all cells, even empty ones
        
        // Initialize an array to store the cell values of the current row
        $rowData = [];
        foreach ($cellIterator as $cell) {
            $rowData[] = $cell->getValue(); // Add cell value to the rowData array
        }

        $sheetData[] = $rowData; // Add the row data to the sheetData array
    }

    // Store the sheet data in the global array with the sheet name as the key
    $globalAgence[$sheetName] = $sheetData;
}

unlink($filePath);
}

// Output the result for debugging
// echo "<pre>";
// var_dump($globalAgence["A01"]);
// echo "</pre>";

$test = $globalAgence;

$DAV = [];
$DAVPAGENCE = [];

$EPR = [];
$EPRPAGENCE = [];

$DAT = [];
$DATPAGENCE = [];
try {

foreach ($test as $key => $value) {
    $entete = $value[0];

    for ($i=1; $i < count($value); $i++) { 
        // $associativeArray[] = $test[$i];
        $combinedArray[$key][] = array_combine($entete, $value[$i]);
    }
    $compte = [];
    
    foreach ($combinedArray[$key] as $item) {
        if (isset($item["Compte"])) {
            $compte[$key][] = $item["Compte"];
        }
    }
    if ($key !== null && !empty($compte[$key])) {
        for ($i=0; $i < count($compte[$key]); $i++) { 
            $str = explode(",", $compte[$key][$i]);
            
            for ($j=0; $j < count($str); $j++) { 
                if (strlen($str[$j]) >= 5) {
                    $faranyNombre = $str[$j][-5];
                    
                    if ($faranyNombre == 0) {
                        $DAV[$key][] = $str[$j];
                    } else if ($faranyNombre == 1) {
                        $EPR[$key][] = $str[$j];
                    } else {
                        $DAT[$key][] =  $str[$j];
                    }
                    
                }
            }
        }
    }
}


foreach ($EPR as $key => $value) {
    $EPRPAGENCE[$key][] = count($value);
}

foreach ($DAV as $key => $value) {
    $DAVPAGENCE[$key][] = count($value);
}

foreach ($DAT as $key => $value) {
    $DATPAGENCE[$key][] = count($value);
}

$filenameEPR = time()."resultatEPR.txt";
$filenameDAV = time()."resultatDAV.txt";
$filenameDAT = time()."resultatDAT.txt";

$file = fopen($filenameEPR,"w");
$file2 = fopen($filenameDAV,"w");
$file3 = fopen($filenameDAT,"w");
$total1= 0;
$total2= 0;
$total3= 0;
if ($file) {
    foreach ($EPRPAGENCE as $key => $value) {
        $sum = array_sum($value);
        $total1 += $sum;

        $line = $key . ": " . implode(", ", $value) . "| Total: $sum\n";
        
        fwrite($file, $line);
    }

    fwrite($file, "\nTotal globale: $total1\n");
}
fclose($file);

if ($file2) {
    foreach ($DAVPAGENCE as $key => $value) {
        $sum = array_sum($value);
        $total2 += $sum;
        $line = $key . ": " . implode(", ", $value) . "| Total: $sum\n";
        fwrite($file2, $line);
    }
    fwrite($file2, "\nTotal globale: $total2\n");
}
fclose($file2);

if ($file3) {
    foreach ($DATPAGENCE as $key => $value) {
        $sum = array_sum($value);
        $total3 += $sum;
        $line = $key . ": " . implode(", ", $value) . "| Total: $sum\n";
        fwrite($file3, $line);
    }
    fwrite($file3, "\nTotal globale: $total3\n");
}
fclose($file3);
} catch (\Throwable $th) {
    //throw $th;
}

echo "<p>terminer <a href='/index.php'>revenir en arriere</a></p>";