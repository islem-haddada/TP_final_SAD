CREATE DATABASE orientation;
USE orientation;

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL,
    name VARCHAR(100) NOT NULL,
    address TEXT,
    photo LONGBLOB,
    specialty VARCHAR(50),
    average FLOAT
);



CREATE TABLE modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    specialty VARCHAR(50)
);


CREATE TABLE student_choices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    specialty_id INT,
    choice_order INT,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (specialty_id) REFERENCES specialties(id)
);

CREATE TABLE specialties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    places INT,
    min_average FLOAT
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role VARCHAR(50)
);


INSERT INTO admins (id, username, password, email, role) VALUES
(1, 'amir', 'amir', 'amir@domain.com', 'manager'),
(2, 'islem', 'islem', 'islem@domain.com', 'manager'),

