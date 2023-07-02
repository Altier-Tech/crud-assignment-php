<?php

require_once 'database.php';

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
