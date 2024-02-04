<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php?msg=Please login to access the expense tracker.");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>SplitWise - MINI PROJECT</title>
</head>
<body>
    <div class="container">
        <h2>SplitWise</h2>
        <a href="logout.php">Logout</a>
        

        <?php
            if(isset($_GET['msg'])) {
                echo "<p class='message'>{$_GET['msg']}</p>";
            }
        ?>
        <form id="expenseForm" action="save_expense.php" method="post">
            <br>
            <br>
            <label for="expenseName">Expense Name:</label>
            <input type="text" id="expenseName" name="expenseName" required>

            <label for="expenseAmount">Expense in Rupees:</label>
            <input type="number" id="expenseAmount" name="expenseAmount" required>

            <label for="members">Members:</label>
            <input type="text" id="membersInput" name="members">

            <ul id="membersList"></ul>

            <input type="submit" value="Submit">
        </form>

        <div class="created-expense-container">
            <h3>Created Expenses</h3>
            <?php include 'display_expenses.php'; ?>
        </div>

        <script>
            document.getElementById('membersInput').addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    addMember();
                }
            });

            function addMember() {
                var membersInput = document.getElementById('membersInput');
                var membersList = document.getElementById('membersList');
                var memberName = membersInput.value.trim();

                if (memberName !== '') {
                    var listItem = document.createElement('li');
                    listItem.textContent = memberName;
                    membersList.appendChild(listItem);
                    membersInput.value = '';
                    var storedMembers = JSON.parse(localStorage.getItem('tempMembers')) || [];
                    storedMembers.push(memberName);
                    localStorage.setItem('tempMembers', JSON.stringify(storedMembers));
                }
            }
            window.onload = function () {
                var storedMembers = JSON.parse(localStorage.getItem('tempMembers')) || [];
                var membersList = document.getElementById('membersList');
                var membersInput = document.getElementById('membersInput');
                storedMembers.forEach(function (memberName) {
                    var listItem = document.createElement('li');
                    listItem.textContent = memberName;
                    membersList.appendChild(listItem);
                });
                membersInput.value = storedMembers.join(', ');
            };
            document.getElementById('expenseForm').addEventListener('submit', function () {
                localStorage.removeItem('tempMembers');
            });
        </script>
    </div>
</body>
</html>