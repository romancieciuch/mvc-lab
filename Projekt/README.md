# MVC LAB

Wybór projektu

Zadanie 13 – System monitorowania wydatków domowych

Struktura projektu MVC:
• Model: kategoria, kwota, data
• Kontroler: obsługa żądań HTTP, interakcja z modelem, przekazywanie danych do widoku
• Widok: lista widoków, formularz dodawania i edycji


Środowisko Docker

Aplikacja www:
http://localhost:8080 (katalog ./www)

Baza MySQL:
- port 3306
- host: db
- nazwa: app_db
- user: dev
- hasło: dev

Zarządzanie bazą danych phpMyAdmin:
http://localhost:8081 (dane logowania: dev / dev)

Skrzynka Mailhog:
http://localhost:8025

Uruchomienie projektu w terminalu:
docker compose up -d --build

Wyjście:
Ctrl + C

Wracanie do punktu zero:
docker compose down -v