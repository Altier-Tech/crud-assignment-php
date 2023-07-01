<?php
// medicines.php

require_once 'database.php';

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