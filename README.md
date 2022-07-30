<h1>Instaliacijos instrukcijos</h1>

<p>Sukurkite pasirinktą duomenų bazę ir pervadinkite .env.example failą į .env</p>
<p>Jame pakeiskite DB_ konfigūracijos eilutes prisijungimui prie mysql duomenų bazės.</p>
<p>Paleiskite komandas:</p>
<code>composer install</code><br />
<code>php artisan:migrate</code><br />
<code>php artisan key:generate</code><br />
<code>php artisan passport:install</code><br />
<code>php artisan serve</code>
