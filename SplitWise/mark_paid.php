<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php?msg=Please login to access the expense tracker.");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amountPaid'], $_POST['username'], $_POST['expenseName'])) {
    $amountPaid = htmlspecialchars($_POST['amountPaid']);
    $username = htmlspecialchars($_POST['username']);
    $expenseName = htmlspecialchars($_POST['expenseName']);

    $xml = simplexml_load_file('data.xml');

    if ($xml !== false) {
        foreach ($xml->expense as $expense) {
            if ($expense->username == $username && $expense->expenseName == $expenseName) {
                $remainingAmount = max(0, $expense->expenseAmount - $amountPaid);
                $expense->expenseAmount = $remainingAmount;
                $xml->asXML('data.xml');

                header("Location: exp.php?user={$username}&expt={$expenseName}&msg=Amount marked as paid successfully");
                exit;
            }
        }

        header("Location: exp.php?user={$username}&expt={$expenseName}&msg=Expense not found");
        exit;
    } else {
        header("Location: exp.php?user={$username}&expt={$expenseName}&msg=Failed to load XML data");
        exit;
    }
} else {
    header("Location: exp.php?user={$username}&expt={$expenseName}&msg=Invalid request");
    exit;
}
?>
