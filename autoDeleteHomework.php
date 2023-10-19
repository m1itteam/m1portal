<?php
$grade = ["jh1","jh2","jh3","h1","h2","h3"];
foreach ($grade as $element) {
	$filename = $element . "-homework.txt";
	$fileContent = file_get_contents($filename);
	
	$lines = explode("\n", $fileContent);
	
	$pattern = "/(\d{4})-(\d{2})-(\d{2})/";
	
	$today = strtotime(date("Y-m-d 00:00:00"));
	
	$lineNumber = 0;
	
	foreach ($lines as $line) {
		$matches = [];
  		if (preg_match($pattern, $line, $matches)) {
        $limit = strtotime($matches[1] . "-" . $matches[2] . "-" . $matches[3] . " 23:59:59");
        if ($limit < $today) {
            $cellsCount = count(explode("|", $matches[0]));
            if ($cellsCount == 2) {
                unset($lines[$lineNumber]);
                $lines = array_values($lines);
            } else if ($cellsCount == 3) {
                $cells = explode("|", $matches[0]);
                $firstCell = $cells[0];
                array_splice($lines, $lineNumber, 1, $firstCell);
                $nextLine = $lines[$lineNumber + 1];
                $nextCellsCount = count(explode("|", $nextLine));
                if ($nextCellsCount == 2){
                  $marged = $lines[$lineNumber] . "|" . $nextLine;
                  array_splice($lines, $lineNumber, 2, $marged);
                }
            }
        }
    }
    $lineNumber++;
	}
	
	$processed = implode("\n", $lines);
	
	file_put_contents($filename, $processed);
}
?>
