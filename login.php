<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
if(isset($_GET['logout'])) { session_destroy(); header("Location: login.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if($_POST['user'] == 'admin' && $_POST['pass'] == 'iran1402') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit;
    } else { $error = "اطلاعات اشتباه است."; }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head><meta charset="UTF-8"><title>ورود مدیر</title></head>
<body style="background:#0a0a0a; color:white; font-family:tahoma; text-align:center; padding-top:100px;">
    <form method="POST">
        <h2>ورود به پنل مدیریت</h2>
        <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
        <input type="text" name="user" placeholder="نام کاربری" style="padding:10px; margin:5px;"><br>
        <input type="password" name="pass" placeholder="رمز عبور" style="padding:10px; margin:5px;"><br>
        <button type="submit" style="padding:10px 40px; background:#c0392b; color:white; border:none; cursor:pointer;">ورود</button>
    </form>
</body>
</html>