<h1>Instaliacijos instrukcijos</h1>

Sukurkite pasirinktą duomenų bazę ir pervadinkite .env.example failą į .env
Jame pakeiskite DB_ konfigūracijos eilutes prisijungimui prie mysql duomenų bazės.
Paleiskite komandas:
```composer install```
```php artisan:migrate```
```php artisan key:generate```
```php artisan passport:install```
```php artisan serve```