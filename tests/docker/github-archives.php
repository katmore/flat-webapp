<?php
// Optional cache warming for locked public GitHub dependencies when API archive
// requests are unavailable. Composer still installs and validates the same lock.
if (count($argv) !== 3) throw new InvalidArgumentException('Usage: github-archives.php LOCK_FILE CACHE_DIRECTORY');
$lock = json_decode(file_get_contents($argv[1]), true, 512, JSON_THROW_ON_ERROR);
$cache = rtrim($argv[2], '/');
foreach (array_merge($lock['packages'], $lock['packages-dev'] ?? []) as $package) {
    $dist = $package['dist'] ?? [];
    if (($dist['type'] ?? '') !== 'zip' || !preg_match('~^https://api\.github\.com/repos/([\w.-]+/[\w.-]+)/zipball/([a-f0-9]{40})$~', $dist['url'] ?? '', $match)) continue;
    $file = $cache.'/'.$package['name'].'/'.sha1($dist['url']).'.zip';
    if (is_file($file)) continue;
    if (!is_dir(dirname($file))) mkdir(dirname($file), 0755, true);
    $stream = fopen($file.'.partial', 'wb');
    $curl = curl_init('https://codeload.github.com/'.$match[1].'/legacy.zip/'.$match[2]);
    curl_setopt_array($curl, [CURLOPT_FILE=>$stream, CURLOPT_FOLLOWLOCATION=>true,
        CURLOPT_FAILONERROR=>true, CURLOPT_CONNECTTIMEOUT=>20, CURLOPT_TIMEOUT=>120]);
    if ($ca = getenv('COMPOSER_CAFILE')) curl_setopt($curl, CURLOPT_CAINFO, $ca);
    try {
        if (!curl_exec($curl)) throw new RuntimeException($package['name'].': '.curl_error($curl));
    } finally { unset($curl); fclose($stream); }
    rename($file.'.partial', $file);
    echo 'Cached locked archive: '.$package['name']."\n";
}
