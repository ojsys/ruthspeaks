-- Add superadmin user to database
-- This user can be used for post authorship and other database relations
-- Login credentials are managed via .env file (ADMIN_EMAIL and ADMIN_PASSWORD_HASH)

INSERT INTO users (email, password_hash, name, role, created_at)
VALUES (
    'onahjonah@gmail.com',
    '$2y$10$CADL905r/vfWzgHu3v8OG.izR0H02ABRdEYEsQznH/opAOUOJN0ui',
    'Jonah Onah',
    'admin',
    NOW()
)
ON DUPLICATE KEY UPDATE
    password_hash = VALUES(password_hash),
    name = VALUES(name),
    role = VALUES(role);
