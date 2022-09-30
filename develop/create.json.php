<?php


$version = isset($argv[1])? $argv[1]: '6.11';

$array = array();

$files = glob(__DIR__."/../packages/$version/*.{txz,tgz}", GLOB_BRACE);
foreach($files as $txz){
    //echo $txz, "\n";

    $array[] = file_check_sha1($txz);
}

// Compare the github sha1 value of a file
function file_check_sha1($file) {
    global $version;

    $size = filesize($file);
    $contents = file_get_contents($file);

    // create a sha1 like github does
    $str = "blob ".$size."\0".$contents;
    $sha1_file = sha1($str);

    $detalle = array();

    #print('Downloading file_check_sha1 => size ['.$size.'] package...');
    #print('Downloading file_check_sha1 => sha1_file ['.$sha1_file.'] package...');
    #print('Downloading file_check_sha1 => sha1_file ['.sha1($contents).'] package...');

    //echo "$file => $sha1_file => $size \n";
    $detalle['name'] = basename($file);
    $detalle['path'] = "packages/$version/".basename($file);
    $detalle['sha'] = $sha1_file;
    $detalle['size'] = $size;
    $detalle['download_url'] = "https://raw.githubusercontent.com/UnRAIDES/unRAID-NerdTools/main/packages/$version/".basename($file);
    $detalle['type'] = 'file';

    //print_r($detalle);
    //print_r($detalle);
    //     $detalle['download_url'] = "https://raw.githubusercontent.com/UnRAIDES/unRAID-NerdPack/master/packages/$version/".basename($file);

    
    return ($detalle);
}

echo json_encode($array,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES );



?>
