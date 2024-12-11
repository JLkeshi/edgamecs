<?php
include 'database/db_connect.php';

// Check if form data is received via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST data
    $username = $_POST['username'];
    $score = $_POST['score'];
    $difficulty = $_POST['difficulty'];
    $game_mode = $_POST['game_mode'];
    $subj = $_POST['subj']; // Subject

    // Ensure all variables are properly sanitized
    $username = mysqli_real_escape_string($conn, $username);
    $score = (int)$score;
    $difficulty = mysqli_real_escape_string($conn, $difficulty);
    $game_mode = mysqli_real_escape_string($conn, $game_mode);
    $subj = mysqli_real_escape_string($conn, $subj);

    // Prepare the SQL query to insert data into the leaderboard table
    $query = "INSERT INTO leaderboard (username, score, difficulty, game_mode, subj) 
              VALUES ('$username', $score, '$difficulty', '$game_mode', '$subj')";
              
// Initialize leaderboard (or create table if not exists)
$query = "CREATE TABLE IF NOT EXISTS leaderboard (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    score INT NOT NULL,
    difficulty ENUM('Beginner', 'Intermediate', 'Advanced') NOT NULL,
    game_mode ENUM('True or False', '0 or 1', 'Random Questions') NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

    // Close the database connection
    mysqli_close($conn);
} else {
    echo "";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/quiz.css">
    <link rel="icon" type="image/png" href="img/logo.png">
    <title>JavaScript True or False Quiz</title>
    <style>
        /* Body and Background */
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background-image: url("img/bg2.png");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            text-align: center;
            background-color: #ffcc8e;
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Video Background (optional, comment out if not needed) */
        .video-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        /* Quiz Container Styling */
        .quiz-container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            box-sizing: border-box;
            text-align: center;
            perspective: 1000px; /* 3D perspective for card flip effect */
        }

        /* Flashcard Styling */
        .flashcard {
            width: 400px;
            height: 400px;
            padding: 20px;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            background-color: white;
            color: black;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 30px;
            transition: transform 0.6s;
            transform-style: preserve-3d;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        /* Flashcard Flip Effect */
        .flashcard.show-answer {
            transform: rotateY(180deg);
        }

        /* Flashcard Front and Back Styling */
        .flashcard .front, .flashcard .back {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
            backface-visibility: hidden;
            border-radius: 10px;
        }

        /* Back of the Flashcard */
        .flashcard .back {
            transform: rotateY(180deg);
        }

        /* Button Styling for Options */
        .options button {
            display: inline-block;
            width: 45%;
            margin: 10px 5px;
            padding: 10px;
            font-size: 18px;
            cursor: pointer;
            border: none;
            background-color: #ffb23e;
            color: black;
            border-radius: 50px;
            transition: background-color 0.3s;
        }

        .options button:hover {
            background-color: #555;
        }

        /* Score Display */
        #score {
            display: none;
            color: white;
            font-size: 24px;
            margin-top: 20px;
        }

        /* Back Button Styling */
        .backButton {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #333;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }

        .backButton:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<!-- Background Video (optional, comment out if not needed) -->
<video class="video-bg" src="img/S.E design website 01.mp4" autoplay loop muted playsinline></video>
    <!-- Back button -->
    <button id="backButton" class="fancy">
        <span class="top-key"></span>
        <span class="text">Back</span>
        <span class="bottom-key-1"></span>
        <span class="bottom-key-2"></span>
    </button

<div class="quiz-container">
    <div class="flashcard" id="flashcard">
        <div class="front">
            <div id="question">Loading Question...</div>
        </div>
        <div class="back">
            <div id="answer">Answer</div>
        </div>
    </div>
    <div class="options">
        <button onclick="checkAnswer('1')">1</button>
        <button onclick="checkAnswer('0')">0</button>
    </div>
    <div id="score">Your score: <span id="correctAnswers">0</span> correct answers out of <span id="totalQuestions"></span>.</div>
</div>

<script>
    const questions = [
        { question: "JavaScript is a programming language used for web development.", correct: "1" },
        { question: "JavaScript is used to style HTML elements.", correct: "0" },
        { question: "JavaScript can be used to manipulate the DOM (Document Object Model).", correct: "1" },
        { question: "JavaScript code runs only on the client-side.", correct: "0" },
        { question: "JavaScript supports object-oriented programming.", correct: "1" },
        { question: "In JavaScript, functions are first-class objects.", correct: "1" },
        { question: "The 'alert()' function is used to create pop-up windows in JavaScript.", correct: "1" },
        { question: "JavaScript is a statically typed language.", correct: "0" },
        { question: "JavaScript can be used to fetch data from APIs.", correct: "1" },
        { question: "JavaScript supports asynchronous programming using Promises and async/await.", correct: "1" }
    ];

    let currentQuestionIndex = 0;
    let correctAnswers = 0;
    const flashcard = document.getElementById('flashcard');
    const questionElement = document.getElementById('question');
    const answerElement = document.getElementById('answer');
    const totalQuestions = questions.length;
    document.getElementById('totalQuestions').textContent = totalQuestions;
    

    function showQuestion() {
        const currentQuestion = questions[currentQuestionIndex];
        questionElement.textContent = currentQuestion.question;
        answerElement.textContent = `Answer: ${currentQuestion.correct}`;
        flashcard.classList.remove('show-answer');

        const optionsContainer = document.getElementById('options');
        optionsContainer.innerHTML = '';
        currentQuestion.options.forEach(option => {
            const button = document.createElement('button');
            button.textContent = option;
            button.onclick = () => checkAnswer(option);
            optionsContainer.appendChild(button);
        });
    }

    function checkAnswer(answer) {
        const currentQuestion = questions[currentQuestionIndex];
        if (answer === currentQuestion.correct) {
            correctAnswers++;
        }
        flashcard.classList.add('show-answer');

        setTimeout(() => {
            currentQuestionIndex++;
            if (currentQuestionIndex < questions.length) {
                showQuestion();
            } else {
                showScore();
            }
        }, 1000);
    }

    function showScore() {
    document.querySelector('.flashcard').style.display = 'none';
    document.querySelector('.options').style.display = 'none';
    document.getElementById('score').style.display = 'block';
    document.getElementById('correctAnswers').textContent = correctAnswers;

    // Get the subject dynamically
    const subject = "Java Script"; // Set this dynamically if needed, based on your page or user selection.

    // Record the score to the database (via PHP)
    const username = prompt("Enter your name for the leaderboard: ");
    if (username) {
        fetch('leaderboard.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `username=${encodeURIComponent(username)}&score=${correctAnswers}&difficulty=Beginner&game_mode=0 or 1&subj=${encodeURIComponent(subject)}`
        }).then(response => response.text()).then(data => {
            alert("Your score has been recorded!");
        });
    }
}


    function goBack() {
        window.location.href = 'Quiz1Or0.php'';
    }

    document.getElementById('backButton').addEventListener('click', goBack);

    // Initialize the first question
    showQuestion();
</script>

</body>
</html>