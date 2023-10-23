<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_log', 'log-homework.txt');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $grade = $_POST["grade"];  // 対象学年
  $gradeIdentifier = $grade . "subject";
  $subject = $_POST[$gradeIdentifier];  // 生成されたフィールド名に基づいて選択された教科を取得
  $date = $_POST["date"];            // 期限
  $homework = $_POST["homework_form"]; // 課題内容

  $logFile = "log-homework.txt";
  $logEntry = "addStart";
  file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);

  // ファイルの内容を読み込む
  $filename = "$grade-homework.txt";
  $fileContent = file_get_contents($filename);

  $homework = explode("\n", $homework);

  // 置換を行う
  $homework = implode("<br>", $homework);

  // 学年ごとの教科の配列を定義
  $gradesArray = array(
    "jh1" => ['文学国語', '古典探究', '数学', '英語C', '論理表現', '化学', '物理', '生物基礎', '地学', '公民', '地理探究', '世界史', '日本史', '情報', '保健', '家庭科', '芸術', '総合'],
    "jh2" => ['文学国語', '古典探究', '数学', '英語C', '論理表現', '化学', '物理', '生物基礎', '地学', '公民', '地理探究', '世界史', '日本史', '情報', '保健', '家庭科', '芸術', '総合'],
    "jh3" => ['文学国語', '古典探究', '数学', '英語C', '論理表現', '化学', '物理', '生物基礎', '地学', '公民', '地理探究', '世界史', '日本史', '情報', '保健', '家庭科', '芸術', '総合'],
    "h1" => ['文学国語', '古典', '数学', '英語C', '論理表現', '化学基礎', '物理基礎', '地理総合', '歴史総合', '情報', '保健', '芸術', '総合'],
    "h2" => ['文学国語', '古典探究', '数学', '英語C', '論理表現', '化学', '物理', '生物基礎', '地学', '公民', '地理探究', '世界史', '日本史', '情報', '保健', '家庭科', '芸術', '総合'],
    "h3" => ['文学国語', '古典探究', '数学', '英語C', '論理表現', '化学', '物理', '生物基礎', '地学', '公民', '地理探究', '世界史', '日本史', '情報', '保健', '家庭科', '芸術', '総合']
  );

  // 学年が存在しない場合の処理
  if (!isset($gradesArray[$grade])) {
    echo "学年がありません";
  } else {
    $array = $gradesArray[$grade];

    // 検索対象の文字列を配列内で見つける
    $key = array_search($subject, $array);

    if ($key !== false) {
      // 検索対象の文字列が見つかった場合、次の要素を取得
      $nextElement = isset($array[$key + 1]) ? $array[$key + 1] : null;
      $logFile = "log-homework.txt";
      $logEntry = $nextElement;
      file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);

    } else {
      // 検索対象の文字列が見つからなかった場合
      echo "教科名エラー";
    }

    // 正規表現を使用して、選択した教科に一致する行を見つける
    $pattern = "/$subject\"(\d+),(\d+)\"/";

    // $nextElement に応じて正規表現パターンを設定
    if ($nextElement !== null) {
      $nextpattern = "/$nextElement\"(\d+),(\d+)\"/";
      $logFile = "log-homework.txt";
      $logEntry = "set";
      file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);
    } else {
      $nextpattern = "/$/"; // テキストの最後にマッチする空のパターン
      $logFile = "log-homework.txt";
      $logEntry = "set";
      file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);
    }

    // 新しい行を作成
    $newLine = $date . "|" . $homework; // 新しい行の内容を適切に設定

    $logFile = "log-homework.txt";
    $logEntry = $newLine;
    file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);

    // 正規表現に一致する行の行数を取得
    $matches = [];
    $lineNumber = 0;
    $lines = explode("\n", $fileContent);

    foreach ($lines as $line) {
      $logFile = "log-homework.txt";
      $logEntry = "foreach";
      file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);
      if (preg_match($pattern, $line, $matches)) {
        $separated = explode("|", $line);

        // 配列の要素数を取得
        $elementCount = count($separated);

        $logFile = "log-homework.txt";
        $logEntry = $matches[0];
        file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);

        if ($elementCount === 1) {
          $newLine = $matches[0] . "|" . $newLine;

          array_splice($lines, $lineNumber, 1, $newLine);

          $logFile = "log-homework.txt";
          $logEntry = "success1";
          file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);
        } else {
          // 新しい値を計算
          $newRow = intval($matches[2]) + 1;
          // 置き換える文字列
          $replacement = $subject . "\"1," . $newRow . "\"";
          // 置換を行う
          $fileContent = preg_replace($pattern, $replacement, $fileContent);
          $matches = [];
          $lineNumber = 0;
          foreach ($lines as $line) {
            $logFile = "log-homework.txt";
            $logEntry = "2ndforeach";
            file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);
            if (preg_match($nextpattern, $line, $matches)) {
              break; // 正規表現に一致した行を見つけたらループを終了
              $logFile = "log-homework.txt";
              $logEntry = "success2";
              file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);
            }
            $lineNumber++;
          }  // 新しい行を挿入
          $logFile = "log-homework.txt";
          $logEntry = $lineNumber;
          file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);
          if ($nextElement !== null) {
            array_splice($lines, $lineNumber, 0, $newLine); // 直前に新しい行を挿入
          } else {
            array_push($lines, $newLine);
          }
        }
        break;
      }
      $lineNumber++;
    }

    // 配列をテキストに戻す
    $newText = implode("\n", $lines);

    // ファイルに書き込み
    file_put_contents($filename, $newText);

    // ログを記録
    $logFile = "log-homework.txt";
    $logEntry = "$grade,$subject,$date,$homework";
    file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);

    $newsFileName = "news.txt";
    $newsFileContent = file_get_contents($newsFileName);

    $newsLines = explode("\n", $newsFileContent);

    $now = date("Y-m-d h:i");

    $grades = array(
      "jh1" => "中学一年生",
      "jh2" => "中学二年生",
      "jh3" => "中学三年生",
      "h1"  => "高校一年生",
      "h2"  => "高校二年生",
      "h3"  => "高校三年生"
    );

    if (isset($grades[$grade])) {
      $grade = $grades[$grade];
    }

    $newsNewLine = $now . "|for" . $grade . "|<a href='https://m1portal.cloudfree.jp/homework.html'>課題が追加されました</a>";

    array_pop($newsLines);

    array_unshift($newsLines, $newsNewLine);

    $newsNewText = implode("\n", $newsLines);

    file_put_contents($newsFileName, $newsNewText);

    header("Location:https://m1portal.cloudfree.jp/inputhomework.html");
    exit();
  }
}
?>
