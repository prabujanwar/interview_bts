+==================================+
Instalasi API Money Apps
+==================================+
Hal yang harus dilakukan untuk menjalankan project ini :

Install Depedencies

1. composer install

Setting database dengan sql yang sudah disiapkan 2. import file money-apps.sql pada database

Setting Database 
3. Ubah pada file .env DB_CONNECTION, DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD disesuaikan dengan koneksi db anda

Reset cache 
4. php artisan config:cache

untuk menjalankan aplikasi ketikan 
5. php artisan serve
#   p r a b u - a g e n t  
 