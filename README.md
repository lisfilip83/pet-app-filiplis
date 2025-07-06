# Instalacja aplikacji:

## Kroki instalacji:

1. **Sklonuj repozytorium**
   ```bash
   git clone https://github.com/lisfilip83/pet-app-filiplis.git/
   cd pet-app
   ```

2. **Zainstaluj zależności**
   ```bash
   composer install
   npm install
   ```

3. **Skonfiguruj środowisko**
   ```bash
   cp .env.example .env
   ```

4. **Wygeneruj klucz aplikacji**
   ```bash
   php artisan key:generate
   ```

5. **Skonfiguruj bazę danych**
    - Edytuj plik `.env` i ustaw połączenie z bazą danych
   ```bash
   php artisan migrate
   ```
   LUB
    - użyj załączonej już bazy database.sqlite

6. **Uruchom aplikację**
   ```bash
   # Serwer Laravel
   php artisan serve
   
   # W osobnym terminalu - build zasobów
   npm run dev
   ```

## Opcjonalne:

- **Seed bazy danych**
  ```bash
  php artisan db:seed
  ```

- **Analiza kodu (PHPStan)**
  ```bash
  composer phpstan
  ```

- **Uruchomienie testów**
  ```bash
  php artisan test
  ```

### Dostęp do aplikacji:

- **Aplikacja**: http://localhost/pets

---

## Wymagania:

- PHP 8.4+
- Composer
- Node.js & npm
- MySQL/PostgreSQL/SQLite
```
