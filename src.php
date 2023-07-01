<?php
// Connect to the database
$dsn = 'mysql:host=localhost;dbname=drugs';
$username = 'root';
$password = '';
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
);

try {
    $db = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

// Create the 'medicines' table if it doesn't exist
$stmt = $db->query('CREATE TABLE IF NOT EXISTS medicines (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (id)
)');

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a new record
    if (isset($_POST['create'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        $stmt = $db->prepare('INSERT INTO medicines (name, description, price) VALUES (:name, :description, :price)');
        $stmt->execute(array(
            ':name' => $name,
            ':description' => $description,
            ':price' => $price
        ));
    }

    // Update an existing record
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        $stmt = $db->prepare('UPDATE medicines SET name = :name, description = :description, price = :price WHERE id = :id');
        $stmt->execute(array(
            ':id' => $id,
            ':name' => $name,
            ':description' => $description,
            ':price' => $price
        ));
    }

    // Delete a record
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $stmt = $db->prepare('DELETE FROM medicines WHERE id = :id');
        $stmt->execute(array(
            ':id' => $id
        ));
    }
}

// Retrieve all records
$stmt = $db->query('SELECT * FROM medicines');
$medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Medicines</title>
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