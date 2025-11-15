-- Initial database setup
CREATE DATABASE IF NOT EXISTS laravel;
CREATE DATABASE IF NOT EXISTS testing;

-- Create a test user
CREATE USER IF NOT EXISTS 'laravel'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON laravel.* TO 'laravel'@'%';
FLUSH PRIVILEGES;
