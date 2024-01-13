<?php

declare(strict_types=1);

const BASE_PATH = __DIR__;
const DATABASE_PATH = BASE_PATH . '/../database';

$db = new SQLite3(sprintf('%s/todo.db', DATABASE_PATH));
$db->enableExceptions(true);

try {
    $statements = file_get_contents(sprintf('%s/migrations/tasks.sql', DATABASE_PATH));
    $db->exec($statements);
} catch (Exception $e) {
    var_dump($e->getMessage());
    die();
} finally {
    $db->close();
}

function touchDB ($db): void
{
    $db->open(sprintf('%s/todo.db', DATABASE_PATH));
}

return $db;
