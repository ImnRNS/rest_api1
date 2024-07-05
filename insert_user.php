<?php
header("Content-Type: application/json");

include 'db_config.php';

// Get the posted data
$data = json_decode(file_get_contents("php://input"));

// Validate the data
if (!isset($data->name) || !isset($data->email)) {
    die(json_encode(["error" => "Invalid input"]));
}

// Escape and sanitize input
$name = $koneksi->real_escape_string($data->name);
$email = $koneksi->real_escape_string($data->email);
$nim = isset($data->nim) ? $koneksi->real_escape_string($data->nim) : null; // Menyimpan nim sebagai teks
$prodi = isset($data->prodi) ? $koneksi->real_escape_string($data->prodi) : null;

// Prepare SQL statement using prepared statement
$sql = "INSERT INTO users (name, email, nim, prodi) VALUES (?, ?, ?, ?)";
$stmt = $koneksi->prepare($sql);

if ($stmt) {
    // Bind parameters and execute query
    // Mengikat parameter dengan tipe data yang sesuai
    // 's' untuk string
    $stmt->bind_param("ssss", $name, $email, $nim, $prodi);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Execute failed: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Prepare failed: " . $koneksi->error]);
}

$koneksi->close();
?>
