<?php
require_once '/usr/local/emhttp/plugins/NerdTools/include/DownloadHelpers.php';

$plg_path = '/boot/config/plugins/NerdTools/'; // plugin path
$depends_file   = $plg_path.'packages-depends';

$pkg_depends = 'https://raw.githubusercontent.com/UnRAIDES/unRAID-NerdTools/main/packages/packages-depends';

if (!file_exists($depends_file) ?? False || (filemtime($depends_file) < (time() - 3600))) {
  get_content_from_github($pkg_depends, $depends_file);
  $depends_file_array = file_exists($depends_file) ? json_decode(file_get_contents($depends_file), true) : [];
} else {
  $depends_file_array = file_exists($depends_file) ? json_decode(file_get_contents($depends_file), true) : [];
}

  
echo json_encode($depends_file_array);
  

?>
