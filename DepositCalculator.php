<html>
    <head>
        <title>Deposit Calculator</title>
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
        <h1>Thank you<?php if ($postData['name'] != "") { echo ", " . $postData['name'] . ","; } ?> for using our deposit calculator!</h1>
        <?php if (!empty($error)) : ?>
            <div class="div-error">
                <p>However, we can not process your request because of the following input errors:</p>
                <ul>
                    <?php foreach ($error as $err) : ?>
                        <li><?php echo $err; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if (empty($error)) : ?>
            <div class="div-success">
                <p>Our customer service department will call you tomorrow <?php for ($i = 0; $i < count($phoneTime); $i++) { if ($i < count($phoneTime) - 1) { echo $phoneTime[$i] . " or "; continue; } echo $phoneTime[$i]; } ?> at <?php echo $postData['phoneNumber']; ?>.</p>
                <br />
                <p>The following is the result of the calculation:</p>
                <table id="tblResult">
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
    </body>
</html>