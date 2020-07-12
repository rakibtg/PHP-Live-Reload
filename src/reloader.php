<?php

  $envExists = file_exists(__DIR__ . '/../env.php');
  if (!$envExists) prepare_environment();
  require_once __DIR__ . '/../env.php';
  $devUrl = isset($_GET['url']) ? $_GET['url'] : false;
  $projectPath = isset($_GET['path']) ? $_GET['path'] : false;

  function is_sub_dir ($path = NULL, $parent_folder = SITE_PATH) {

    //Get directory path minus last folder
    $dir = dirname($path);
    $folder = substr($path, strlen($dir));

    //Check the the base dir is valid
    $dir = realpath($dir);

    //Only allow valid filename characters
    $folder = preg_replace('/[^a-z0-9\.\-_]/i', '', $folder);

    //If this is a bad path or a bad end folder name
    if( !$dir OR !$folder OR $folder === '.') {
      return FALSE;
    }

    //Rebuild path
    $path = $dir. DS. $folder;

    //If this path is higher than the parent folder
    if( strcasecmp($path, $parent_folder) > 0 ) {
      return $path;
    }

    return FALSE;
  }

  function get_modification_time ($path) {
    $directory = new RecursiveDirectoryIterator(
      $path,
      FilesystemIterator::KEY_AS_PATHNAME | 
      FilesystemIterator::CURRENT_AS_FILEINFO | 
      FilesystemIterator::SKIP_DOTS
    );
    $iterator = new RecursiveIteratorIterator(
      $directory,
      RecursiveIteratorIterator::SELF_FIRST 
    );
    $resultFile = $iterator->current();
    $toIgnore = $path . "/vendor/";
    foreach($iterator as $file) {
      if (is_sub_dir($file, $toIgnore)) {
        // echo '1, ';
      } else {
        echo $file . "\n";
      }
      if ($file->getMtime() > $resultFile->getMtime()) {
        $resultFile = $file;
      }
    }
    return $resultFile->getMtime();
  }

  function prepare_environment () {
    if (!file_exists(__DIR__ . '/../env.php')) {
      copy(__DIR__.'/env.backup', __DIR__.'/../env.php');
      echo "Created ENV file.\n";
    }
  }

  function serve () {
    global $env;
    $_php = $env['php'] ? $env['php'] : 'php';
    $_port = $env['port'] ? $env['port'] : 2020;
    $_host = $env['host'] ? $env['host'] : '127.0.0.1';
    echo "Starting server at: http://$_host:$_port \n";
    $appDir = __DIR__ . '/app';
    exec("$_php -S $_host:$_port -t $appDir");
  }

  function handle_api() {
    global $projectPath;
    echo get_modification_time($projectPath);
  }