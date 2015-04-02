<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/order/([0-9]+)/([0-9]+)/#",
		"RULE" => "result=\$1&order=\$2&OTHER=\$3",
		"ID" => "",
		"PATH" => "/index.php",
	),
	array(
		"CONDITION" => "#^/result/([0-9]+)/#",
		"RULE" => "result=\$1&OTHER=\$2",
		"ID" => "",
		"PATH" => "/index.php",
	),
);

?>