<?php
// Connect to the database
$dsn = 'mysql:host=localhost;dbname=vehicles';
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

// Create the 'cars' table if it doesn't exist
$stmt = $db->query('CREATE TABLE IF NOT EXISTS cars (
    id INT(11) NOT NULL AUTO_INCREMENT,
    make VARCHAR(255) NOT NULL,
    model VARCHAR(255) NOT NULL,
    year INT(4) NOT NULL,
    PRIMARY KEY (id)
)');

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a new record
    if (isset($_POST['create'])) {
        $make = $_POST['make'];
        $model = $_POST['model'];
        $year = $_POST['year'];

        $stmt = $db->prepare('INSERT INTO cars (make, model, year) VALUES (:make, :model, :year)');
        $stmt->execute(array(
            ':make' => $make,
            ':model' => $model,
            ':year' => $year
        ));
    }

    // Update an existing record
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $make = $_POST['make'];
        $model = $_POST['model'];
        $year = $_POST['year'];

        $stmt = $db->prepare('UPDATE cars SET make = :make, model = :model, year = :year WHERE id = :id');
        $stmt->execute(array(
            ':id' => $id,
            ':make' => $make,
            ':model' => $model,
            ':year' => $year
        ));
    }

    // Delete a record
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $stmt = $db->prepare('DELETE FROM cars WHERE id = :id');
        $stmt->execute(array(
            ':id' => $id
        ));
    }
}

// Retrieve all records
$stmt = $db->query('SELECT * FROM cars');
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cars</title>
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