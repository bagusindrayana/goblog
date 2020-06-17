## **GOBLOG**

Simple Blog System With Laravel
Fitur
- Gutenberg Editor like Wordpress
- Page Builder

![enter image description here](https://i.ibb.co/TcJw9ft/Fire-Shot-Capture-129-Goblog-goblog-dev-com.png)

![enter image description here](https://i.ibb.co/S7V0V2L/Fire-Shot-Capture-130-Add-Page-New-Page-goblog-dev-com.png)

![enter image description here](https://i.ibb.co/bL36F18/Fire-Shot-Capture-131-Goblog-goblog-dev-com.png)

**menjalankan aplikasi**

 1. download/clone repo ini lalu dan masuk ke direktorinya/foldernya
 2. jalankan perintah `composer install`
 3. jalankan perintah `npm install`
 4. copy file `.env.example` lalu rename jadi `.env`
 5. isi semua parameter yang ada di `.env` seperti host,username,database,password,tb_prefix dll
 6. buat akun [http://disqus.com/](http://disqus.com/) untuk mengisi `EMBED_DISQUS`
 9. jalankan perintah `php artisan key:generate`
 10. jalankan perintah `php artisan migrate`
 11. jalankan perintah `php artisan db:seed` 
  11. dan jalankan `php artisan serve` lalu buka [http://127.0.0.1:8000/](http://127.0.0.1:8000/) di browser

Library pihak ketiga yang di gunakan
- Laraberg - Wordpress Gutenberg Editor [https://github.com/VanOns/laraberg](https://github.com/VanOns/laraberg)
- GrapeJs - Page Builder [http://grapesjs.com/](http://grapesjs.com/)
- Laravel Filemanager [https://github.com/UniSharp/laravel-filemanager](https://github.com/UniSharp/laravel-filemanager)
- Disqus - Comment System [https://disqus.com/](https://disqus.com/)

***sistem ini belum final jadi setiap ada perubahan database harus menjalankan migration dari awal lagi dengan `php artisan migrate:fresh`***
