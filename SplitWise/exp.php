<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Details</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #b0c4fc;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.2);;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 80%;
            max-width: 400px;
            text-align: center;
            backdrop-filter: blur(10px); /* Add blur effect */
        }

        h2 {
            color: #333;
        }

        form {
            margin-top: 20px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        p {
            margin-top: 20px;
            font-size: 18px;
            color: #333;
        }

        a {
            display: block;
            margin-top: 20px;
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Expense Details</h2>

        <?php
        $username = $_GET['user'] ?? '';
        $expenseName = $_GET['expt'] ?? '';

        $xml = simplexml_load_file('data.xml');

        $amountOwed = 0;
        $remainingAmount = 0;

        foreach ($xml->expense as $expense) {
            if ($expense->username == $username && $expense->expenseName == $expenseName) {
                $amountOwed = ($expense->expenseAmount) / count(explode(',', $expense->members));

                // Check if the entered amount is posted
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amountPaid'])) {
                    $amountPaid = htmlspecialchars($_POST['amountPaid']);
                    // You should validate and sanitize the entered amount

                    $remainingAmount = max(0, $expense->expenseAmount - $amountPaid);
                    // Update the XML with the new remaining amount
                    $expense->expenseAmount = $remainingAmount;
                    $xml->asXML('data.xml');
                } else {
                    $remainingAmount = $expense->expenseAmount;
                }
            }
        }

        // Display the form to mark as paid
        echo "<form action='mark_paid.php' method='post'>";
        echo "<textarea name='amountPaid' placeholder='Enter Amount'></textarea>";
        echo "<input type='hidden' name='username' value='{$username}'>";
        echo "<input type='hidden' name='expenseName' value='{$expenseName}'>";
        echo "<input type='submit' value='Mark Paid'>";
        echo "</form>";

        // Display the remaining amount owed
        echo "<p>You are owed: {$remainingAmount} Rupees</p>";
        ?>

        <a href="index.php">Go back to Home</a>
    </div>
</body>
</html>
