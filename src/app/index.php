<?php require_once __DIR__ . '/../reloader.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="app.css">
  <script src="app.js"></script>
  <title>PHP Live Reloader</title>
</head>
<body>
  <?php if($devUrl && $projectPath) { ?>
    <div class="d_f w_f fa_center nav_closed" id="floating_nav">
      <div class="p_10">
        <a class="cl_444 p_10 no_td" href="/">+ New</a>
      </div>
      <form class="d_f fg_1 p_10" action="/" method="get">
        <input class="p_10 fg_1 mr_10 mw_600" onClick="this.select();" type="text" name="url" placeholder="http://localhost" value="<?php echo $devUrl; ?>">
        <input class="p_10 d_h" type="text" name="path" placeholder="/users/project/blog" value="<?php echo $projectPath; ?>">
        <input class="p_10" type="submit" value="Update">
      </form>
    </div>
    <iframe 
      class="w_f h_f d_b" 
      src="<?php echo $devUrl; ?>" 
      frameborder="0"
      path="<?php echo $projectPath; ?>"
      url="<?php echo $devUrl; ?>"
      api="<?php echo "http://$_host:$_port/api.php"; ?>"
      interval="<?php echo $env['interval']; ?>"
    ></iframe>
  <?php } else { ?>
    <div class="w_f h_f d_f fj_center fa_center">
      <div class="w_8p mw_600 fd_col">
        <div class="f_24 mb_10">PHP Live Reloader</div>
        <form class="d_f fd_col" action="/" method="get">
          <input class="mb_10 p_10" type="text" name="url" placeholder="http://localhost">
          <input class="mb_10 p_10" type="text" name="path" placeholder="/users/project/blog">
          <input class="p_10 mt_10" type="submit" value="Start">
        </form>
      </div>
    </div>
  <?php } ?>
</body>
</html>