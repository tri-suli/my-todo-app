<?php

declare(strict_types=1);

function update (array $attributes): bool {
    /** @var SQLite3 $db */
    $db = require_once './init.php';
    $values = '';

    if (! array_key_exists('_method', $attributes)) {
        return false;
    }

    if (! in_array(strtolower($attributes['_method']), ['put', 'patch'])) {
        return false;
    }
    unset($attributes['_method']);

    if (! array_key_exists('id', $attributes)) {
        return false;
    }
    $taskId = $attributes['id'];
    unset($attributes['id']);

    if (array_key_exists('completed', $attributes)) {
        $values .= "status = :status";
    }

    foreach ($attributes as $key => $value) {
        $values .= "{$key} = :{$key}, ";
    }
    $values = rtrim($values, ', ');

    try {
        touchDB($db);
        $statement = $db->prepare("UPDATE tasks SET {$values} WHERE id = :id");
        $statement->bindValue(':id', intval($taskId));

        if (str_contains($values, 'status')) {
            $statement->bindValue(':status', intval($attributes['status']));
        }

        $query = $statement->getSQL(true);
        $db->exec($query);

        return (bool) $db->changes();
    } catch (Exception $e) {
        var_dump($e->getMessage());
        die();
    } finally {
        $db->close();
    }
}

$updated = update($_POST);

if (!$updated) {
    session_start();
    $_SESSION['errors']['notfound'] = 'Task not found! nothing deleted.';
}

header('Location: /');
exit();

