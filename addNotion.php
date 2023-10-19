<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $notion = $_POST["notion_form"]; // お知らせ内容

  // ファイルの内容を読み込む
  $filename = "notion.txt";
  $fileContent = file_get_contents($filename);

  $notion = explode("\n", $notion);

  // 置換を行う
  $notion = implode("<br>", $notion);

  $lines = explode("\n", $fileContent)

  $newLine = date("Y-m-d h:m:s") . "
 " . $notion;

  array_pop($lines);

  array_unshift($lines, $newLine);

  // 配列をテキストに戻す
  $newText = implode("\n", $lines);

  // ファイルに書き込み
  file_put_contents($filename, $newText);

  // ログを記録
  $logFile = "log-notion.txt";
  $logEntry = "$newLine";
  file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);

  header("Location:https://m1portal.cloudfree.jp/inputnotion.html");
	exit();
}
?>
