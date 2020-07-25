Petunjuk Aplikasi:

- "composer install" lalu "npm install"
- create database
- rename .env.example menjadi .env dan atur konfigurasi db
- php artisan key:generate
- php artisan optimize
- Untuk create table sudah sudah saya sediakan migration, bisa dijalankan dengan perintah 'php artisan migrate'
- Untuk user agar bisa login, sudah saya sediakan seeder, bisa dijalankan dengan perintah 'php artisan db:seed' dengan detil username : admin & password : 12345678
- Apabila gagal, file backup db sudah saya sediakan dengan nama db_valley.sql
- Lalu generate passport dengan perintah "php artisan passport:install"
- Setelah itu, compile dengan perintah "npm run prod"
- Untuk soal file excel juga sudah saya sediakan pada repositori ini dengan nama file test-programmer.xls
- Terima kasih
