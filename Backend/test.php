<?php
$jsonFile = "C:/xampp/htdocs/config/carwash-firebase-adminsdk.json";

if (file_exists($jsonFile)) {
    echo "✅ JSON file found at: " . $jsonFile;
} else {
    echo "❌ JSON file not found! Check the path.";
}
?>