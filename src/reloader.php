<?php

  $envExists = file_exists(__DIR__ . '/../env.php');
  if (!$envExists) prepare_environment();
  require_once __DIR__ . '/../env.php';

  $devUrl       = isset($_GET['url']) ? $_GET['url'] : false;
  $projectPath  = isset($_GET['path']) ? rtrim($_GET['path'], DIRECTORY_SEPARATOR) : false;
  $_port        = $env['port'] ? $env['port'] : 2020;
  $_host        = $env['host'] ? $env['host'] : '127.0.0.1';

  function is_ignored_file ($filePath) {
    global $env, $projectPath;
    foreach ($env['ignore'] as $ignorePath) {
      $ignorePath = trim($ignorePath, DIRECTORY_SEPARATOR);
      $ignoreFullpath = $projectPath
        . DIRECTORY_SEPARATOR
        . $ignorePath;
      if (strstr($filePath, $ignoreFullpath)) {
        return true;
      }
    }
    return false;
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
    foreach($iterator as $file) {
      if (!is_ignored_file($file)) {
        if ($file->getMtime() > $resultFile->getMtime()) {
          $resultFile = $file;
        }
      }
    }
    return $resultFile->getMtime();
  }

  function prepare_environment () {
    if (!file_exists(__DIR__ . '/../env.php')) {
      copy(__DIR__.'/env.backup', __DIR__.'/../env.php');
    }
  }

  function serve () {
    global $env, $_port, $_host;
    $_php = $env['php'] ? $env['php'] : 'php';
    echo "Starting server at: http://$_host:$_port \n";
    $appDir = __DIR__ . '/app';
    exec("$_php -S $_host:$_port -t $appDir");
  }

  function handle_api() {
    global $projectPath;
    echo get_modification_time($projectPath);
  }