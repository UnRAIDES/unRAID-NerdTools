<?
require_once '/usr/local/emhttp/plugins/NerdTools/include/NerdToolsHelpers.php';
require_once '/usr/local/emhttp/plugins/NerdTools/include/DownloadHelpers.php';

// Only download repo update if the current one is 1 hour old or more
if (!file_exists($repo_file) || !empty($_GET['force']) || (filemtime($repo_file) < (time() - 3600))) {
    get_content_from_github($pkg_repo, $repo_file);
    get_content_from_github($pkg_desc, $desc_file);
    get_content_from_github($pkg_depends, $depends_file);
    $pkgs_desc_array   = file_exists($desc_file) ? json_decode(file_get_contents($desc_file), true) : [];
    $pkgs_github_array = file_exists($repo_file) ? json_decode(file_get_contents($repo_file), true) : [];
    $depends_file_array = file_exists($depends_file) ? json_decode(file_get_contents($depends_file), true) : [];
}

$pkgs_array = [];
$pkg_nameArray = [];

foreach ($pkgs_github_array as $pkg_github) {
    $pkg_nameArray = explode('-', $pkg_github['name']); // split package name into array

    $pattern  = '/^([a-zA-Z0-9-]+)-([\d.]+)-?.*\.txz$/';
    $pkg_name = "";
    $pkg_version = "";
    $pkg_nver = "";

    if (preg_match($pattern, $pkg_github['name'], $matches)) {
        // $matches[1] contiene el nombre del paquete
        // $matches[2] contiene la versión del paquete
        $pkg_name = $matches[1];
        $pkg_version = $matches[2];
        $pkg_nver = $matches[2];
    }

    // strip md5 files
    if(!strpos(end($pkg_nameArray),'.md5')) {
                
        $pkg_nver    = $pkg_name.'-'.str_replace('.', '__', $pkg_version); // add underscored version to package name
        $pkg_pattern = '/^'.$pkg_name.'-[0-9].*/'; // search pattern for packages

        // check all plugins for package dependency
        $plugins =  [];
        exec("cd /boot/config/plugins ; find *.plg | xargs grep '$pkg_name-$pkg_version' -sl",$plugins);
        $pkg_plgs = '--';
        if ($plugins){
            foreach ($plugins as $plugin){
                $pkg_plgs .= pathinfo($plugin, PATHINFO_FILENAME).', ';
                }
            $pkg_plgs =	substr($pkg_plgs, 2, -2);
        }

        // get package preference from config file
        $pkg_set = "no";
        foreach ($pkg_cfg as $pkg_key => $pkg_line) {
            if (preg_match('/^'.$pkg_name.'.*/',$pkg_key)){
                if(sizeof(array_diff(explode('-', $pkg_key), explode('-', $pkg_name))) < 2 ){
                    $pkg_set = $pkg_line;
                    break;
                }
            }
        }

        $downloadedpkg = !empty(preg_grep($pkg_pattern, $pkgs_installed)) ? array_values(preg_grep($pkg_pattern, $pkgs_installed))[0] : false;
        $downloadedpkgv = $downloadedpkg ? preg_match('/^'.$pkg_name.'-(\d.+?)[-|_].*/',$downloadedpkg, $matches)? $matches[1]:false : false;
        $updatePkg = version_compare($pkg_version, $downloadedpkgv, '>') ;
        
        if (!array_key_exists($pkg_name, $pkgs_desc_array)) $pkgs_desc_array[$pkg_name] = "";

        $pkg = [
            'name'          => str_replace("_nerdtools.txz",".txz",$pkg_github['name']) , // add full package name
            'dependencies'  => isset($depends_file_array[$pkg_name]) ? str_replace(array(" ",","), array("",", "), $depends_file_array[$pkg_name]) : '', // add package name only
            'pkgname'       => $pkg_name, // add package name only
            'pkgnver'       => $pkg_nver, // add package name with underscored version
            'pkgversion'    => $pkg_version, // add package name with raw version
            'updatePkg'     => $updatePkg, // add package name with raw version
            'updatePkgs'    => "$pkg_version => $downloadedpkgv", // add package name with raw version
            'size'          => format_size($pkg_github['size'], 1, '?'), // add package size
            'installed'     => !empty(preg_grep($pkg_pattern, $pkgs_installed)) ? 'yes' : 'no', // checks if package name is installed
            'installeq'     => in_array(pathinfo($pkg_github['name'], PATHINFO_FILENAME), $pkgs_installed) ? 'yes' : 'no', // checks if package installed equals github exactly
            'downloaded'    => !empty(preg_grep($pkg_pattern, $pkgs_downloaded)) ? 'yes' : 'no', // checks if package name is downloaded
            'downloadeq'    => in_array($pkg_github['name'], $pkgs_downloaded) ? 'yes' : 'no', // checks if package downloaded equals github exactly
            'actualpkgv'    => $downloadedpkgv ? $downloadedpkgv:" - ", // checks if package name is downloaded
            'config'        => $pkg_set, // install preference
            'plugins'       => $pkg_plgs, // plugins dependency on package
            'desc'          => $pkgs_desc_array[$pkg_name]
        ];

        $pkgs_array[] = $pkg;
    }
}


$return = [
        'packages' => $pkgs_array,
        'empty'    => empty($pkgs_downloaded)
    ];

echo json_encode($return);
?>