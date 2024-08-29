<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

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

    // maka an ilay fichier amzay
    $worksheet = $spreadsheet->getActiveSheet();

    // maka total an le ligne amzay
    $highestRow = $worksheet->getHighestRow();

    // avoka ny resultat
    echo "Total number of rows: " . $highestRow;
    echo "<br><a href='/'>Retour en ariere</a>";

    // fafana anaty dosssier uploads ny fichier, ra tsy tiana fafana de afaka esorina ito
    unlink($filePath);
} else {
    echo "No file uploaded or invalid request.";
}

