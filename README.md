<h1>Instaliacijos instrukcijos</h1>

<p>Sukurkite pasirinktą duomenų bazę ir pervadinkite .env.example failą į .env</p>
<p>Jame pakeiskite DB_ konfigūracijos eilutes prisijungimui prie mysql duomenų bazės.</p>
<p>Paleiskite komandas:</p>
<code>
composer install<br />
php artisan:migrate<br />
php artisan key:generate<br />
php artisan passport:install<br />
php artisan serve
</code>