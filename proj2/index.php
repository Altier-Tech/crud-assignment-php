<?php
// index.php

require_once 'database.php';
require_once 'cars.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cars</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Cars</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Make</th>
                <th>Model</th>
                <th>Year</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cars as $car): ?>
                <tr>
                    <td><?php echo $car['id']; ?></td>
                    <td><?php echo $car['make']; ?></td>
                    <td><?php echo $car['model']; ?></td>
                    <td><?php echo $car['year']; ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="id" value="<?php echo $car['id']; ?>">
                            <input type="submit" name="edit" value="Edit">
                            <input type="submit" name="delete" value="Delete">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button id="new-entry">New Entry</button>

    <div id="create-form" style="display: none;">
        <h2>Create a new car</h2>

        <form method="post">
            <label for="make">Make:</label>
            <input type="text" name="make" required>
            <br>
            <label for="model">Model:</label>
            <input type="text" name="model" required>
            <br>
            <label for="year">Year:</label>
            <input type="number" name="year" required>
            <br>
            <input type="submit" name="create" value="Create">
        </form>
    </div>

    <script>
        var newEntryButton = document.getElementById('new-entry');
        var createForm = document.getElementById('create-form');

        newEntryButton.addEventListener('click', function() {
            createForm.style.display = 'block';
        });
    </script>

    <?php if (isset($_POST['edit'])): ?>
        <?php
        $id = $_POST['id'];

        $stmt = $db->prepare('SELECT * FROM cars WHERE id = :id');
        $stmt->execute(array(
            ':id' => $id
        ));
        $car = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>

        <h2>Edit car</h2>

        <form method="post">
            <input type="hidden" name="id" value="<?php echo $car['id']; ?>">
            <label for="make">Make:</label>
            <input type="text" name="make" value="<?php echo $car['make']; ?>" required>
            <br>
            <label for="model">Model:</label>
            <input type="text" name="model" value="<?php echo $car['model']; ?>" required>
            <br>
            <label for="year">Year:</label>
            <input type="number" name="year" value="<?php echo $car['year']; ?>" required>
            <br>
            <input type="submit" name="update" value="Update">
        </form>
    <?php endif; ?>
</body>
</html>