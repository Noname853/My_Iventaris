<?php
$csv = "kode;nama;kategori;stok;lokasi;deskripsi\nTEST1;Alat Test;Test;10;Lab;Test desc";
file_put_contents('test_import.csv', $csv);
$handle = fopen('test_import.csv', 'r');
$firstLine = fgets($handle);
$delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
echo "Delimiter: " . $delimiter . "\n";
rewind($handle);
$header = null;
$rowIndex = 0;
while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
    if ($rowIndex === 0) {
        $row[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[0]);
        $header = $row;
        $rowIndex++;
        continue;
    }
    $data = array_combine($header, $row);
    print_r($data);
}
fclose($handle);
