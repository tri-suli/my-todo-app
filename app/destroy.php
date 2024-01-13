<?php

declare(strict_types=1);

function destroy (array $attributes): bool {
    /** @var SQLite3 $db */
    $db = require_once './init.php';

    if (! array_key_exists('_method', $attributes)) {
        return false;
    }

    if (strtolower($attributes['_method']) !== 'delete') {
        return false;
    }
    unset($attributes['_method']);

    if (! array_key_exists('id', $attributes)) {
        return false;
    }
    $taskId = $attributes['id'];
    unset($attributes['id']);

    try {
        touchDB($db);
        $statement = $db->prepare('DELETE FROM tasks WHERE id = :id');
        $statement->bindValue(':id', $taskId);

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

$deleted = destroy($_POST);

if (!$deleted) {
    session_start();
    $_SESSION['errors']['notfound'] = 'Task not found! nothing deleted.';
}

header('Location: /');
exit();

