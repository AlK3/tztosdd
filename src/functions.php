<?php
    function connect(string $host, string $db, string $user, string $password)
    {
        try {
            $dsn = "pgsql:host={$host};port=5432;dbname={$db};";
            return new PDO(
                $dsn,
                $user,
                $password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    function createTable($pdo, $table)
    {
        try {
            $sql = "CREATE TABLE {$table}(id serial primary key, data jsonb);";
            $pdo->exec($sql);
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    function tableExists($pdo, $table)
    {
        try {
            $result = $pdo->query("SELECT 1 FROM {$table} LIMIT 1");
            return $result !== false;
        } catch (Exception $e) {
            return false;
        }
    }

    function tableEmpty($pdo, $table)
    {
        $data = $pdo->query("SELECT * FROM {$table} LIMIT 1")->fetchAll();
        if ($data) {
            return false;
        } else {
            return true;
        }
    }

    function insertJSON($pdo, $table, $json)
    {
        $sql = "INSERT INTO {$table}(data) values(:data)";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            "data" => $json
        ]);
    }

    function replaceAllJSON($pdo, $table, $json, $id)
    {
        $sql = "UPDATE {$table} SET data = :data WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            "data" => $json,
            "id" => $id
        ]);
    }

    function showCategories($categoryObject, $tabs)
    {
        echo $tabs . $categoryObject->name . "<br>";
        foreach($categoryObject->childrens as $child) {
            if(!empty($child->childrens)) {
                showCategories($child, $tabs . "\t");
            } else {
                echo $tabs . "\t" . $child->name . "<br>";
            }
        }
    }

    function writeCategoriesWithAlias($categoryObject, $tabs)
    {
        $handle = fopen("type_a.txt", "a");
        fwrite($handle, $tabs . $categoryObject->name . " " . $categoryObject->alias . "\n");
        foreach($categoryObject->childrens as $child) {
            if(!empty($child->childrens)) {
                writeCategoriesWithAlias($child, $tabs . "\t");
            } else {
                fwrite($handle, $tabs . "\t" . $child->name . " " . $child->alias . "\n");
            }
        }
    }

    function writeCategoriesWithSingleNesting($categoryObject)
    {
        $handle = fopen("type_b.txt", "a");
        fwrite($handle, $categoryObject->name . "\n");
        foreach($categoryObject->childrens as $child) {
            fwrite($handle, "\t" . $child->name . "\n");
        }
    }

    function clearFiles()
    {
        $handle_a = fopen("type_a.txt", "w");
		$handle_b = fopen("type_b.txt", "w");
		fclose($handle_a);
		fclose($handle_b);
    }
?>