CREATE DATABASE IF NOT EXISTS gymbuddy;
USE gymbuddy;

CREATE TABLE IF NOT EXISTS users (
     id INT AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    role VARCHAR(50) NOT NULL
    );

CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    content VARCHAR(1000) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id)
);
CREATE TABLE IF NOT EXISTS training_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS user_training_categories (
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES training_categories(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS descriptions (
    user_id INT PRIMARY KEY,
    description TEXT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE days (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL
);
CREATE TABLE user_days (
    user_id INTEGER,
    day_id INTEGER,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (day_id) REFERENCES days(id),
    PRIMARY KEY (user_id, day_id)
);
CREATE TABLE IF NOT EXISTS locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL
);
CREATE TABLE IF NOT EXISTS user_locations (
    user_id INT NOT NULL,
    location_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, location_id)
);

CREATE TABLE IF NOT EXISTS liked_users (
    user_id INT NOT NULL,
    liked_user_id INT NOT NULL,
    PRIMARY KEY (user_id, liked_user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (liked_user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS disliked_users (
    user_id INT NOT NULL,
    disliked_user_id INT NOT NULL,
    PRIMARY KEY (user_id, disliked_user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (disliked_user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS gyms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS invitations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    gym_id INT NOT NULL,
    message TEXT NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (gym_id) REFERENCES gyms(id) ON DELETE CASCADE
);


SELECT * FROM invitations ;
insert into invitations(id,sender_id,receiver_id,gym_id,message,status,created_at)values (37,59,9,6, 'hi chomik0', 'accepted','2025-06-10 13:22:43');

INSERT INTO users (name, email, password_hash, age, role) VALUES     ('Magda', 'magda1@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG', 21 ,'admin');

-- testowi użytkownicy (52) haslo 123
INSERT INTO users (name, email, password_hash, age, role) VALUES
    ('Magda', 'magda@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG', 21 ,'user'),
    ('Emma', 'emma@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',23 ,'user'),
    ('Oliver', 'oliver@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',33, 'user'),
    ('Lena', 'lena@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',55, 'user'),
    ('Tomasz', 'tomasz@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',66, 'user'),
    ('Julia', 'julia@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',22, 'user'),
    ('Jan', 'jan@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',34, 'user'),
    ('Ola', 'ola@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',18, 'user'),
    ('Piotr', 'piotr@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',19, 'user'),
    ('Kasia', 'kasia@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',29, 'user'),
    ('Bartek', 'bartek@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',20, 'user'),
    ('Anna', 'anna@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',28, 'user'),
    ('Mateusz', 'mateusz@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',23, 'user'),
    ('Zofia', 'zofia@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',24, 'user'),
    ('Karol', 'karol@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',21, 'user'),
    ('Natalia', 'natalia@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',29, 'user'),
    ('Andrzej', 'andrzej@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',20, 'user'),
    ('Kinga', 'kinga@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',28, 'user'),
    ('Robert', 'robert@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',30, 'user'),
    ('Marta', 'marta@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',26, 'user'),
    ('Filip', 'filip@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',25, 'user'),
    ('Dominika', 'dominika@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG', 33, 'user'),
    ('Sebastian', 'sebastian@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG', 25, 'user'),
    ('Monika', 'monika@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',26, 'user'),
    ('Patryk', 'patryk@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',42, 'user'),
    ('Joanna', 'joanna@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',23, 'user'),
    ('Dawid', 'dawid@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',47, 'user'),
    ('Agnieszka', 'agnieszka@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',34, 'user'),
    ('Kamil', 'kamil@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG', 33, 'user'),
    ('Wiktoria', 'wiktoria@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',28, 'user'),
    ('Adam', 'adam@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',29, 'user'),
    ('Ewa', 'ewa@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG', 31, 'user'),
    ('Mikołaj', 'mikolaj@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',33, 'user'),
    ('Izabela', 'izabela@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',23, 'user'),
    ('Grzegorz', 'grzegorz@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',27, 'user'),
    ('Halina', 'halina@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',27, 'user'),
    ('Damian', 'damian@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',26, 'user'),
    ('Eliza', 'eliza@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',25, 'user'),
    ('Wojtek', 'wojtek@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',18, 'user'),
    ('Sylwia', 'sylwia@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',19, 'user'),
    ('Jacek', 'jacek@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',29, 'user'),
    ('Paulina', 'paulina@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',52, 'user'),
    ('Szymon', 'szymon@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',29, 'user'),
    ('Milena', 'milena@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',29, 'user'),
    ('Kacper', 'kacper@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',51, 'user'),
    ('Natalia2', 'natalia2@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',41, 'user'),
    ('Paweł', 'pawel@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',61, 'user'),
    ('Ala', 'ala@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',41, 'user'),
    ('Michał', 'michal2@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',66, 'user'),
    ('Asia', 'asia@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',34, 'user'),
    ('Tadeusz', 'tadeusz@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',46, 'user'),
    ('Weronika', 'weronika@example.com', '$2y$10$/PpBhwh0HerIQ9D18HQcduR0cIZ3O8vHZONthsof17XiWB9ZNdomG',41, 'user');

SELECT * FROM users
    left join user_training_categories utc on users.id = utc.user_id
    left join descriptions d on users.id = d.user_id;

INSERT INTO training_categories (name) VALUES
('Outdoor Exercise'),
('Cardio Exercise'),
('Strength Training'),
('Flexibility + Mobility'),
('Yoga & Pilates'),
('Senior workouts'),
('Aqua Fitness'),
('Rehabilitation & Recovery');


-- Kategorie (każdy ma 2)
INSERT INTO user_training_categories (user_id, category_id) VALUES (1, 2);
INSERT INTO user_training_categories (user_id, category_id) VALUES (1, 3);
INSERT INTO user_training_categories (user_id, category_id) VALUES (2, 1);
INSERT INTO user_training_categories (user_id, category_id) VALUES (2, 5);
INSERT INTO user_training_categories (user_id, category_id) VALUES (3, 8);
INSERT INTO user_training_categories (user_id, category_id) VALUES (3, 7);
INSERT INTO user_training_categories (user_id, category_id) VALUES (4, 6);
INSERT INTO user_training_categories (user_id, category_id) VALUES (4, 4);
INSERT INTO user_training_categories (user_id, category_id) VALUES (5, 8);
INSERT INTO user_training_categories (user_id, category_id) VALUES (5, 2);
INSERT INTO user_training_categories (user_id, category_id) VALUES (6, 5);
INSERT INTO user_training_categories (user_id, category_id) VALUES (6, 3);
INSERT INTO user_training_categories (user_id, category_id) VALUES (7, 8);
INSERT INTO user_training_categories (user_id, category_id) VALUES (7, 7);
INSERT INTO user_training_categories (user_id, category_id) VALUES (8, 4);
INSERT INTO user_training_categories (user_id, category_id) VALUES (8, 6);
INSERT INTO user_training_categories (user_id, category_id) VALUES (9, 1);
INSERT INTO user_training_categories (user_id, category_id) VALUES (9, 5);
INSERT INTO user_training_categories (user_id, category_id) VALUES (10, 2);
INSERT INTO user_training_categories (user_id, category_id) VALUES (10, 7);
INSERT INTO user_training_categories (user_id, category_id) VALUES (11, 3);
INSERT INTO user_training_categories (user_id, category_id) VALUES (11, 8);
INSERT INTO user_training_categories (user_id, category_id) VALUES (12, 4);
INSERT INTO user_training_categories (user_id, category_id) VALUES (12, 1);
INSERT INTO user_training_categories (user_id, category_id) VALUES (13, 6);
INSERT INTO user_training_categories (user_id, category_id) VALUES (13, 2);
INSERT INTO user_training_categories (user_id, category_id) VALUES (14, 5);
INSERT INTO user_training_categories (user_id, category_id) VALUES (14, 3);
INSERT INTO user_training_categories (user_id, category_id) VALUES (15, 7);
INSERT INTO user_training_categories (user_id, category_id) VALUES (15, 8);
INSERT INTO user_training_categories (user_id, category_id) VALUES (16, 4);
INSERT INTO user_training_categories (user_id, category_id) VALUES (16, 2);
INSERT INTO user_training_categories (user_id, category_id) VALUES (17, 1);
INSERT INTO user_training_categories (user_id, category_id) VALUES (17, 6);
INSERT INTO user_training_categories (user_id, category_id) VALUES (18, 8);
INSERT INTO user_training_categories (user_id, category_id) VALUES (18, 5);
INSERT INTO user_training_categories (user_id, category_id) VALUES (19, 7);
INSERT INTO user_training_categories (user_id, category_id) VALUES (19, 3);
INSERT INTO user_training_categories (user_id, category_id) VALUES (20, 2);
INSERT INTO user_training_categories (user_id, category_id) VALUES (20, 4);
INSERT INTO user_training_categories (user_id, category_id) VALUES (21, 6);
INSERT INTO user_training_categories (user_id, category_id) VALUES (21, 1);
INSERT INTO user_training_categories (user_id, category_id) VALUES (22, 8);
INSERT INTO user_training_categories (user_id, category_id) VALUES (22, 7);
INSERT INTO user_training_categories (user_id, category_id) VALUES (23, 3);
INSERT INTO user_training_categories (user_id, category_id) VALUES (23, 5);
INSERT INTO user_training_categories (user_id, category_id) VALUES (24, 4);
INSERT INTO user_training_categories (user_id, category_id) VALUES (24, 6);
INSERT INTO user_training_categories (user_id, category_id) VALUES (25, 1);
INSERT INTO user_training_categories (user_id, category_id) VALUES (25, 2);
INSERT INTO user_training_categories (user_id, category_id) VALUES (26, 5);
INSERT INTO user_training_categories (user_id, category_id) VALUES (26, 8);
INSERT INTO user_training_categories (user_id, category_id) VALUES (27, 7);
INSERT INTO user_training_categories (user_id, category_id) VALUES (27, 4);
INSERT INTO user_training_categories (user_id, category_id) VALUES (28, 3);
INSERT INTO user_training_categories (user_id, category_id) VALUES (28, 1);
INSERT INTO user_training_categories (user_id, category_id) VALUES (29, 2);
INSERT INTO user_training_categories (user_id, category_id) VALUES (29, 5);
INSERT INTO user_training_categories (user_id, category_id) VALUES (30, 8);
INSERT INTO user_training_categories (user_id, category_id) VALUES (30, 6);
INSERT INTO user_training_categories (user_id, category_id) VALUES (31, 7);
INSERT INTO user_training_categories (user_id, category_id) VALUES (31, 3);
INSERT INTO user_training_categories (user_id, category_id) VALUES (32, 4);
INSERT INTO user_training_categories (user_id, category_id) VALUES (32, 2);
INSERT INTO user_training_categories (user_id, category_id) VALUES (33, 1);
INSERT INTO user_training_categories (user_id, category_id) VALUES (33, 8);
INSERT INTO user_training_categories (user_id, category_id) VALUES (34, 5);
INSERT INTO user_training_categories (user_id, category_id) VALUES (34, 7);
INSERT INTO user_training_categories (user_id, category_id) VALUES (35, 3);
INSERT INTO user_training_categories (user_id, category_id) VALUES (35, 6);
INSERT INTO user_training_categories (user_id, category_id) VALUES (36, 2);
INSERT INTO user_training_categories (user_id, category_id) VALUES (36, 4);
INSERT INTO user_training_categories (user_id, category_id) VALUES (37, 8);
INSERT INTO user_training_categories (user_id, category_id) VALUES (37, 1);
INSERT INTO user_training_categories (user_id, category_id) VALUES (38, 7);
INSERT INTO user_training_categories (user_id, category_id) VALUES (38, 5);
INSERT INTO user_training_categories (user_id, category_id) VALUES (39, 6);
INSERT INTO user_training_categories (user_id, category_id) VALUES (39, 3);
INSERT INTO user_training_categories (user_id, category_id) VALUES (40, 4);
INSERT INTO user_training_categories (user_id, category_id) VALUES (40, 8);
INSERT INTO user_training_categories (user_id, category_id) VALUES (41, 2);
INSERT INTO user_training_categories (user_id, category_id) VALUES (41, 7);
INSERT INTO user_training_categories (user_id, category_id) VALUES (42, 5);
INSERT INTO user_training_categories (user_id, category_id) VALUES (42, 6);
INSERT INTO user_training_categories (user_id, category_id) VALUES (43, 1);
INSERT INTO user_training_categories (user_id, category_id) VALUES (43, 4);
INSERT INTO user_training_categories (user_id, category_id) VALUES (44, 8);
INSERT INTO user_training_categories (user_id, category_id) VALUES (44, 2);
INSERT INTO user_training_categories (user_id, category_id) VALUES (45, 3);
INSERT INTO user_training_categories (user_id, category_id) VALUES (45, 7);
INSERT INTO user_training_categories (user_id, category_id) VALUES (46, 5);
INSERT INTO user_training_categories (user_id, category_id) VALUES (46, 1);
INSERT INTO user_training_categories (user_id, category_id) VALUES (47, 6);
INSERT INTO user_training_categories (user_id, category_id) VALUES (47, 3);
INSERT INTO user_training_categories (user_id, category_id) VALUES (48, 4);
INSERT INTO user_training_categories (user_id, category_id) VALUES (48, 8);
INSERT INTO user_training_categories (user_id, category_id) VALUES (49, 2);
INSERT INTO user_training_categories (user_id, category_id) VALUES (49, 5);
INSERT INTO user_training_categories (user_id, category_id) VALUES (50, 7);
INSERT INTO user_training_categories (user_id, category_id) VALUES (50, 6);
INSERT INTO user_training_categories (user_id, category_id) VALUES (51, 1);
INSERT INTO user_training_categories (user_id, category_id) VALUES (51, 3);
INSERT INTO user_training_categories (user_id, category_id) VALUES (52, 4);
INSERT INTO user_training_categories (user_id, category_id) VALUES (52, 2);


INSERT INTO descriptions (user_id, description) VALUES
(1, 'Loves keeping the heart racing with intense cardio sessions. Builds power like a machine in the weight room.'),
(2, 'Balances body and mind with peaceful yoga practice. Crushes workouts with fresh air and sunlight.'),
(3, 'Splashing through cardio and resistance in the pool. Knows the power of recovery for long-term strength.'),
(4, 'Stays active and energized with senior-friendly routines. Believes stretching is the secret to success.'),
(5, 'I love intense cardio workouts that give me energy for the whole day. I also regularly work on recovery and balance to take care of my long-term fitness and health.'),
(6, 'Builds power like a machine in the weight room. Enjoys long stretches and deep breathing flows.'),
(7, 'Follows mindful rehab to bounce back stronger. Splashing through cardio and resistance in the pool.'),
(8, 'Moves with grace and control in every session. Stays active and energized with senior-friendly routines.'),
(9, 'Feels most alive training in the great outdoors. Enjoys long stretches and deep breathing flows.'),
(10, 'Gets energy from every sprint and jump rope round. Splashing through cardio and resistance in the pool.'),
(11, 'Builds power like a machine in the weight room. Knows the power of recovery for long-term strength.'),
(12, 'Moves with grace and control in every session. Feels most alive training in the great outdoors.'),
(13, 'Stays active and energized with senior-friendly routines. Gets energy from every sprint and jump rope round.'),
(14, 'Enjoys long stretches and deep breathing flows. Builds power like a machine in the weight room.'),
(15, 'Splashing through cardio and resistance in the pool. Knows the power of recovery for long-term strength.'),
(16, 'Moves with grace and control in every session. Gets energy from every sprint and jump rope round.'),
(17, 'Feels most alive training in the great outdoors. Stays active and energized with senior-friendly routines.'),
(18, 'Follows mindful rehab to bounce back stronger. Balances body and mind with peaceful yoga practice.'),
(19, 'Splashing through cardio and resistance in the pool. Builds power like a machine in the weight room.'),
(20, 'Gets energy from every sprint and jump rope round. Moves with grace and control in every session.'),
(21, 'Stays active and energized with senior-friendly routines. Crushes workouts with fresh air and sunlight.'),
(22, 'Knows the power of recovery for long-term strength. Splashing through cardio and resistance in the pool.'),
(23, 'Builds power like a machine in the weight room. Balances body and mind with peaceful yoga practice.'),
(24, 'Moves with grace and control in every session. Stays active and energized with senior-friendly routines.'),
(25, 'Feels most alive training in the great outdoors. Gets energy from every sprint and jump rope round.'),
(26, 'Enjoys long stretches and deep breathing flows. Follows mindful rehab to bounce back stronger.'),
(27, 'Splashing through cardio and resistance in the pool. Moves with grace and control in every session.'),
(28, 'Builds power like a machine in the weight room. Feels most alive training in the great outdoors.'),
(29, 'Gets energy from every sprint and jump rope round. Balances body and mind with peaceful yoga practice.'),
(30, 'Knows the power of recovery for long-term strength. Stays active and energized with senior-friendly routines.'),
(31, 'Splashing through cardio and resistance in the pool. Builds power like a machine in the weight room.'),
(32, 'Moves with grace and control in every session. Gets energy from every sprint and jump rope round.'),
(33, 'Feels most alive training in the great outdoors. Knows the power of recovery for long-term strength.'),
(34, 'Balances body and mind with peaceful yoga practice. Splashing through cardio and resistance in the pool.'),
(35, 'Builds power like a machine in the weight room. Stays active and energized with senior-friendly routines.'),
(36, 'Gets energy from every sprint and jump rope round. Moves with grace and control in every session.'),
(37, 'Knows the power of recovery for long-term strength. Feels most alive training in the great outdoors.'),
(38, 'Splashing through cardio and resistance in the pool. Balances body and mind with peaceful yoga practice.'),
(39, 'Stays active and energized with senior-friendly routines. Builds power like a machine in the weight room.'),
(40, 'Moves with grace and control in every session. Follows mindful rehab to bounce back stronger.'),
(41, 'Gets energy from every sprint and jump rope round. Splashing through cardio and resistance in the pool.'),
(42, 'Balances body and mind with peaceful yoga practice. Stays active and energized with senior-friendly routines.'),
(43, 'Feels most alive training in the great outdoors. Moves with grace and control in every session.'),
(44, 'Knows the power of recovery for long-term strength. Gets energy from every sprint and jump rope round.'),
(45, 'Builds power like a machine in the weight room. Splashing through cardio and resistance in the pool.'),
(46, 'Balances body and mind with peaceful yoga practice. Feels most alive training in the great outdoors.'),
(47, 'Stays active and energized with senior-friendly routines. Builds power like a machine in the weight room.'),
(48, 'Moves with grace and control in every session. Knows the power of recovery for long-term strength.'),
(49, 'Gets energy from every sprint and jump rope round. Balances body and mind with peaceful yoga practice.'),
(50, 'Splashing through cardio and resistance in the pool. Stays active and energized with senior-friendly routines.'),
(51, 'Feels most alive training in the great outdoors. Builds power like a machine in the weight room.'),
(52, 'Moves with grace and control in every session. Gets energy from every sprint and jump rope round.');

INSERT INTO days (id, name) VALUES
(1, 'Monday'),
(2, 'Tuesday'),
(3, 'Wednesday'),
(4, 'Thursday'),
(5, 'Friday'),
(6, 'Saturday'),
(7, 'Sunday');

SELECT * FROM users
    left join descriptions d on users.id = d.user_id
    left join user_days ud on users.id = ud.user_id;


-- dostępność użytkowników od 1 do 7 dni
INSERT INTO user_days (user_id, day_id) VALUES (1, 4);
INSERT INTO user_days (user_id, day_id) VALUES (1, 5);
INSERT INTO user_days (user_id, day_id) VALUES (1, 3);
INSERT INTO user_days (user_id, day_id) VALUES (1, 1);
INSERT INTO user_days (user_id, day_id) VALUES (2, 5);
INSERT INTO user_days (user_id, day_id) VALUES (2, 1);
INSERT INTO user_days (user_id, day_id) VALUES (2, 4);
INSERT INTO user_days (user_id, day_id) VALUES (2, 3);
INSERT INTO user_days (user_id, day_id) VALUES (2, 2);
INSERT INTO user_days (user_id, day_id) VALUES (3, 6);
INSERT INTO user_days (user_id, day_id) VALUES (3, 7);
INSERT INTO user_days (user_id, day_id) VALUES (3, 1);
INSERT INTO user_days (user_id, day_id) VALUES (3, 3);
INSERT INTO user_days (user_id, day_id) VALUES (3, 2);
INSERT INTO user_days (user_id, day_id) VALUES (4, 7);
INSERT INTO user_days (user_id, day_id) VALUES (4, 1);
INSERT INTO user_days (user_id, day_id) VALUES (4, 4);
INSERT INTO user_days (user_id, day_id) VALUES (4, 5);
INSERT INTO user_days (user_id, day_id) VALUES (5, 5);
INSERT INTO user_days (user_id, day_id) VALUES (5, 3);
INSERT INTO user_days (user_id, day_id) VALUES (5, 2);
INSERT INTO user_days (user_id, day_id) VALUES (5, 7);
INSERT INTO user_days (user_id, day_id) VALUES (5, 1);
INSERT INTO user_days (user_id, day_id) VALUES (6, 1);
INSERT INTO user_days (user_id, day_id) VALUES (6, 2);
INSERT INTO user_days (user_id, day_id) VALUES (7, 1);
INSERT INTO user_days (user_id, day_id) VALUES (7, 4);
INSERT INTO user_days (user_id, day_id) VALUES (7, 3);
INSERT INTO user_days (user_id, day_id) VALUES (7, 5);
INSERT INTO user_days (user_id, day_id) VALUES (8, 5);
INSERT INTO user_days (user_id, day_id) VALUES (8, 1);
INSERT INTO user_days (user_id, day_id) VALUES (9, 4);
INSERT INTO user_days (user_id, day_id) VALUES (9, 5);
INSERT INTO user_days (user_id, day_id) VALUES (9, 3);
INSERT INTO user_days (user_id, day_id) VALUES (9, 7);
INSERT INTO user_days (user_id, day_id) VALUES (10, 5);
INSERT INTO user_days (user_id, day_id) VALUES (10, 1);
INSERT INTO user_days (user_id, day_id) VALUES (10, 7);
INSERT INTO user_days (user_id, day_id) VALUES (11, 7);
INSERT INTO user_days (user_id, day_id) VALUES (11, 2);
INSERT INTO user_days (user_id, day_id) VALUES (12, 4);
INSERT INTO user_days (user_id, day_id) VALUES (12, 5);
INSERT INTO user_days (user_id, day_id) VALUES (12, 7);
INSERT INTO user_days (user_id, day_id) VALUES (12, 1);
INSERT INTO user_days (user_id, day_id) VALUES (13, 4);
INSERT INTO user_days (user_id, day_id) VALUES (13, 2);
INSERT INTO user_days (user_id, day_id) VALUES (13, 1);
INSERT INTO user_days (user_id, day_id) VALUES (13, 6);
INSERT INTO user_days (user_id, day_id) VALUES (13, 7);
INSERT INTO user_days (user_id, day_id) VALUES (14, 6);
INSERT INTO user_days (user_id, day_id) VALUES (14, 1);
INSERT INTO user_days (user_id, day_id) VALUES (14, 4);
INSERT INTO user_days (user_id, day_id) VALUES (14, 3);
INSERT INTO user_days (user_id, day_id) VALUES (14, 2);
INSERT INTO user_days (user_id, day_id) VALUES (15, 4);
INSERT INTO user_days (user_id, day_id) VALUES (15, 1);
INSERT INTO user_days (user_id, day_id) VALUES (15, 2);
INSERT INTO user_days (user_id, day_id) VALUES (15, 7);
INSERT INTO user_days (user_id, day_id) VALUES (15, 3);
INSERT INTO user_days (user_id, day_id) VALUES (16, 4);
INSERT INTO user_days (user_id, day_id) VALUES (16, 3);
INSERT INTO user_days (user_id, day_id) VALUES (16, 2);
INSERT INTO user_days (user_id, day_id) VALUES (16, 1);
INSERT INTO user_days (user_id, day_id) VALUES (17, 2);
INSERT INTO user_days (user_id, day_id) VALUES (17, 6);
INSERT INTO user_days (user_id, day_id) VALUES (17, 3);
INSERT INTO user_days (user_id, day_id) VALUES (17, 5);
INSERT INTO user_days (user_id, day_id) VALUES (17, 1);
INSERT INTO user_days (user_id, day_id) VALUES (18, 4);
INSERT INTO user_days (user_id, day_id) VALUES (18, 7);
INSERT INTO user_days (user_id, day_id) VALUES (18, 1);
INSERT INTO user_days (user_id, day_id) VALUES (18, 5);
INSERT INTO user_days (user_id, day_id) VALUES (19, 5);
INSERT INTO user_days (user_id, day_id) VALUES (19, 1);
INSERT INTO user_days (user_id, day_id) VALUES (20, 7);
INSERT INTO user_days (user_id, day_id) VALUES (20, 5);
INSERT INTO user_days (user_id, day_id) VALUES (21, 6);
INSERT INTO user_days (user_id, day_id) VALUES (21, 4);
INSERT INTO user_days (user_id, day_id) VALUES (21, 2);
INSERT INTO user_days (user_id, day_id) VALUES (21, 3);
INSERT INTO user_days (user_id, day_id) VALUES (22, 4);
INSERT INTO user_days (user_id, day_id) VALUES (22, 7);
INSERT INTO user_days (user_id, day_id) VALUES (22, 2);
INSERT INTO user_days (user_id, day_id) VALUES (22, 5);
INSERT INTO user_days (user_id, day_id) VALUES (23, 6);
INSERT INTO user_days (user_id, day_id) VALUES (23, 1);
INSERT INTO user_days (user_id, day_id) VALUES (23, 2);
INSERT INTO user_days (user_id, day_id) VALUES (23, 3);
INSERT INTO user_days (user_id, day_id) VALUES (24, 3);
INSERT INTO user_days (user_id, day_id) VALUES (24, 2);
INSERT INTO user_days (user_id, day_id) VALUES (24, 6);
INSERT INTO user_days (user_id, day_id) VALUES (25, 4);
INSERT INTO user_days (user_id, day_id) VALUES (25, 5);
INSERT INTO user_days (user_id, day_id) VALUES (26, 7);
INSERT INTO user_days (user_id, day_id) VALUES (26, 2);
INSERT INTO user_days (user_id, day_id) VALUES (26, 4);
INSERT INTO user_days (user_id, day_id) VALUES (27, 5);
INSERT INTO user_days (user_id, day_id) VALUES (27, 2);
INSERT INTO user_days (user_id, day_id) VALUES (27, 1);
INSERT INTO user_days (user_id, day_id) VALUES (27, 3);
INSERT INTO user_days (user_id, day_id) VALUES (28, 1);
INSERT INTO user_days (user_id, day_id) VALUES (28, 2);
INSERT INTO user_days (user_id, day_id) VALUES (28, 4);
INSERT INTO user_days (user_id, day_id) VALUES (28, 3);
INSERT INTO user_days (user_id, day_id) VALUES (28, 5);
INSERT INTO user_days (user_id, day_id) VALUES (29, 3);
INSERT INTO user_days (user_id, day_id) VALUES (29, 4);
INSERT INTO user_days (user_id, day_id) VALUES (29, 2);
INSERT INTO user_days (user_id, day_id) VALUES (30, 2);
INSERT INTO user_days (user_id, day_id) VALUES (30, 7);
INSERT INTO user_days (user_id, day_id) VALUES (31, 7);
INSERT INTO user_days (user_id, day_id) VALUES (31, 1);
INSERT INTO user_days (user_id, day_id) VALUES (31, 6);
INSERT INTO user_days (user_id, day_id) VALUES (32, 1);
INSERT INTO user_days (user_id, day_id) VALUES (32, 7);
INSERT INTO user_days (user_id, day_id) VALUES (32, 2);
INSERT INTO user_days (user_id, day_id) VALUES (32, 4);
INSERT INTO user_days (user_id, day_id) VALUES (32, 6);
INSERT INTO user_days (user_id, day_id) VALUES (33, 6);
INSERT INTO user_days (user_id, day_id) VALUES (33, 5);
INSERT INTO user_days (user_id, day_id) VALUES (33, 2);
INSERT INTO user_days (user_id, day_id) VALUES (33, 4);
INSERT INTO user_days (user_id, day_id) VALUES (34, 6);
INSERT INTO user_days (user_id, day_id) VALUES (34, 4);
INSERT INTO user_days (user_id, day_id) VALUES (34, 1);
INSERT INTO user_days (user_id, day_id) VALUES (35, 6);
INSERT INTO user_days (user_id, day_id) VALUES (35, 4);
INSERT INTO user_days (user_id, day_id) VALUES (36, 1);
INSERT INTO user_days (user_id, day_id) VALUES (36, 3);
INSERT INTO user_days (user_id, day_id) VALUES (37, 3);
INSERT INTO user_days (user_id, day_id) VALUES (37, 2);
INSERT INTO user_days (user_id, day_id) VALUES (37, 6);
INSERT INTO user_days (user_id, day_id) VALUES (37, 1);
INSERT INTO user_days (user_id, day_id) VALUES (38, 4);
INSERT INTO user_days (user_id, day_id) VALUES (38, 5);
INSERT INTO user_days (user_id, day_id) VALUES (39, 4);
INSERT INTO user_days (user_id, day_id) VALUES (39, 3);
INSERT INTO user_days (user_id, day_id) VALUES (39, 6);
INSERT INTO user_days (user_id, day_id) VALUES (39, 1);
INSERT INTO user_days (user_id, day_id) VALUES (40, 1);
INSERT INTO user_days (user_id, day_id) VALUES (40, 3);
INSERT INTO user_days (user_id, day_id) VALUES (40, 7);
INSERT INTO user_days (user_id, day_id) VALUES (40, 6);
INSERT INTO user_days (user_id, day_id) VALUES (40, 5);
INSERT INTO user_days (user_id, day_id) VALUES (41, 4);
INSERT INTO user_days (user_id, day_id) VALUES (41, 6);
INSERT INTO user_days (user_id, day_id) VALUES (41, 3);
INSERT INTO user_days (user_id, day_id) VALUES (41, 5);
INSERT INTO user_days (user_id, day_id) VALUES (41, 2);
INSERT INTO user_days (user_id, day_id) VALUES (42, 1);
INSERT INTO user_days (user_id, day_id) VALUES (42, 6);
INSERT INTO user_days (user_id, day_id) VALUES (42, 5);
INSERT INTO user_days (user_id, day_id) VALUES (43, 2);
INSERT INTO user_days (user_id, day_id) VALUES (43, 5);
INSERT INTO user_days (user_id, day_id) VALUES (43, 7);
INSERT INTO user_days (user_id, day_id) VALUES (44, 1);
INSERT INTO user_days (user_id, day_id) VALUES (44, 5);
INSERT INTO user_days (user_id, day_id) VALUES (44, 4);
INSERT INTO user_days (user_id, day_id) VALUES (44, 3);
INSERT INTO user_days (user_id, day_id) VALUES (45, 1);
INSERT INTO user_days (user_id, day_id) VALUES (45, 3);
INSERT INTO user_days (user_id, day_id) VALUES (45, 5);
INSERT INTO user_days (user_id, day_id) VALUES (46, 2);
INSERT INTO user_days (user_id, day_id) VALUES (46, 6);
INSERT INTO user_days (user_id, day_id) VALUES (46, 7);
INSERT INTO user_days (user_id, day_id) VALUES (47, 3);
INSERT INTO user_days (user_id, day_id) VALUES (47, 5);
INSERT INTO user_days (user_id, day_id) VALUES (48, 7);
INSERT INTO user_days (user_id, day_id) VALUES (48, 2);
INSERT INTO user_days (user_id, day_id) VALUES (49, 2);
INSERT INTO user_days (user_id, day_id) VALUES (49, 7);
INSERT INTO user_days (user_id, day_id) VALUES (50, 1);
INSERT INTO user_days (user_id, day_id) VALUES (50, 2);
INSERT INTO user_days (user_id, day_id) VALUES (51, 1);
INSERT INTO user_days (user_id, day_id) VALUES (51, 3);
INSERT INTO user_days (user_id, day_id) VALUES (51, 6);
INSERT INTO user_days (user_id, day_id) VALUES (51, 7);
INSERT INTO user_days (user_id, day_id) VALUES (51, 4);
INSERT INTO user_days (user_id, day_id) VALUES (52, 3);
INSERT INTO user_days (user_id, day_id) VALUES (52, 5);
INSERT INTO user_days (user_id, day_id) VALUES (52, 6);
INSERT INTO user_days (user_id, day_id) VALUES (52, 7);



SELECT * FROM users
    left join descriptions d on users.id = d.user_id
    left join user_days ud on users.id = ud.user_id
left join user_locations ul on users.id = ul.user_id
left join user_training_categories utc on users.id = utc.user_id;



INSERT INTO locations (name) VALUES
('Warsaw'),
('Cracow'),
('Łódź'),
('Wrocław'),
('Olsztyn'),
('Gdańsk'),
('Szczecin');

INSERT INTO user_locations (user_id, location_id) VALUES (1, 1);
INSERT INTO user_locations (user_id, location_id) VALUES (2, 2);
INSERT INTO user_locations (user_id, location_id) VALUES (3, 3);
INSERT INTO user_locations (user_id, location_id) VALUES (4, 4);
INSERT INTO user_locations (user_id, location_id) VALUES (5, 5);
INSERT INTO user_locations (user_id, location_id) VALUES (6, 6);
INSERT INTO user_locations (user_id, location_id) VALUES (7, 7);
INSERT INTO user_locations (user_id, location_id) VALUES (8, 1);
INSERT INTO user_locations (user_id, location_id) VALUES (9, 2);
INSERT INTO user_locations (user_id, location_id) VALUES (10, 3);
INSERT INTO user_locations (user_id, location_id) VALUES (11, 4);
INSERT INTO user_locations (user_id, location_id) VALUES (12, 5);
INSERT INTO user_locations (user_id, location_id) VALUES (13, 6);
INSERT INTO user_locations (user_id, location_id) VALUES (14, 7);
INSERT INTO user_locations (user_id, location_id) VALUES (15, 1);
INSERT INTO user_locations (user_id, location_id) VALUES (16, 2);
INSERT INTO user_locations (user_id, location_id) VALUES (17, 3);
INSERT INTO user_locations (user_id, location_id) VALUES (18, 4);
INSERT INTO user_locations (user_id, location_id) VALUES (19, 5);
INSERT INTO user_locations (user_id, location_id) VALUES (20, 6);
INSERT INTO user_locations (user_id, location_id) VALUES (21, 7);
INSERT INTO user_locations (user_id, location_id) VALUES (22, 1);
INSERT INTO user_locations (user_id, location_id) VALUES (23, 2);
INSERT INTO user_locations (user_id, location_id) VALUES (24, 3);
INSERT INTO user_locations (user_id, location_id) VALUES (25, 4);
INSERT INTO user_locations (user_id, location_id) VALUES (26, 5);
INSERT INTO user_locations (user_id, location_id) VALUES (27, 6);
INSERT INTO user_locations (user_id, location_id) VALUES (28, 7);
INSERT INTO user_locations (user_id, location_id) VALUES (29, 1);
INSERT INTO user_locations (user_id, location_id) VALUES (30, 2);
INSERT INTO user_locations (user_id, location_id) VALUES (31, 3);
INSERT INTO user_locations (user_id, location_id) VALUES (32, 4);
INSERT INTO user_locations (user_id, location_id) VALUES (33, 5);
INSERT INTO user_locations (user_id, location_id) VALUES (34, 6);
INSERT INTO user_locations (user_id, location_id) VALUES (35, 7);
INSERT INTO user_locations (user_id, location_id) VALUES (36, 1);
INSERT INTO user_locations (user_id, location_id) VALUES (37, 2);
INSERT INTO user_locations (user_id, location_id) VALUES (38, 3);
INSERT INTO user_locations (user_id, location_id) VALUES (39, 4);
INSERT INTO user_locations (user_id, location_id) VALUES (40, 5);
INSERT INTO user_locations (user_id, location_id) VALUES (41, 6);
INSERT INTO user_locations (user_id, location_id) VALUES (42, 7);
INSERT INTO user_locations (user_id, location_id) VALUES (43, 1);
INSERT INTO user_locations (user_id, location_id) VALUES (44, 2);
INSERT INTO user_locations (user_id, location_id) VALUES (45, 3);
INSERT INTO user_locations (user_id, location_id) VALUES (46, 4);
INSERT INTO user_locations (user_id, location_id) VALUES (47, 5);
INSERT INTO user_locations (user_id, location_id) VALUES (48, 6);
INSERT INTO user_locations (user_id, location_id) VALUES (49, 7);
INSERT INTO user_locations (user_id, location_id) VALUES (50, 1);
INSERT INTO user_locations (user_id, location_id) VALUES (51, 2);
INSERT INTO user_locations (user_id, location_id) VALUES (52, 3);


INSERT INTO gyms (name, city) VALUES
('Cracow Gym 1', 'Cracow'),
('Cracow Gym 2', 'Cracow'),
('Warsaw Gym 1', 'Warsaw'),
('Warsaw Gym 2', 'Warsaw'),
('Warsaw Gym 3', 'Warsaw'),
('Łódź Gym 1', 'Łódź'),
('Łódź Gym 2', 'Łódź'),
('Wrocław Gym 1', 'Wrocław'),
('Wrocław Gym 2', 'Wrocław'),
('Gdańsk Gym 1', 'Gdańsk'),
('Gdańsk Gym 2', 'Gdańsk'),
('Olsztyn Gym 1', 'Olsztyn'),
('Olsztyn Gym 2', 'Olsztyn'),
('Szczecin Gym 1', 'Szczecin'),
('Szczecin Gym 2', 'Szczecin');

DELIMITER //
-- wyzwalacz, który automatycznie dodaje opis do nowego
 -- użytkownika, jeśli nie podano go ręcznie
CREATE TRIGGER add_default_description
AFTER INSERT ON users
FOR EACH ROW
BEGIN
    INSERT INTO descriptions (user_id, description)
    VALUES (NEW.id, 'This is a default description.');
END;
//

DELIMITER ;

select * from invitations;