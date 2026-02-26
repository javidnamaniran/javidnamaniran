<?php
header("Content-Type: application/xml; charset=utf-8");
require 'config.php';

$baseUrl = "https://javidnamaniran.org/";

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" 
              xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

// ۱. صفحات ثابت سایت
$pages = [
    ['url' => '', 'priority' => '1.0'],
    ['url' => 'index.php', 'priority' => '0.9'],
    ['url' => 'upload.html', 'priority' => '0.8'],
    ['url' => 'about.html', 'priority' => '0.7']
];

foreach ($pages as $page) {
    echo "<url>
            <loc>{$baseUrl}{$page['url']}</loc>
            <changefreq>daily</changefreq>
            <priority>{$page['priority']}</priority>
          </url>";
}

// ۲. دریافت اطلاعات از دیتابیس برای کپشن تصاویر
$martyrsInfo = [];
try {
    $stmt = $pdo->query("SELECT * FROM martyrs WHERE status = 1");
    while($row = $stmt->fetch()){
        $martyrsInfo[$row['image_name']] = $row['full_name'];
    }
} catch (Exception $e) {}

// ۳. اسکن تصاویر و ثبت در سایت‌مپ
$dir = "pic/";
if (is_dir($dir)) {
    $allFiles = scandir($dir);
    foreach ($allFiles as $file) {
        // شناسایی فایل‌های معتبر
        if (stripos($file, 'javidnamvatan') !== false && preg_match('/\.(jpg|jpeg|png)$/i', $file)) {
            
            $caption = "جاویدنام وطن";
            if (isset($martyrsInfo[$file])) {
                $caption = "جاویدنام " . htmlspecialchars($martyrsInfo[$file]);
            }

            echo "<url>
                    <loc>{$baseUrl}index.php#" . urlencode($file) . "</loc>
                    <image:image>
                        <image:loc>{$baseUrl}pic/" . rawurlencode($file) . "</image:loc>
                        <image:caption>{$caption}</image:caption>
                        <image:title>یادمان جاویدنامان ایران و جان فدایان وطن </image:title>
                    </image:image>
                  </url>";
        }
    }
}

echo '</urlset>';
?>