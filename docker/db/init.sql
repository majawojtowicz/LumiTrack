
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    firstname VARCHAR(100),
    lastname VARCHAR(100),
    role VARCHAR(50) DEFAULT 'USER',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_blocked integer DEFAULT 0;
);

INSERT INTO users (email, password, firstname, lastname, role)
VALUES (
    'admin@lumitrack.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'Admin',
    'User',
    'ADMIN'
);

CREATE TABLE entries (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    energy INT NOT NULL,
    mood INT NOT NULL,
    focus INT NOT NULL,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_entries_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

#1 do 1
CREATE TABLE user_profiles (
    user_id INTEGER PRIMARY KEY,
    display_name VARCHAR(100),
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_user_profile
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

#n do m
CREATE TABLE tags (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE entry_tags (
    entry_id INTEGER NOT NULL,
    tag_id INTEGER NOT NULL,
    PRIMARY KEY (entry_id, tag_id),
    CONSTRAINT fk_entry
        FOREIGN KEY (entry_id)
        REFERENCES entries(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_tag
        FOREIGN KEY (tag_id)
        REFERENCES tags(id)
        ON DELETE CASCADE
);

CREATE VIEW v_admin_activity_log AS
SELECT u.firstname, u.lastname, e.energy, e.mood, e.note, e.created_at
FROM entries e
JOIN users u ON e.user_id = u.id
ORDER BY e.created_at DESC;

CREATE VIEW v_entries_with_tags AS
SELECT
    e.id AS entry_id,
    e.note,
    t.name AS tag
FROM entries e
JOIN entry_tags et ON e.id = et.entry_id
JOIN tags t ON et.tag_id = t.id;

CREATE OR REPLACE FUNCTION count_user_entries(p_user_id INT)
RETURNS INTEGER AS $$
DECLARE
    total INTEGER;
BEGIN
    SELECT COUNT(*) INTO total
    FROM entries
    WHERE user_id = p_user_id;

    RETURN total;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION block_entry_for_blocked_user()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (
        SELECT 1 FROM users
        WHERE id = NEW.user_id AND is_blocked = 1
    ) THEN
        RAISE EXCEPTION 'User is blocked and cannot add entries';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_blocked_user_entry
BEFORE INSERT ON entries
FOR EACH ROW
EXECUTE FUNCTION block_entry_for_blocked_user();

BEGIN TRANSACTION ISOLATION LEVEL SERIALIZABLE;

INSERT INTO entries (user_id, content)
VALUES (1, 'Transactional test entry');

COMMIT;

CREATE OR REPLACE VIEW v_entries_with_users AS
SELECT 
    e.id AS entry_id,
    e.note,
    e.created_at,
    u.email
FROM entries e
JOIN users u ON e.user_id = u.id;