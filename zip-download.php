<?php

  if ( $_SERVER['REQUEST_METHOD'] == "POST" ) {

    // ***********************************************
    // 対象フォルダ名
    // ***********************************************
    $target_dir = realpath("./");
  
    // ***********************************************
    // 書庫ファイル名
    // ***********************************************
    $zipname = basename( $target_dir );
  
    // ***********************************************
    // 一時ファイルを作成 ( temp/oooooo.tmp )
    // ***********************************************
    $file = tempnam( sys_get_temp_dir(), "zip" ); 
  
    // ***********************************************
    // ZIP 書庫作成
    // ***********************************************
    $zip = new ZipArchive(); 
  
    $zip->open($file, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE ); 
  
    $targets = recursionFiles( $target_dir );
  
    foreach( $targets as $target ) {
      $zip->addFile( $target, str_replace($target_dir."/","", $target) );
    }
  
    $zip->close(); 
  
    // ***********************************************
    // ダウンロードさせる為の処理
    // ***********************************************
    header("Content-Type: application/zip"); 
    header("Connection: close");
    header("Content-Length: " . filesize($file)); 
    header("Content-Disposition: attachment; filename=\"{$zipname}.zip\""); 
    readfile($file); 
  
    // ***********************************************
    // 一時ファイルを削除
    // ***********************************************
    unlink($file); 
  
    exit();
  }

  // ***********************************************
  // 再帰によるファイル一覧作成
  // ***********************************************
  function recursionFiles( $target ) {

    $files = glob( "{$target}/*" );

    $result = array();

    foreach ( $files as $file ) {
      // ファイル
      if (is_file($file)) {
        $result[] = $file;
      }
      // フォルダ
      else {
        $result = array_merge($result, recursionFiles($file));
      }
    }

    return $result;
  }

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
    <title>PHP で zip ファイルを作成してダウンロード</title>
    
<script>

</script>


</head>

<body>
    <h3 class="alert alert-primary">
        <a href=".">PHP で zip ファイルを作成してダウンロード</a>
    </h3>
    <div id="content"
        class="m-4">
        <form action=""
            method="POST">
            <div>
                <input
                    type="submit"
                    name="send"
                    value="zip をダウンロード">
            </div>

        </form>

    </div>
</body>
</html>
