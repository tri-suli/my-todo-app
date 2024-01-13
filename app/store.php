<?php

declare(strict_types=1);

require_once './validation.php';

function store (): bool|array {
    $input = $_POST;
    $errors = [];
    $db = new SQLite3('../database/todo.db');

    if (isEmpty($input['title'])) {
        $errors['title'] = 'The task title cannot be empty';
    }

    if (!empty($errors)) {
        return $errors;
    }

    try {
        $priority = 0;
        $db->enableExceptions(true);

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


$result = store();

if (is_array($result)) {
    session_start();
    $_SESSION['errors'] = $result;
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
