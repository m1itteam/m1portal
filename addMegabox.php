<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $question = $_POST["megaboxForm"]; // お知らせ内容
  $address = $_POST["addressSelect"];
  $grade = $_POST["megaboxGradeSelect"];
  $gradeOpen = $_POST["gradeOpenSelect"];
  $questionOpen = $_POST["questionOpenSelect"];
  
  $toWrite = [date("Y-m-d H:i:s"),$grade,$gradeOpen,$questionOpen,$question];
  
  if ($address == "SC"){
    $file = fopen("SCMegabox.csv","a");
  } else if ($address == "IT"){
    $file = fopen("ITMegabox.csv","a");
  }
  $fputcsv($file, $toWrite);
  
  fclose($file);
}
?>
