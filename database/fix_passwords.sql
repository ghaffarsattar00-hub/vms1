-- FIX: Replace fake seed hashes with real Argon2id hashes
-- Admins & Parents -> password
UPDATE users SET password_hash = '$argon2id$v=19$m=65536,t=4,p=1$ZmYyTUgzVWZjcWNPekt2bQ$VvUPgqvpbNlqwzarxduHhboP7kAE6qOribAEm6SRXZM' WHERE role_id IN (1, 2);
-- Hospital Staff -> hospital123
UPDATE users SET password_hash = '$argon2id$v=19$m=65536,t=4,p=1$VVl6RHlTS2xJc28uV0JVYQ$LAeEN/iO8jJWWc1gt6qcAhUvl03c3GhDExWQJJJ1c3Y' WHERE role_id = 3;
