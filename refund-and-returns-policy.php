<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Texas Treasures - Refund and Returns Policy</title>
    <link rel="icon" type="image/svg+xml" href="./images/favicon.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="style/privacy-policy.css">
</head>
<body class="bg-gray-50">
<?php include 'parts/navbar.php'; ?>
    


<!-- start privacy policy content -->
<?php
$lastUpdated = "March 14, 2024";
?>



        <div class="privacy-policy">
        <h1>Refund and Returns Policy</h1>
        
        
            <?php
        require_once __DIR__ . '/class/entire-website-controle.php';

        $websiteEdite = new Entire_Website_Controle();
        $settings = $websiteEdite->Refund_and_Returns_Policy();

        if ($settings) {
            $refund_returns_policy = $settings['refund_returns_policy'];

        } else {
            $refund_returns_policy = "Error , please load the page";

        }
        ?>




        <?php echo $refund_returns_policy; ?>


    </div>










<?php require_once 'parts/Newsletter.php'; ?>
    <?php require_once 'parts/footer.php'; ?>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>