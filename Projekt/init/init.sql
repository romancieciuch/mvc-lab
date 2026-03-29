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
    balance DECIMAL(15, 2) DEFAULT 0.00,
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
    description VARCHAR(255) NULL,
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


-- Wyzwalacze - automatyczna aktualizacja salda konta po dowolnych operacjach na tym koncie
-- Zmiana znaku końca zapytania, aby MySQL nie przerwał tworzenia triggera w połowie
DELIMITER //

-- 1. Wyzwalacz po dodaniu nowej transakcji (INSERT)
CREATE TRIGGER po_dodaniu_transakcji
AFTER INSERT ON transactions
FOR EACH ROW
BEGIN
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

-- Przywrócenie standardowego średnika jako separatora
DELIMITER ;


-- Dane startowe

-- 0. Twortzymy użytkownika Jan Kowalski, jan.kowalski@gmail.com, 123456abcd
INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `token`, `active`) VALUES
(1, 'Jan Kowalski', 'jan.kowalski@gmail.com', '$2y$10$ZdJacckGfs4lrr87hRIgUOpQwZesKTC3H7EamTSIB3x9qEPbKWNoe', NULL, 1);

-- 1. Tworzymy dwa konta dla użytkownika o id = 1
INSERT INTO accounts (id, user_id, name, balance) VALUES
(1, 1, 'Konto bieżące', 0.00),
(2, 1, 'Konto oszczędnościowe', 0.00);

-- 2. Dodajemy kategorie dla konta bieżącego (account_id = 1)
INSERT INTO categories (id, account_id, name) VALUES
(1, 1, 'Wypłata'),
(2, 1, 'Zakupy spożywcze'),
(3, 1, 'Paliwo');

-- 3. Dodajemy kategorie dla konta oszczędnościowego (account_id = 2)
INSERT INTO categories (id, account_id, name) VALUES
(4, 2, 'Odsetki'),
(5, 2, 'Wpłata własna');

-- 4. Rejestrujemy transakcje dla konta bieżącego
INSERT INTO transactions (account_id, category_id, amount, description, transaction_date) VALUES
(1, 1, 5000.00, 'Wypłata za marzec', '2026-03-10'),
(1, 2, -150.50, 'Zakupy w Biedronce', '2026-03-12'),
(1, 3, -200.00, 'Tankowanie na Orlenie', '2026-03-15'),
-- Symulacja transakcji, która straciła kategorię (np. kategoria została usunięta)
(1, NULL, -50.00, 'Abonament za telefon', '2026-03-16');

-- 5. Rejestrujemy transakcje dla konta oszczędnościowego
INSERT INTO transactions (account_id, category_id, amount, description, transaction_date) VALUES
(2, 5, 1000.00, 'Przelew nadwyżki z bieżącego', '2026-03-10'),
(2, 4, 15.50, 'Kapitalizacja odsetek', '2026-03-28');

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