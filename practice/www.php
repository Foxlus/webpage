<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>www</title>
    <link rel="stylesheet" type="text/css" href="a.css" charset="utf-8">

</head>
<body>
    <div class="text-container">
        <?php
            echo nl2br('
            ---------- Ubuntu 14.04 Apache2.4目錄結構----------

            /etc/apache2/
            |-- apache2.conf     主要的設定檔
            |-- conf-available   可用的config檔
            |       |-- *.conf   
            |-- conf-enabled     已啟用的的config檔(皆為超連結)
            |-- mods-available   可用的config檔
            |       |-- *.conf   
            |-- mods-enabled     已啟用的的config檔(皆為超連結)
            |-- sites-available  可用的站台設定檔
            |       |-- *.conf   
            |-- sites-enabled    已啟用的的台設定檔(皆為超連結)
            
            ->啟用及關閉設定
            1. conf啟用及關閉範例
            啟用 sudo a2enconf charset
            關閉 sudo a2disconf charset
            2. mod啟用及關閉範例
            啟用 sudo a2enmod userdir
            關閉 sudo a2dismod userdir
            3. site啟用及關閉範例
            啟用 sudo a2ensite ssl
            關閉 sudo a2dissite ssl
            
            ----------架設AMP---------
            
            1.sudo apt-get update && sudo apt-get upgrade
            2.sudo apt-get install apache2
            3.sudo apt-get install mysql-server
            4.you will be asked to enter a password for the MySQL ‘root’ user
            5.mysql_secure_installation
            6.如果不需調整高強度安全性則按n
            7.next, you will be asked whether you want to change the password for ‘root
                **(ubuntu>18之後，不需設定密碼，直接系統匹配或root直接登入)
                **(如果需要修改密碼->sudo mysql->ALTER USER  "root"@"localhost" IDENTIFIED WITH mysql_native_password BY "password"; 
                   -> FLUSH PRIVILEGES; ->密碼修改為 "password" )
            8.For remaining questions, type y and press enter at each prompt.
            9.sudo apt-get install php libapache2-mod-php php-mcrypt php-mysql **(php-mcrypt在ubuntu>18之後就沒有支援了)
            
            *[for test]
            *10.sudo nano /var/www/html/info.php
            *11.type "<?php phpinfo(); ?>"
            *12.http://server_ip_address/info.php[於網頁查看是否顯示資訊
            *13.sudo rm /var/www/html/info.php
            
            ----------更改Apache服务器預設根目录---------
            
            1.修改/etc/apache2/apache2.conf裡面的"<Directory "路徑">
            2.修改/etc/apache2/sites-available/000-default.conf裡面DoucmentRoot "路徑"
            3.修改/etc/apache2/sites-available/default-ssl.conf裡面DoucmentRoot "路徑"
            4.sudo service apache2 restart(重新啟動)
            
            -----------讓使用者的資料夾變成獨立的網頁環境----------
            
            1.sudo a2enmod userdir  (啟用userdir功能)
            2.sudo vim /etc/apache2/mods-enabled/userdir.conf (查看userdir的設定檔userdir.conf)
            3.sudo service apache2 restart  (重新啟動apache伺服器)
            4.之後只要使用者有建立public_html資料夾，就能在http://localhost/~user/上顯示出來了
            
            ----------目錄相關之權限設定----------
            範例：
            ########################################
            #<Directory />                         #
            #    Options FollowSymLinks            #
            #    AllowOverride None                #
            #</Directory>                          #
            #                                      #
            #<Directory "/var/www/html">           #
            #    Options Indexes FollowSymLinks    #
            #    AllowOverride None                #
            #    Order allow,deny                  #
            #    Allow from all                    #
            #</Directory>                          #
            ########################################
            
            Options (目錄參數)：
                Indexes：如果在此目錄下找不到『首頁檔案 (預設為 index.html) 』時， 就顯示整個目錄下的檔案名稱(不安全)
                FollowSymLinks：預設的情況下，在"路徑"底下的連結檔只要連結到非此目錄的其他地方，則該連結檔預設是失效的。使用此設定即可讓連結檔有效的離開本目錄。
                ExecCGI：讓此目錄具有執行 CGI 程式的權限
                Includes：讓一些 Server-Side Include 程式可以運作。
                MultiViews：在同一部主機中，可以依據用戶端的語系而給予不同的語言顯示
            AllowOverride (允許的覆寫參數功能、表示是否允許額外設定檔 .htaccess 的某些參數覆寫)：
                ALL：全部的權限均可被覆寫；
                AuthConfig：僅有網頁認證 (帳號密碼) 可覆寫；
                Indexes：僅允許 Indexes 方面的覆寫；
                Limits：允許使用者利用 Allow, Deny 與 Order 管理可瀏覽的權限；
                None：不可覆寫，亦即讓 .htaccess 檔案失效！
            Order, Allow, Deny (能否登入瀏覽的權限)：
                deny,allow：以 deny 優先處理，但沒有寫入規則的則預設為 allow
                allow,deny：以 allow 為優先處理，但沒有寫入規則的則預設為 deny
                在預設的環境中，因為是 allow,deny 所以預設為 deny (不可瀏覽)，不過在下一行有個 Allow from all，allow 優先處理，因此全部 (all) 用戶端皆可瀏覽
            
            ---------設定Alias----------
            
            範例：
            #########################################################
            #Alias /icons/ "/var/www/icons/"  <==製作一個目錄別名 	#
            #<Directory "/var/www/icons">				#
            #    Options Indexes MultiViews				#
            #    AllowOverride None					#	
            #    Order allow,deny					#
            #    Allow from all					#
            #</Directory>                     			#
            #########################################################
            
            在/var/www/html 並沒有 icons 那個目錄，不過由於 Alias (別名) 的關係，會讓該網址直接連結到 /var/www/icons/ 下
            
            -----------.htaccess檔案----------
            
            ※※※記得Allowoverride設為all※※※
            ※※※將php_admin_flag engine Off註解 => 讓htaccess可以動(在mode-enable裡的php7...)※※※
            
            ‎使用.htaccess檔可以創建和應用重寫規則，而無需訪問伺服器配置檔。 
            通過將.htaccess檔放在網站的根目錄中，您可以基於每個網站或每個目錄管理重寫。
            
            1.sudo a2enmod rewrite  (‎為了讓Apache瞭解重寫規則，我們首先需要啟動mod_rewrite。)
            2.‎RewriteEngine on (在新文件的頂部添加此行以啟動重寫引擎 )
            3.撰寫規則
            
            RewriteCond：定義一條重寫測試規則，且測試
            ‎	RewriteCond指令允許我們為重寫規則添加條件，以控制何時處理規則‎
                ‎如果RewriteCond評估為true，則將考慮緊隨其後的RewriteRule。 如果不相關，則該規則將被丟棄‎
                ‎多個RewriteCond可可以一個接一個的使用，並且，對於默認行為，所有的值都必須為true，才能考慮下面的規則‎
                格式：RewriteCond TestString Condition[Flags]  TestString是要测试的字符串、Condition是匹配的模式或条件
                範例：RewriteCond%{REQUEST_FILENAME}!-f
                      RewriteCond%{REQUEST_FILENAME}!-d
                      RewriteRule./
                     =>%{REQUEST_FILENAME}是要检查的字符串，它是请求的文件名，它是可用于每个请求的系统变量
                    =>-f是一个内置条件，它验证所请求的名称是否存在于磁盘上并且是一个文件
                    =>仅当指定的名称不存在或不是目录时，!-d的评估结果才为true
                    =>最终线的RewriteRule只为=有当请求不存在的文件或目录时才将生效	
            RewriteRule：取得重寫字串，並套入重寫規則
                格式： RewriteRule pattern substitution[flags]
                範例：RewriteRule ^about$ about.html[NC]
                    => ^表示your_server_ip/之后的URL的开头，$表示URL的结尾，about匹配字符串“about”，about.html是用户访问的实际文件，[NC]是一个使规则不区分大小写的标志
                範例：RewriteRule^shirt/summer$ results.php?item=shirt&season=summer[QSA]
                    =>‎[QSA]標誌‎告訴Apache將任何其他查詢字串附加到提供的URL‎，‎shirt/summer在請求的位址被明確的匹配而且Apache被告知去服務results.php？item=shirt&season=summer‎
                範例：RewriteRule^([A-Za-z0-9]+)/(summer|winter|fall|spring)results.php?item=$1&season=$2[QSA]
                    =>括弧中的第一正則表達式，字‎符串相匹配並將匹配片段保存為$1變數‎，以此類推
            RewriteLog：可以紀錄重寫規則
            RewriteLogLevel：紀錄重寫規則的複雜程度，最小 0 最大 9，9 就是什麼都有
            RewriteBase：重寫規則以哪一個網址層級為基準
            RewriteOptions：最常用是 MaxRedirects=number，限制重寫次數，避免改到服務器掛掉
            
            範例：
            ###########################################################################################################################
            # 打開重寫引擎														  #
            #RewriteEngine On			 										  #
            # 設定基準目錄，從根目錄開始比對 											  #
            #RewriteBase /														  #								
            # 比對網址，倘若符合 thumbnail，則轉址到 cache 底下，至此結束。                                                           #
            #RewriteRule ^(thumbnail)/([0-9]+/.+)$ cache/$1/$2 [L]   								  #
            # 重新比對網址，倘若結尾是 mp3, mov, ogg, mp4, avi, wmv，不做任何替代，直接 404，至此結束。 				  #
            #RewriteRule (.[^\.]).(mp3|mov|ogg|mp4|avi|wmv)$ - [NC,F,L] 								  #
            # 設定比對條件，若請求的網址是檔案。   											  #
            #RewriteCond %{REQUEST_FILENAME} -f 											  #
            # 重新比對網址，倘若是 cache，但是非 sitemap 底下，結尾是 .cache, .xml, .txt, .log，不做任何替代，直接 404，至此結束。    #
            #RewriteRule ^(cache)+/+([^sitemap/])(.+[^/]).(cache|xml|txt|log)$ - [NC,F,L]						  #
            # 重新比對網址，倘若請求網址是資料夾。											  #
            #RewriteCond %{REQUEST_FILENAME} -d										          #
            # 在結尾加上 /，至此結束。  												  #
            #RewriteRule ^(.+[^/])$ $1/ [L] 											  #
            # 重新比對網址，倘若請求網址不是檔案。											  #
            #RewriteCond %{REQUEST_FILENAME} !-f 											  #
            # 將請求網址轉給 rewrite.php 這支檔案，至此結束，並連同 Query String 一併傳入。						  #
            #RewriteRule ^(.*)$ rewrite.php [L,QSA]										 	  #
            ###########################################################################################################################
                        
            ');?>
    </div>
</body>
</html>