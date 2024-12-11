<?php
// Define a list of Python code questions and correct answers
$questions = [
    "answer1" => [
        "print('Hello World') uses parentheses. Fill the missing code: ___('Hello World')", 
        "print"
    ],
    "answer2" => [
        "Fix this syntax error in a conditional statement: `if x ___ 5:` (missing operator)", 
        "=="
    ],
    "answer3" => [
        "Fill in the loop header to iterate over a list `my_list`: `for item ___ my_list:`", 
        "in"
    ],
    "answer4" => [
        "Complete this function definition: `def my_function___:`", 
        "()"
    ],
    "answer5" => [
        "Add the keyword to raise an exception: `___ ValueError('Invalid value')`", 
        "raise"
    ]
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
        if (isset($userAnswers[$key]) && strcasecmp(trim($userAnswers[$key]), $value[1]) === 0) {
            $score++;
        }
    }

    // Prepare feedback message
    $feedback = "<h2>Your Score: $score / 5</h2>";
    if ($score == 5) {
        $feedback .= "<p>Excellent! You're a Python pro! 🎉</p>";
    } elseif ($score > 0) {
        $feedback .= "<p>Good work! Keep practicing to perfect your Python skills!</p>";
    } else {
        $feedback .= "<p>Don't give up! Debugging mastery takes practice!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Python Code Debugging Game</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #1e1e1e, #333);
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
            width: 400px;
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
        <h1>Python Debugging Game</h1>
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
