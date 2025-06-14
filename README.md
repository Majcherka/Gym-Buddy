# 🏋️‍♂️ GymBuddy – System Umawiania Treningów

**GymBuddy** to lekki, rozbudowany system webowy napisany w czystym PHP, wspierany przez PostgreSQL i uruchamiany w środowisku Docker. Aplikacja umożliwia użytkownikom tworzenie profili, wyszukiwanie partnerów treningowych, wysyłanie zaproszeń, dopasowywanie się i zarządzanie dostępnością.

## 📦 Zawartość projektu

- 📁 `api/` – kontrolery obsługujące żądania HTTP
- 📁 `services/` – logika biznesowa i dostęp do bazy danych
- 📁 `models/` – klasy reprezentujące dane domenowe (np. `User`, `Gym`, `Invitation`)
- 📁 `core/` – komponenty wspólne: `Request`, `Response`, `Session`, `Cors`
- 📁 `db/` – klasa `Database` zarządzająca połączeniem z PostgreSQL
- 📁 `frontend/` – statyczne pliki HTML/JS wyświetlające interfejs
- 📁 `config/` – konfiguracja (np. tryb szyfrowania haseł)
- 📄 `Dockerfile`, `docker-compose.yml` – środowisko uruchomieniowe

## 🚀 Jak uruchomić projekt

### 1. Wymagania

- Docker + Docker Compose
- Porty:
  - `8080` – aplikacja webowa
  - `9900` – PostgreSQL (mapowany z `5432`)

### 2. Klonowanie projektu

```bash
git clone https://github.com/Majcherka/Gym-Buddy.git
cd gymbuddy
```

### 3. Budowa i uruchomienie kontenerów

```bash
docker-compose up --build
```

### 4. Otwórz w przeglądarce

```text
http://localhost:8080
```

> Pliki frontendowe znajdują się w katalogu `frontend/views/`.

## ⚙️ Konfiguracja

### Plik konfiguracyjny: `config/app.php`

```php
return [
    'password_encryptor' => 'default'  // Możliwości: default, md5, plaintext
];
```

## 🧠 Architektura aplikacji

### 🔁 Wzorowana na MVC

- **Controller** – odbiera żądanie (np. `UserController`)
- **Service** – logika operacyjna (np. `UserService`)
- **Model** – obiekt reprezentujący dane (np. `User`, `Gym`, `Invitation`)

### 📡 Router

Centralny router w `index.php` (Apache + `.htaccess`) przekierowuje wszystkie żądania do odpowiednich kontrolerów:

```php
'POST /api/login' => ['AuthController', 'login'],
'POST /api/invitations/send' => ['InvitationController', 'send'],
```

### 🧩 Wzorce projektowe

- **Factory + Strategy** – dynamiczny wybór strategii szyfrowania hasła (`PasswordEncryptorFactory`)
- **Builder** – tworzenie obiektu użytkownika (`UserBuilder`)
- **Singleton** – klasa `Database` zarządzająca połączeniem z PostgreSQL
- **Service Layer** – oddzielenie logiki aplikacyjnej od kontrolerów

## 🗄️ Baza danych

- Używany silnik: **PostgreSQL 16**
- Domyślne dane logowania (ustawione w `docker-compose.yml`):

```env
POSTGRES_USER=postgres
POSTGRES_PASSWORD=postgres
POSTGRES_DB=gymbuddy
```

- Inicjalizacja: plik `init_postgres.sql` (automatycznie ładowany przy starcie kontenera)

## 🔐 Logowanie i sesje

- Uwierzytelnienie oparte na `session_start()` i ciasteczkach (`PHPSESSID`)
- Żądania `fetch()` z przeglądarki muszą mieć `credentials: 'include'`
- `Session` obsługiwany przez klasę `core/Session.php`

## 🔄 Obsługa CORS

Zarządzana w `core/Cors.php`, włączona globalnie przez `index.php`:

```php
Cors::apply('http://localhost:63342');
```

## 🔍 Przykładowe endpointy

| Metoda | Endpoint | Opis |
|--------|----------|------|
| POST   | `/api/login` | Logowanie |
| POST   | `/api/register` | Rejestracja |
| POST   | `/api/invitations/send` | Wysyłanie zaproszenia |
| GET    | `/api/invitations/received` | Odebrane zaproszenia |
| POST   | `/api/match` | Zapisanie polubienia lub odrzucenia |
| GET    | `/api/users/random` | Wylosuj użytkownika pasującego do kategorii |

## 🧪 Rozszerzenia / TODO

- ✅ Obsługa zaproszeń (`Invitation`)
- ✅ Polubienia / dopasowania (`Match`)
- ✅ Rejestracja profilu (`save_profile`)
- ⏳ Walidacja adresu email
