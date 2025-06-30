<?php
session_start();
require 'connection.php'; 

if (isset($_SESSION['user_id']) && isset($_GET['plant_id'])) {
    $user_id = intval($_SESSION['user_id']);
    $plant_id = intval($_GET['plant_id']);

    $stmt = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND plant_id = ?");
    $stmt->bind_param("ii", $user_id, $plant_id);

    if ($stmt->execute()) {
        header("Location: userpage.php?success=Plant removed from favorites");
        exit();
    } else {
        header("Location: userpage.php?error=Failed to remove plant from favorites");
        exit();
    }
} else {
    header("Location: userpage.php?error=Invalid request");
    exit();
}
?>
