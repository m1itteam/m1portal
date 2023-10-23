<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_log', 'log-homework.txt');

if ($_SERVER["REQUEST_METHOD"] == "POST"){
  // 受信したJSONデータをデコード
  $jsonData = file_get_contents('php://input');
  if ($jsonData !== false) {
  $data = json_decode($jsonData, true);

  if ($data) {

  $deleteGrade = $data['deleteGrade'];
  $deleteLine = $data['deleteLine'];
  $deletedNumber = 0;

  $logFile = "log-homework.txt";
  $logEntry = "deleteStart";
  file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);

  foreach ($deleteGrade as $grade) {
    $logEntry = "foreachStart";
    file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);

    // ファイルの内容を読み込む
    $filename = "$grade-homework.txt";
    $fileContent = file_get_contents($filename);

    $homework = explode("\n", $fileContent);

    $deletedLineNumber = $deleteLine[$deletedNumber];

    if (isset($homework[$deletedLineNumber])) {
      $logEntry = "ifStart";
      file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);

      $deletedLine = $homework[$deletedLineNumber];

      $deletedCells = explode("|",$deletedLine);

      $deletedCellsCount = count($deletedCells);

      $pattern = '/(.*?)\"(\d+),(\d+)\"/';

      if ($deletedCellsCount == 2) {
        $logEntry = "processStart";
        file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);
        unset($homework[$deletedLineNumber]);

        $parentLineNumber = $deletedLineNumber - 1;
        for ($i = $parentLineNumber; $i >= 0; $i--) {
          $countCells = count(explode("|",$homework[$i]));
          if ($countCells = 3){
            break;
          }
          $parentLineNumber--;
        }
        $parentCells = explode("|",$homework[$parentLineNumber]);
        preg_match($pattern, $parentCells[0], $matches);
        $rowNumber = $matches[3] - 1;
        $newCell = $matches[1] . '"' . $matches[2] . "," . $rowNumber . '"';
        array_splice($parentCells, 0, 1, $newCell);
        $newLine = implode("|",$parentCells);
        array_splice($homework, $parentLineNumber, 1, $newLine);
      } else {
        $logEntry = "processStart";
        file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);
        $homework[$deletedLineNumber] = $deletedCells[0];
      }
      $homework = implode("\n", $homework);
      // ファイルに書き込み
      file_put_contents($filename, $homework);

      // ログを記録
      $logFile = "log-homework.txt";
      $logEntry = date('Y-m-d h:i') . " " . $deletedLine . "deleted";
      file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);
    }
    $deletedNumber++;
  }
  } else {
      echo json_encode(["error" => "JSONデータのデコードに失敗しました"]);
    }
  } else {
    echo json_encode(["error" => "JSONデータの受信に失敗しました"]);
  }
  header("Location:https://m1portal.cloudfree.jp/inputhomework.html");
  exit();
}
?>
