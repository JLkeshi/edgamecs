<?php 
include 'database/db_connect.php'; 

// Initialize leaderboard (or create table if not exists)
$query = "CREATE TABLE IF NOT EXISTS leaderboard (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    score INT NOT NULL,
    difficulty ENUM('Beginner', 'Intermediate', 'Advanced') NOT NULL,
    game_mode ENUM('True or False', '0 or 1', 'Random Questions') NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/quiz.css">
    <link rel="icon" type="image/png" href="img/logo.png">
    <title>Random Quiz</title>
    <style>
        /* Body and Background */
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
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

        /* Video Background */
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
            background: rgba(0, 0, 0, 0.7); /* Dark semi-transparent background */
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
            background-color: white; /* Card background color */
            color: black; /* Text color inside flashcard */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Shadow effect */
            font-size: 30px;
            transition: transform 0.6s; /* Flip transition duration */
            transform-style: preserve-3d; /* For 3D flip effect */
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        /* Flashcard Flip Effect */
        .flashcard.show-answer {
            transform: rotateY(180deg); /* Flip to show answer */
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
            backface-visibility: hidden; /* Hide back when front is visible */
            border-radius: 10px; /* Ensure rounded corners */
        }

        /* Back of the Flashcard */
        .flashcard .back {
            transform: rotateY(180deg); /* Initial flip for back side */
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
            background-color: #ffb23e; /* Button color */
            color: black;
            border-radius: 50px; /* Rounded button shape */
            transition: background-color 0.3s;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        /* Button Hover Effect */
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
            background-color: #333; /* Back button color */
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }

        .backButton:hover {
            background-color: #555; /* Hover effect for back button */
        }
    </style>
</head>
<body>

<video class="video-bg" src="img/S.E design website random.mp4" autoplay loop muted playsinline></video>

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
            <div id="question"></div>
        </div>
        <div class="back">
            <div id="answer">Answer</div>
        </div>
    </div>
    <div class="options" id="options"></div>
    <div id="score">Your score: <span id="correctAnswers">0</span> correct answers out of <span id="totalQuestions"></span>.</div>
</div>

<script>
    const questions = [
    { question: "PHP output?", options: ["echo", "print"], correct: "echo" },
    { question: "JS constant?", options: ["const", "let"], correct: "const" },
    { question: "Python if?", options: ["==", "="], correct: "==" },
    { question: "C++ loop?", options: ["for", "loop"], correct: "for" },
    { question: "Python comment?", options: ["//", "'''"], correct: "'''" },
    { question: "JS call?", options: ["func()", "name()"], correct: "name()" },
    { question: "End PHP?", options: ["end;", "?>"], correct: "?>" },
    { question: "JS array?", options: ["[]", "{}"], correct: "[]" },
    { question: "Python var?", options: ["x = 10", "var x"], correct: "x = 10" },
    { question: "Java var?", options: ["int", "let"], correct: "int" }
];

    let currentQuestionIndex = 0;
    let correctAnswers = 0;

    const questionElement = document.getElementById('question');
    const answerElement = document.getElementById('answer');
    const flashcard = document.getElementById('flashcard');
    const totalQuestions = questions.length;
    document.getElementById('totalQuestions').textContent = totalQuestions;

    let difficulty = 'Beginner'; // Can be dynamic depending on your quiz
    let gameMode = 'Random Questions'; // Update based on the mode selected

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
        flashcard.style.display = 'none';
        document.getElementById('options').style.display = 'none';
        document.getElementById('score').style.display = 'block';
        document.getElementById('correctAnswers').textContent = correctAnswers;

        // Record the score to the database (via PHP)
        const username = prompt("Enter your name for the leaderboard: ");
        if (username) {
            fetch('leaderboard.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `username=${encodeURIComponent(username)}&score=${correctAnswers}&difficulty=${encodeURIComponent(difficulty)}&game_mode=${encodeURIComponent(gameMode)}`
            }).then(response => response.text()).then(data => {
                alert("Your score has been recorded!");
            });
        }
    }

    function goBack() {
        window.location.href = 'Quiztopic.php';
    }

    document.getElementById('backButton').addEventListener('click', goBack);

    // Initialize the first question
    showQuestion();
</script>

</body>
</html>
