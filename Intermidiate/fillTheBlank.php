<?php
// Define a list of questions and answers
$questions = [
    "answer1" => ["What is the process of finding and fixing errors in code?", "Debugging"],
    "answer2" => ["What data type is used to store True or False values in Python?", "Boolean"],
    "answer3" => ["What keyword is used to define a function in Python?", "def"],
    "answer4" => ["Which loop is used to iterate over a sequence in Python?", "for"],
    "answer5" => ["What is the output of 3 ** 2 in Python?", "9"]
];

// Shuffle the questions for randomness
$randomQuestions = array_slice($questions, 0, 5);

// Initialize variables for feedback and score
$feedback = "";
$score = 0;

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userAnswers = $_POST; // Get user answers from form
    foreach ($randomQuestions as $key => $value) {
        if (isset($userAnswers[$key]) && strcasecmp($userAnswers[$key], $value[1]) === 0) {
            $score++;
        }
    }

    // Prepare feedback message
    $feedback = "<h2>Your Score: $score / 5</h2>";
    if ($score == 5) {
        $feedback .= "<p>Perfect! 🎉 You know Python well!</p>";
    } elseif ($score > 0) {
        $feedback .= "<p>Good job! Try to improve your score!</p>";
    } else {
        $feedback .= "<p>Keep practicing! Python mastery is just a step away!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fill-in-the-Blank Game</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #000000, #333333);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            color: #ffffff;
        }

        .game-container {
            background: rgba(0, 0, 0, 0.8); /* Black with 80% opacity */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
            width: 300px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #f9f9f9;
        }

        form p {
            margin: 15px 0;
        }

        input[type="text"] {
            width: calc(100% - 20px);
            padding: 5px;
            margin-top: 5px;
            border: 1px solid #444;
            border-radius: 5px;
            background: #222;
            color: #fff;
        }

        button {
            background: #007BFF;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #0056b3;
        }

        .feedback {
            margin-top: 20px;
            font-size: 18px;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="game-container">
        <h1>Python Fill-in-the-Blank</h1>
        <form method="POST">
            <?php foreach ($randomQuestions as $key => $question): ?>
                <p><?= $question[0]; ?> <input type="text" name="<?= $key; ?>" placeholder="Your answer"></p>
            <?php endforeach; ?>
            <button type="submit">Submit Answers</button>
        </form>

        <!-- Display feedback after form submission -->
        <?php if (!empty($feedback)): ?>
            <div class="feedback">
                <?= $feedback; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
