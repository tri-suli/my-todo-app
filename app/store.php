<?php

declare(strict_types=1);

require_once './validation.php';

function store (array $input): bool|array {
    $errors = [];
    /** @var SQLite3 $db */
    $db = require_once './init.php';
    touchDB($db);

    if (isEmpty($input['title'])) {
        $errors['title'] = 'The task title cannot be empty';
    }

    $statement = $db->prepare('SELECT title from tasks where title = :t');
    $statement->bindValue(':t', $input['title']);
    $query = $statement->getSQL(true);
    $taskTitle = $db->querySingle($query);
    if ($taskTitle === $input['title']) {
        $errors['title'] = sprintf('The task title "%s" is already exists', $taskTitle);
    }

    if (!empty($errors)) {
        return $errors;
    }

    try {
        $priority = 0;

        if (isset($input['priority'])) {
            $priority = $input['priority'];
        }

        $statement = $db->prepare(
            "INSERT INTO tasks (title, priority) values (:t, :p)"
        );
        $statement->bindValue(':t', $input['title']);
        $statement->bindValue(':p', $priority);

        $statement->execute();
        return true;
    } catch (Exception $e) {
        var_dump($e);
        die();
    } finally {
        $db->close();
    }
}


$result = store($_POST);

if (is_array($result)) {
    session_start();
    $_SESSION['errors'] = $result;
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
