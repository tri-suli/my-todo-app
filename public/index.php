<?php
    $db = require_once '../app/init.php';
    session_start();

    $tasks = [];
    try {
        touchDB($db);
        $results = $db->query("SELECT id, title, priority, status, created_at from tasks limit 10");

        while ($row = $results->fetchArray(1)) {
            $tasks[] = $row;
        }
    } catch (Exception $e) {
        var_dump($e->getMessage());
        die();
    } finally {
        $db->close();
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Todo</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <?php echo 'Todo List App'; ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form id="task" action="../app/store.php" method="POST">
                    <div class="form-control">
                        <label for="title">Title</label>
                        <input id="title" name="title" type="text">
                        <?php if (isset($_SESSION['errors']['title'])): ?>
                            <p class="error-message">
                                <?php
                                echo $_SESSION['errors']['title'];
                                unset($_SESSION['errors']);
                                ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="form-control">
                        <label for="priority">Priority</label>
                        <select id="priority" name="priority">
                            <option value="" selected disabled>Choose one</option>
                            <option value="1">Urgent</option>
                            <option value="2">High</option>
                            <option value="3">Normal</option>
                            <option value="4">Low</option>
                        </select>
                    </div>

                    <div class="action">
                        <button class="btn" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-6">
                        <?php if (isset($_SESSION['errors']['notfound'])): ?>
                            <p class="error-message">
                                <?php
                                    echo $_SESSION['errors']['notfound'];
                                    unset($_SESSION['errors']);
                                ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <table class="table-responsive">
                    <thead>
                        <?php foreach (['Title', 'Priority', 'Status', 'Created On'] as $header): ?>
                            <th><?php print $header; ?></th>
                        <?php endforeach; ?>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $i => $task): ?>
                            <tr>
                                <td><?php print $i+1; ?></td>
                                <td><?php print $task['title']; ?></td>
                                <td><?php print $task['priority']; ?></td>
                                <td><?php print $task['status']; ?></td>
                                <td><?php print $task['created_at']; ?></td>
                                <td>
                                    <?php if($task['status'] === 1): ?>
                                        <span><strong>Completed</strong></span>
                                    <?php else: ?>
                                        <form action="../app/update.php" method="POST">
                                            <input type="hidden" name="_method" value="PATCH">
                                            <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                                            <input type="hidden" name="status" value="1">
                                            <button class="btn btn-sm btn-danger" type="submit">Mark as Complete</button>
                                        </form>
                                        <form action="../app/destroy.php" method="POST">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                                            <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                        </form>
                                    <?php endif;?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="js/app.js"></script>
</body>
</html>