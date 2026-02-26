<?php 
header('Content-Type: text/html; charset=utf-8');
require 'config.php'; 
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>یادنامه جاویدنامان ایران</title>
    <style>
        @font-face { font-family: 'BYekan'; src: url('BYekan.ttf') format('truetype'); }
        body { background: #0a0a0a; color: #e0e0e0; font-family: 'BYekan', Tahoma, sans-serif; margin: 0; padding-top: 80px; text-align: right; }
        nav { background: rgba(0,0,0,0.95); position: fixed; top: 0; width: 100%; z-index: 1000; padding: 15px 0; border-bottom: 1px solid #333; text-align: center; }
        nav a { color: white; text-decoration: none; margin: 0 15px; font-size: 1.1rem; }
        #gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; padding: 20px; max-width: 1400px; margin: auto; }
        .img-container { position: relative; aspect-ratio: 3/4; overflow: hidden; border-radius: 5px; background: #1a1a1a; }
        img { width: 100%; height: 100%; object-fit: cover; filter: grayscale(100%); transition: 0.6s; }
        .overlay { position: absolute; bottom: 0; left: 0; right: 0; background: rgba(192, 57, 43, 0.9); color: white; padding: 15px; transform: translateY(100%); transition: 0.4s ease; text-align: center; }
        .img-container:hover img { filter: grayscale(0%); transform: scale(1.1); }
        .img-container:hover .overlay { transform: translateY(0); }
        footer { text-align: center; padding: 40px; border-top: 1px solid #333; margin-top: 50px; font-size: 0.9rem; color: #888; }
    </style>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-L5CBVRPMW2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-L5CBVRPMW2');
</script>

</head>
<body>
    <nav>
        <a href="index.php">صفحه اصلی</a>
        <a href="upload.html">ارسال عکس</a>
        <a href="about.html">درباره ما</a>
    </nav>
    <div id="gallery">
        <?php
        // ۱. دریافت تمام اطلاعات تایید شده از دیتابیس و ریختن در یک آرایه برای دسترسی سریع
        $martyrsInfo = [];
        $stmt = $pdo->query("SELECT * FROM martyrs WHERE status = 1");
        while($row = $stmt->fetch()){
            $martyrsInfo[$row['image_name']] = [
                'name' => $row['full_name'],
                'desc' => $row['description']
            ];
        }

        // ۲. اسکن کردن پوشه pic برای پیدا کردن تمام فایل‌هایی که با javidnamvatan شروع می‌شوند
        $files = glob("pic/javidnamvatan (*).{jpg,jpeg,png,JPG}", GLOB_BRACE);
        
        // مرتب‌سازی بر اساس شماره (طبیعی)
        natsort($files);

        foreach ($files as $file) {
            $fileName = basename($file);
            echo "<div class='img-container'>";
            echo "<img src='pic/$fileName' loading='lazy'>";
            
            // اگر برای این فایل در دیتابیس اطلاعاتی وجود داشت، آن را نمایش بده
            if (isset($martyrsInfo[$fileName])) {
                echo "<div class='overlay'>";
                echo "<h4>".htmlspecialchars($martyrsInfo[$fileName]['name'])."</h4>";
                echo "<p>".htmlspecialchars($martyrsInfo[$fileName]['desc'])."</p>";
                echo "</div>";
            }
            
            echo "</div>";
        }
        ?>
    </div>
    <footer>این وب‌سایت ارائه‌ای از ثبت یادبود جاویدنامان وطن است.<br><a href="https://javidnamaniran.org" style="color:#c0392b; text-decoration:none;">javidnamaniran.org</a></footer>
</body>
</html>