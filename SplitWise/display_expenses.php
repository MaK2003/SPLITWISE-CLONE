<?php
session_start();

$username = $_SESSION['username'] ?? '';

if (!empty($username)) {
    $xml = simplexml_load_file('data.xml');

    foreach ($xml->expense as $expense) {
        if ($expense->username == $username) {
            if ((int)$expense->expenseAmount !== 0) {
                $encodedUsername = urlencode($username);
                $encodedExpenseName = urlencode($expense->expenseName);

                echo "<div class='expense-box' onclick='redirectToExpPage(\"{$encodedUsername}\", \"{$encodedExpenseName}\")'>";
                echo "<p><strong>Expense Name:</strong> {$expense->expenseName}</p>";
                echo "<p><strong>Expense Amount:</strong> {$expense->expenseAmount} Rupees</p>";
                echo "</div>";
            } else {
                unset($expense[0]);
                $xml->asXML('data.xml');
            }
        }
    }
} else {
    echo "<p class='message'>Please login to view expenses.</p>";
}
?>
<script>
function redirectToExpPage(username, expenseName) {
    var url = 'exp.php?user=' + encodeURIComponent(username) + '&expt=' + encodeURIComponent(expenseName);
    window.location.href = url;
}
</script>
