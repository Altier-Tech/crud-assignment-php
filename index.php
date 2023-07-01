<?php
// index.php

require_once 'database.php';
require_once 'medicines.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Medicines</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Medicines</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($medicines as $medicine): ?>
                <tr>
                    <td><?php echo $medicine['id']; ?></td>
                    <td><?php echo $medicine['name']; ?></td>
                    <td><?php echo $medicine['description']; ?></td>
                    <td><?php echo $medicine['price']; ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="id" value="<?php echo $medicine['id']; ?>">
                            <input type="submit" name="edit" value="Edit">
                            <input type="submit" name="delete" value="Delete">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Create a new medicine</h2>

    <form method="post">
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <br>
        <label for="description">Description:</label>
        <textarea name="description" required></textarea>
        <br>
        <label for="price">Price:</label>
        <input type="number" name="price" required>
        <br>
        <input type="submit" name="create" value="Create">
    </form>

    <?php if (isset($_POST['edit'])): ?>
        <?php
        $id = $_POST['id'];

        $stmt = $db->prepare('SELECT * FROM medicines WHERE id = :id');
        $stmt->execute(array(
            ':id' => $id
        ));
        $medicine = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>

        <h2>Edit medicine</h2>

        <form method="post">
            <input type="hidden" name="id" value="<?php echo $medicine['id']; ?>">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo $medicine['name']; ?>" required>
            <br>
            <label for="description">Description:</label>
            <textarea name="description" required><?php echo $medicine['description']; ?></textarea>
            <br>
            <label for="price">Price:</label>
            <input type="number" name="price" value="<?php echo $medicine['price']; ?>" required>
            <br>
            <input type="submit" name="update" value="Update">
        </form>
    <?php endif; ?>
</body>
</html>
