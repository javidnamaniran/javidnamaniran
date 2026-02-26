<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
require 'config.php';
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }

if (isset($_GET['approve'])) {
    $pdo->prepare("UPDATE martyrs SET status = 1 WHERE id = ?")->execute([$_GET['approve']]);
}
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("SELECT image_name FROM martyrs WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $img = $stmt->fetch();
    if($img) { @unlink("pic/".$img['image_name']); }
    $pdo->prepare("DELETE FROM martyrs WHERE id = ?")->execute([$_GET['delete']]);
}

$pending = $pdo->query("SELECT * FROM martyrs WHERE status = 0")->fetchAll();
$approved = $pdo->query("SELECT * FROM martyrs WHERE status = 1")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مدیریت جاویدنامان</title>
    <style>body{font-family:tahoma; direction:rtl; background:#f0f0f0; padding:20px;} .box{background:white; padding:20px; margin-bottom:20px; border-radius:8px;} table{width:100%; border-collapse:collapse;} td,th{border:1px solid #ddd; padding:10px; text-align:center;} img{width:60px;}</style>
</head>
<body>
    <div class="box">
        <a href="login.php?logout=1" style="color:red; float:left;">خروج از پنل</a>
        <h2>در انتظار تایید</h2>
        <table>
            <tr><th>عکس</th><th>نام</th><th>عملیات</th></tr>
            <?php foreach($pending as $p): ?>
            <tr>
                <td><img src="pic/<?= $p['image_name'] ?>"></td>
                <td><?= htmlspecialchars($p['full_name']) ?></td>
                <td>
                    <a href="?approve=<?= $p['id'] ?>" style="color:green;">تایید</a> | 
                    <a href="?delete=<?= $p['id'] ?>" style="color:red;" onclick="return confirm('حذف شود؟')">حذف</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div class="box">
        <h2>تایید شده‌ها</h2>
        <table>
            <?php foreach($approved as $a): ?>
            <tr>
                <td><img src="pic/<?= $a['image_name'] ?>"></td>
                <td><?= htmlspecialchars($a['full_name']) ?></td>
                <td><a href="?delete=<?= $a['id'] ?>" style="color:red;">حذف کامل</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>