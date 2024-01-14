<?php
/** @var SQLite3 $db */
require '../app/Enum/Priority.php';
require '../app/Enum/Status.php';
$db = require_once '../app/init.php';
session_start();

$tasks = [];
$taskCount = 0;
try {
    touchDB($db);
    $results = $db->query("SELECT id, title, priority, status, created_at from tasks ORDER BY priority, title limit 10");

    while ($row = $results->fetchArray(1)) {
        $tasks[] = $row;
    }

    $taskCount = $db->query('SELECT COUNT(*) total from tasks')->fetchArray()['total'];
    $taskCompleted = $db->query('SELECT COUNT(*) total from tasks WHERE status = 1')->fetchArray(1)['total'];
} catch (Exception $e) {
    var_dump($e->getMessage());
    die();
} finally {
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/app.css">
    <title>To-Do List App</title>
</head>
<body>
<div class="todo-app">
    <h1>To-Do List</h1>
    <form class="add-todo" action="../app/store.php" method="POST">
        <label for="title" style="display: none;"></label>
        <input type="text" id="title" name="title" placeholder="Title">
        <div class="input-group">
            <select id="priority" name="priority">
                <option selected disabled>Set task priority</option>
                <option value="1">Urgent</option>
                <option value="2">High</option>
                <option value="3">Normal</option>
                <option value="4">Low</option>
            </select>
        </div>
        <div class="input-action">
            <p class="error-message">
                <?php if (isset($_SESSION['errors']['notfound'])): ?>
                    <?php
                        echo $_SESSION['errors']['notfound'];
                        unset($_SESSION['errors']);
                    ?>
                <?php elseif (isset($_SESSION['errors']['title'])): ?>
                    <?php
                        echo $_SESSION['errors']['title'];
                        unset($_SESSION['errors']);
                    ?>
                <?php endif; ?>
            </p>
            <button type="submit">Add</button>
        </div>
    </form>
    <hr class="separator">
    <div class="filters">
        <label>Filter by:</label>
        <label for="priority" hidden="hidden"></label>
        <select id="priority">
            <option value="">Priority</option>
            <option value="high">High</option>
            <option value="medium">Medium</option>
            <option value="low">Low</option>
        </select>
        <label for="status" hidden="hidden"></label>
        <select id="status">
            <option value="">All Status</option>
            <option value="completed">Completed</option>
            <option value="incomplete">Incomplete</option>
        </select>
    </div>
    <ul id="todo-list">
        <?php foreach($tasks as $task): ?>
            <li class="<?php echo $task['status'] === 1 ? 'list-completed' : ''; ?>">
                <div class="todo-info">
                    <h2><?php echo $task['title']?></h2>
                    <p class="todo-details">
                        Priority: <span class="<?php echo $task['status'] === 0 ? sprintf('badge badge-%s', strtolower(\App\Enum\Priority::from($task['priority'])->label())) : ''; ?>"><?php echo \App\Enum\Priority::from($task['priority'])->label(); ?></span>
                    </p>
                </div>
                <div class="todo-actions">
                    <form id="updateStatus" action="../app/update.php" method="POST">
                        <input id="_method" type="text" name="_method" value="PATCH" hidden="hidden">
                        <input id="id" type="text" name="id" value="<?php echo $task['id']; ?>" hidden="hidden">
                        <input type="text" name="completed" value="<?php echo !$task['status']; ?>" hidden="hidden">
                        <button class="status-btn" title="<?php echo $task['status'] === 1 ? 'Mark as In-Completed' : 'Mark as Completed'; ?>" type="submit">
                            <img src="images/status-icon.png" alt="Complete/Incomplete">
                        </button>
                    </form>
                    <?php if ($task['status'] === 0): ?>
                        <form id="deleteTask" action="../app/destroy.php" method="POST">
                            <input id="_method" type="text" name="_method" value="DELETE" hidden="hidden">
                            <input id="id" type="text" name="id" value="<?php echo $task['id']; ?>" hidden="hidden">
                            <button class="remove-btn" title="Delete Task" type="submit">
                                <img src="images/delete-icon.png" alt="Remove">
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <div id="totals">
        <p>Tasks: <span id="total-todos"><?php echo $taskCount; ?></span></p>
        <p>Completed: <span id="completed-todos"><?php echo $taskCompleted; ?></span></p>
    </div>
</div>
<script src="js/app.js"></script>
</body>
</html>