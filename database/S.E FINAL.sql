CREATE TABLE leaderboard (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    score INT NOT NULL,
    difficulty ENUM('Beginner', 'Intermediate', 'Advanced') NOT NULL,
    game_mode ENUM('True or False', '0 or 1', 'Random Questions') NOT NULL,  -- New game modes
    subj VARCHAR(255) NOT NULL,  -- New column for subject/category
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
