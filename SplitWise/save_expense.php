<?php

session_start();

if (!file_exists('data.xml')) {
    $xml = new SimpleXMLElement('<expenses></expenses>');
    $xml->asXML('data.xml');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_COOKIE['username'], $_POST['expenseName'], $_POST['expenseAmount'], $_POST['members'])) {
        $username = htmlspecialchars($_COOKIE['username']);
        $expenseName = htmlspecialchars($_POST['expenseName']);
        $expenseAmount = htmlspecialchars($_POST['expenseAmount']);
        $members = htmlspecialchars($_POST['members']);

        $xml = simplexml_load_file('data.xml');

        if ($xml !== false) {
            $expense = $xml->addChild('expense');
            $expense->addChild('username', $username);
            $expense->addChild('expenseName', $expenseName);
            $expense->addChild('expenseAmount', $expenseAmount);
            $membersNode = $expense->addChild('members');
            
            $membersArray = explode(', ', $members);
            foreach ($membersArray as $member) {
                $memberNode = $membersNode->addChild('member', $member);
            }

            if ($xml->asXML('data.xml')) {
                header("Location: index.php?msg=Expense saved successfully");
                exit;
            } else {
                header("Location: index.php?msg=Failed to save XML data");
                exit;
            }
        } else {
            header("Location: index.php?msg=Failed to load XML data");
            exit;
        }
    } else {
        header("Location: index.php?msg=Invalid form data");
        exit;
    }
} else {
    header("Location: index.php?msg=Invalid request");
    exit;
}
?>
