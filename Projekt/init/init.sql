SET NAMES utf8mb4;

-- Tabela użytkownicy
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    token VARCHAR(100) NULL,
    active BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela kont użytkownika (np. emerytalne, oszczędnościowe)
CREATE TABLE IF NOT EXISTS accounts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    balance DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
	currency VARCHAR(3) NOT NULL,
	priority TINYINT NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_account_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela kategorii przypisanych do konkretnego konta
CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    account_id INT UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
	color VARCHAR(7) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_category_account
        FOREIGN KEY (account_id)
        REFERENCES accounts(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela transakcji (wydatków i zysków)
CREATE TABLE IF NOT EXISTS transactions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    account_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NULL,
    amount DECIMAL(15, 2) NOT NULL,
    name VARCHAR(255) NULL,
	description TEXT NULL,
    transaction_date DATE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_transaction_account
        FOREIGN KEY (account_id)
        REFERENCES accounts(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_transaction_category
        FOREIGN KEY (category_id)
        REFERENCES categories(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela historii transakcji
CREATE TABLE IF NOT EXISTS transaction_history (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT UNSIGNED NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    log_date DATE NOT NULL,
    UNIQUE KEY unique_transaction_date (transaction_id, log_date),
    CONSTRAINT fk_history_transaction
        FOREIGN KEY (transaction_id)
        REFERENCES transactions(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela historii sald kont
CREATE TABLE IF NOT EXISTS account_history (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    account_id INT UNSIGNED NOT NULL,
    balance DECIMAL(15, 2) NOT NULL,
    log_date DATE NOT NULL,
    UNIQUE KEY unique_account_date (account_id, log_date),
    CONSTRAINT fk_history_account
        FOREIGN KEY (account_id)
        REFERENCES accounts(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Wyzwalacze - automatyczna aktualizacja salda konta po dowolnych operacjach na tym koncie
-- Zmiana znaku końca zapytania, aby MySQL nie przerwał tworzenia triggera w połowie
DELIMITER //

-- 1. Wyzwalacz po dodaniu nowej transakcji (INSERT)
CREATE TRIGGER po_dodaniu_transakcji
AFTER INSERT ON transactions
FOR EACH ROW
BEGIN

	-- Historia transakcji: Dodajemy pierwszy wpis historyczny z dzisiejszą datą
    INSERT INTO transaction_history (transaction_id, amount, log_date)
    VALUES (NEW.id, NEW.amount, CURDATE());

	-- Aktualizacja salda
    UPDATE accounts
    SET balance = balance + NEW.amount
    WHERE id = NEW.account_id;

END //

-- 2. Wyzwalacz po usunięciu transakcji (DELETE)
CREATE TRIGGER po_usunieciu_transakcji
AFTER DELETE ON transactions
FOR EACH ROW
BEGIN

    UPDATE accounts
    SET balance = balance - OLD.amount
    WHERE id = OLD.account_id;

END //

-- 3. Wyzwalacz po edycji transakcji (UPDATE)
CREATE TRIGGER po_edycji_transakcji
AFTER UPDATE ON transactions
FOR EACH ROW
BEGIN

	-- Historia transakcji: Uruchamiamy tylko, jeśli kwota faktycznie uległa zmianie
    IF OLD.amount != NEW.amount THEN
        INSERT INTO transaction_history (transaction_id, amount, log_date)
        VALUES (NEW.id, NEW.amount, CURDATE())
        ON DUPLICATE KEY UPDATE amount = NEW.amount;
    END IF;

    -- Sprawdzamy, czy transakcja została przeniesiona na inne konto
    IF OLD.account_id = NEW.account_id THEN
        -- Jeśli konto jest to samo, aktualizujemy tylko różnicę kwot
        UPDATE accounts
        SET balance = balance - OLD.amount + NEW.amount
        WHERE id = NEW.account_id;
    ELSE
        -- Jeśli zmieniono przypisanie do konta, odejmujemy ze starego i dodajemy do nowego
        UPDATE accounts
        SET balance = balance - OLD.amount
        WHERE id = OLD.account_id;

        UPDATE accounts
        SET balance = balance + NEW.amount
        WHERE id = NEW.account_id;
    END IF;

END //


-- --------------------------------------------------------
-- 4. Wyzwalacz po dodaniu nowego konta (INSERT)
-- --------------------------------------------------------
DROP TRIGGER IF EXISTS po_dodaniu_konta //
CREATE TRIGGER po_dodaniu_konta
AFTER INSERT ON accounts
FOR EACH ROW
BEGIN
    -- Logujemy początkowe saldo (zazwyczaj 0.00, ale warto mieć to na osi czasu)
    INSERT INTO account_history (account_id, balance, log_date)
    VALUES (NEW.id, NEW.balance, CURDATE());
END //

-- --------------------------------------------------------
-- 5. Wyzwalacz po edycji konta (UPDATE)
-- --------------------------------------------------------
DROP TRIGGER IF EXISTS po_edycji_konta //
CREATE TRIGGER po_edycji_konta
AFTER UPDATE ON accounts
FOR EACH ROW
BEGIN
    -- Sprawdzamy, czy saldo faktycznie uległo zmianie
    -- (żeby nie tworzyć logów, gdy edytujesz tylko nazwę konta)
    IF OLD.balance != NEW.balance THEN

        -- Mechanizm "Upsert" - wstawia nowy dzień lub aktualizuje dzisiejszą końcówkę
        INSERT INTO account_history (account_id, balance, log_date)
        VALUES (NEW.id, NEW.balance, CURDATE())
        ON DUPLICATE KEY UPDATE balance = NEW.balance;

    END IF;
END //


-- Przywrócenie standardowego średnika jako separatora
DELIMITER ;



-- Dane startowe

-- 0. Tworzymy użytkownika Jan Kowalski, jan.kowalski@gmail.com, 123456abcd
INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `token`, `active`) VALUES
(1, 'Jan Kowalski', 'jan.kowalski@gmail.com', '$2y$10$ZdJacckGfs4lrr87hRIgUOpQwZesKTC3H7EamTSIB3x9qEPbKWNoe', NULL, 1);

-- 1. Tworzymy dwa konta dla użytkownika o id = 1
INSERT INTO accounts (id, user_id, name, balance, currency, priority) VALUES
(1, 1, 'Konto bieżące', 0.00, 'PLN', 57),
(2, 1, 'Konto oszczędnościowe', 0.00, 'PLN', 65),
(3, 1, 'Konto walutowe', 0.00, 'EUR', 71);

-- 2. Dodajemy kategorie dla konta bieżącego (account_id = 1)
INSERT INTO categories (id, account_id, name, color) VALUES
(1, 1, 'Wypłata', '#059669'),
(2, 1, 'Zakupy spożywcze', '#ea580c'),
(3, 1, 'Paliwo', '#e11d48');

-- 3. Dodajemy kategorie dla konta oszczędnościowego (account_id = 2)
INSERT INTO categories (id, account_id, name, color) VALUES
(4, 2, 'Odsetki', '#7c3aed'),
(5, 2, 'Wpłata własna', '#2563eb');

-- 4. Dodajemy kategorie dla konta walutowego (account_id = 3)
INSERT INTO categories (id, account_id, name, color) VALUES
(6, 3, 'Barcelona', '#b31446'),
(7, 3, 'Berlin', '#2563eb');

-- 5. Rejestrujemy transakcje dla konta bieżącego
INSERT INTO transactions (account_id, category_id, amount, name, transaction_date) VALUES
(1, 1, 5000.00, 'Wypłata za marzec', '2026-03-10'),
(1, 2, -150.50, 'Zakupy w Biedronce', '2026-03-12'),
(1, 3, -200.00, 'Tankowanie na Orlenie', '2026-03-15'),
-- Symulacja transakcji, która straciła kategorię (np. kategoria została usunięta)
(1, NULL, -50.00, 'Abonament za telefon', '2026-03-16'),
(1, 2, -230.40, 'Duże zakupy w Lidlu', '2026-03-18'),
(1, 3, -150.00, 'Tankowanie na stacji Shell', '2026-03-20'),
(1, 2, -45.99, 'Piekarnia i drobne zakupy', '2026-03-21'),
(1, NULL, -120.00, 'Wyjście do kina i popcorn', '2026-03-22'),
(1, 2, -320.50, 'Zakupy na cały tydzień - Biedronka', '2026-03-25'),
(1, 1, 500.00, 'Premia kwartalna', '2026-03-28'),
(1, 3, -250.00, 'Pełny bak - Orlen', '2026-04-02'),
(1, NULL, -89.90, 'Subskrypcje Netflix i Spotify', '2026-04-05'),
(1, 2, -18.50, 'Żabka - przekąski i woda', '2026-04-08'),
(1, 1, 5000.00, 'Wypłata za kwiecień', '2026-04-10');

-- 6. Rejestrujemy transakcje dla konta oszczędnościowego
INSERT INTO transactions (account_id, category_id, amount, name, transaction_date) VALUES
(2, 5, 1000.00, 'Przelew nadwyżki z bieżącego', '2026-03-10'),
(2, 4, 15.50, 'Kapitalizacja odsetek', '2026-03-28'),
(2, 5, 500.00, 'Regularne oszczędzanie - marzec', '2026-03-15'),
(2, 5, 200.00, 'Przelew wolnych środków', '2026-03-20'),
(2, 5, 1000.00, 'Wpłata z premii', '2026-03-30'),
(2, 5, 300.00, 'Przelew nadwyżki z bieżącego', '2026-04-05'),
(2, NULL, -500.00, 'Awaryjna wypłata na naprawę auta', '2026-04-10'),
(2, 5, 150.00, 'Zwrot długu od znajomego (na oszczędności)', '2026-04-12'),
(2, 5, 400.00, 'Regularne oszczędzanie - kwiecień', '2026-04-15'),
(2, 4, 18.20, 'Kapitalizacja odsetek', '2026-04-28'),
(2, 4, 21.00, 'Kapitalizacja odsetek', '2026-05-28'),
(2, 5, 600.00, 'Przelew nadwyżki - maj', '2026-05-30');

-- 7. 10 transakcji dla konta walutowego (account_id = 3)
INSERT INTO transactions (account_id, category_id, amount, name, transaction_date) VALUES
(3, 6, 300.00, 'Zasilenie z kantoru internetowego', '2026-03-05'),
(3, 6, -14.99, 'Subskrypcja oprogramowania', '2026-03-12'),
(3, NULL, -45.50, 'Zakupy zagraniczne - Amazon', '2026-03-18'),
(3, NULL, 500.00, 'Wymiana walut - przelew z bieżącego', '2026-03-25'),
(3, 7, -120.00, 'Rezerwacja noclegu - Booking.com', '2026-04-02'),
(3, 7, -35.00, 'Wypłata z bankomatu za granicą', '2026-04-15'),
(3, 7, -89.90, 'Bilety lotnicze', '2026-04-20'),
(3, 6, 150.00, 'Zasilenie konta przed wyjazdem', '2026-05-05'),
(3, 7, -8.50, 'Kawa i przekąski na lotnisku', '2026-05-10'),
(3, 6, -112.99, 'Zakupy - elektronika AliExpress', '2026-05-18');

-- 6. Przeliczenie sald (dzieje się automatycznie)
-- UPDATE accounts a
-- LEFT JOIN (
--     SELECT account_id, SUM(amount) AS total_balance
--     FROM transactions
--     GROUP BY account_id
-- ) t ON a.id = t.account_id
-- SET a.balance = COALESCE(t.total_balance, 0.00)
-- WHERE a.id = 1;

-- UPDATE accounts a
-- LEFT JOIN (
--     SELECT account_id, SUM(amount) AS total_balance
--     FROM transactions
--     GROUP BY account_id
-- ) t ON a.id = t.account_id
-- SET a.balance = COALESCE(t.total_balance, 0.00)
-- WHERE a.id = 2;