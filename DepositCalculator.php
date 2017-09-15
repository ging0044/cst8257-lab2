<html>
    <head>
        <title>Deposit Calculator</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
        <?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$postData = $_POST;
$error = array();
$phoneTime = $postData["phoneTime"];
$amount = 0;
$interest = 0;
foreach ($postData as $key => $value) {
    if ($key === "contactMethod" && $value === "phone" && !$postData["phoneTime"]) {
        array_push($error, "phoneTime is required if phone is contact method");
        continue;
    }
    if ($value == "") {
        array_push($error, "$key is required");
        continue;
    }
    if (($key === "principalAmount" || $key === "interestRate" || $key === "depositYears") && !is_numeric($value)) {
        array_push($error, "$key must be a number");
        continue;
    }
    else if ($key === "principalAmount" && $value <= 0) {
        array_push($error, "$key must be greater than 0");
        continue;
    }
    else if ($key === "interestRate" && $value < 0) {
        array_push($error, "$key cannot be negative");
        continue;
    }
    else if ($key === "depositYears" && ($value > 20 || $value < 1)) {
        array_push($error, "$key must be between 1 and 20");
        continue;
    }
}
?>
    </head>
    <body>
        <div class="container">
            <h1>Thank you<?php if ($postData['name'] != "") { echo ", " . $postData['name'] . ","; } ?> for using our deposit calculator!</h1>
            <?php if (!empty($error)) : ?>
                <div class="div-error">
                    <p>However, we cannot process your request because of the following input errors:</p>
                    <ul>
                        <?php foreach ($error as $err) : ?>
                            <li><?php echo $err; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if (empty($error)) : ?>
                <div class="div-success">
                    <?php if ($postData['contactMethod'] === "phone") : ?>
                        <p>Our customer service department will call you tomorrow <?php for ($i = 0; $i < count($phoneTime); $i++) { if ($i < count($phoneTime) - 1) { echo $phoneTime[$i] . " or "; continue; } echo $phoneTime[$i]; } ?> at <?php echo $postData['phoneNumber']; ?>.</p>
                    <?php endif; ?>
                    <?php if ($postData['contactMethod'] === "email") : ?>
                        <p>Our customer service department will email you tomorrow at <?php echo $postData['emailAddress']; ?>.</p>
                    <?php endif; ?>
                    <br />
                    <p>The following is the result of the calculation:</p>
                    <table id="tblResult" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Principal at Year Start</th>
                                <th>Interest for the Year</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $amount = $postData['principalAmount'];
                            ?>
                            <?php for ($i = 1; $i <= $postData['depositYears']; $i++) : ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php printf("\$%.2f", $amount); ?></td>
                                <td><?php $interest = $amount * $postData['interestRate'] / 100; $amount += $interest; printf("\$%.2f", $interest); ?></td>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
            </div>
            <?php endif; ?>
        </div>
    </body>
</html>