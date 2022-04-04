<!DOCTYPE html>
<?php
	require_once("functions.php");

	$pdo = connect($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);

	$table = "categories";

	if(!tableExists($pdo, $table)) {
		createTable($pdo, $table);
	}

	$json = file_get_contents(__DIR__ . "/categories.json");

	if($json) {
		if(tableEmpty($pdo, $table)) {
			insertJSON($pdo, $table, $json);
		} else {
			replaceAllJSON($pdo, $table, $json, "1");
		}
	}
?>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>categories</title>
	</head>
	<body>
		<?
			$sql = "select
					json_array_elements(data::json) AS data
					from {$table}";
			$statement = $pdo->prepare($sql);
			$statement->execute();
			$categoriesArray = $statement->fetchAll(PDO::FETCH_ASSOC);
				
			clearFiles();

			echo "<pre>";
				
			foreach($categoriesArray as $categoryObject) {
				$categoryObject = json_decode($categoryObject["data"]);
				writeCategoriesWithSingleNesting($categoryObject);
				writeCategoriesWithAlias($categoryObject, "");
				showCategories($categoryObject, "");
			}
				
			echo "</pre>";
		?>
	</body>
</html>