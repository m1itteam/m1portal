<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $grade = $_POST["grade"];  // 対象学年
  $gradeIdentifier = $grade . "subject";
  $subject = $_POST[$gradeIdentifier];  // 生成されたフィールド名に基づいて選択された教科を取得
  $date = $_POST["date"];            // 期限
  $homework = $_POST["homework_form"]; // 課題内容

  // ファイルの内容を読み込む
  $filename = "$grade-homework.txt";
  $fileContent = file_get_contents($filename);

  $homework = explode("\n", $homework);

  // 置換を行う
  $homework = implode("<br>", $homework);

  if ($grade === "jh1"){
    $array = ['文学国語','古典探究','数学','英語C','論理表現','化学','物理','生物基礎','地学','公民','地理探究','世界史','日本史','情報','保健','家庭科','芸術','総合'];
  } else if ($grade === "jh2"){
    $array = ['文学国語','古典探究','数学','英語C','論理表現','化学','物理','生物基礎','地学','公民','地理探究','世界史','日本史','情報','保健','家庭科','芸術','総合'];
  } else if ($grade === "jh3"){
    $array = ['文学国語','古典探究','数学','英語C','論理表現','化学','物理','生物基礎','地学','公民','地理探究','世界史','日本史','情報','保健','家庭科','芸術','総合'];
  } else if ($grade === "h1"){
    $array = ['文学国語','古典','数学','英語C','論理表現','化学基礎','物理基礎','地理総合','歴史総合','情報','保健','芸術','総合'];
  } else if ($grade === "h2"){
    $array = ['文学国語','古典探究','数学','英語C','論理表現','化学','物理','生物基礎','地学','公民','地理探究','世界史','日本史','情報','保健','家庭科','芸術','総合'];
  } else if ($grade === "h3"){
    $array = ['文学国語','古典探究','数学','英語C','論理表現','化学','物理','生物基礎','地学','公民','地理探究','世界史','日本史','情報','保健','家庭科','芸術','総合'];
  } else {
    echo "学年がありません";
  }

// 検索対象の文字列を配列内で見つける
  $key = array_search($subject, $array);

  if ($key !== false) {
    // 検索対象の文字列が見つかった場合、次の要素を取得
    $nextElement = isset($array[$key + 1]) ? $array[$key + 1] : null;

  } else {
    // 検索対象の文字列が見つからなかった場合
    echo "教科名エラー";
  }

  // 正規表現を使用して、選択した教科に一致する行を見つける
  $pattern = "/$subject\"(\d+),(\d+)\"/";
  
  preg_match($pattern, $fileContent, $matches);

  // 新しい値を計算
  $newRow = intval($matches[2]) + 1;

  // 置き換える文字列
  $replacement = $subject . "\"1," . $newRow . "\"" ;

  // 置換を行う
  $updatedText = preg_replace($pattern, $replacement, $fileContent);

  // $nextElement に応じて正規表現パターンを設定
  if ($nextElement !== null) {
      $nextpattern = "/$nextElement\"(\d+),(\d+)\"/";
  } else {
      $nextpattern = "/$/"; // テキストの最後にマッチする空のパターン
  }

  // 新しい行を作成
  $newLine = $date . "|" . $homework; // 新しい行の内容を適切に設定

  // 正規表現に一致する行の行数を取得
  $matches = [];
  $lineNumber = 0;
  $lines = explode("\n", $updatedText);

  foreach ($lines as $line) {
      if (preg_match($nextpattern, $line, $matches)) {
          break; // 正規表現に一致した行を見つけたらループを終了
      }
      $lineNumber++;
  }

  // 新しい行を挿入
  if ($nextElement !== null){
    array_splice($lines, $lineNumber, 0, $newLine); // 直前に新しい行を挿入
  } else {
    array_push($lines, $newLine);
  }

  // 配列をテキストに戻す
  $newText = implode("\n", $lines);

  // ファイルに書き込み
  file_put_contents($filename, $newText);

  // ログを記録
  $logFile = "log-homework.txt";
  $logEntry = "$grade,$subject,$date,$homework";
  file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND);

  header("Location:https://m1portal.cloudfree.jp/inputhomework.html");
	exit();
}
?>
