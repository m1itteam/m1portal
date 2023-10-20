function getDataFromCSVAndSubmitToForm() {
  // 外部CSVファイルのURL
  var csvUrl = "https://example.com/external-data.csv"; // 外部サーバーのCSVファイルのURLを指定

  // GoogleフォームのID
  var formId = "your_form_id"; // GoogleフォームのIDを指定

  // 外部CSVデータを取得
  var response = UrlFetchApp.fetch(csvUrl);
  var csvData = response.getContentText();

  var questions = ["パスワードを入力してください", "生徒の方は学年の番号を選択してください。(教職員の方はTを選択してください。)", "希望される対応を一つ選択してください。 1,学年公開◎、質問公開◎ 2,学年公開◎、質問公開✕ 3,学年公開✕、質問公開◎ 4,学年公開✕、質問公開✕", "生徒会に対しての意見や質問をお願いします！（1回の回答につき一つの内容にまとめていただけると役員が返信しやすくなります。）"];

  // CSVデータを行に分割
  var csvLines = csvData.split("\n");

  // Googleフォームを取得
  var form = FormApp.openById(formId);

  // 各質問に対するフォームアイテムを取得
  var item1 = form.getItems(FormApp.ItemType.TEXT, questions[0])[0];
  var item2 = form.getItems(FormApp.ItemType.MULTIPLE_CHOICE, questions[1])[0];
  var item3 = form.getItems(FormApp.ItemType.MULTIPLE_CHOICE, questions[2])[0];
  var item4 = form.getItems(FormApp.ItemType.TEXT, questions[3])[0];

  // CSVデータを行ごとに処理
  csvLines.forEach(function(line) {
    var csvCells = line.split(",");
    var password = csvCells[0];
    var grade = csvCells[1];
    var open = csvCells[2];
    var feedback = csvCells[3];

    // 各質問に回答を追加して送信
    form.createResponse(item1.asTextItem().createResponse(password)).submit();
    form.createResponse(item2.asMultipleChoiceItem().createResponse(grade)).submit();
    form.createResponse(item3.asMultipleChoiceItem().createResponse(open)).submit();
    form.createResponse(item4.asTextItem().createResponse(feedback)).submit();
  });
}
