<?php
header('Content-Type: text/html; charset=utf-8');
require 'config.php';
$targetDir = "pic/";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $fullName = $_POST['name'];
    $description = $_POST['desc'];

    $files = glob($targetDir . "javidnamvatan (*).jpg");
    $maxNumber = 249;
    foreach ($files as $file) {
        if (preg_match('/\((\d+)\)/', $file, $matches)) {
            $num = (int)$matches[1];
            if ($num > $maxNumber) $maxNumber = $num;
        }
    }
    $newFileName = "javidnamvatan (" . ($maxNumber + 1) . ").jpg";

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $newFileName)) {
        $stmt = $pdo->prepare("INSERT INTO martyrs (full_name, description, image_name, upload_date, status) VALUES (?, ?, ?, NOW(), 0)");
        $stmt->execute([$fullName, $description, $newFileName]);
        
        echo "<!DOCTYPE html><html lang='fa' dir='rtl'><head><meta charset='UTF-8'></head><body style='background:#0a0a0a; color:white; font-family:tahoma; text-align:center; padding-top:100px;'>";
        echo "<h2>ارسال با موفقیت انجام شد.</h2><p>پس از تایید مدیر، نمایش داده می‌شود.</p>";
        echo "<a href='index.php' style='color:red;'>بازگشت</a></body></html>";
    }
}
?>