<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Texas Treasures - Payment Policy</title>
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
        <h1>Payment Policy</h1>
        <p class="last-updated">Last Updated: <?php echo $lastUpdated; ?></p>


        <?php
        require_once __DIR__ . '/class/entire-website-controle.php';

        $websiteEdite = new Entire_Website_Controle();
        $settings = $websiteEdite->payment_policy();

        if ($settings) {
            $payment_policy = $settings['payment_policy'];

        } else {
            $payment_policy = "Error , please load the page";

        }
        ?>




        <?php echo $payment_policy; ?>




    </div>
    <!-- End privacy policy content -->










    <?php require_once 'parts/Newsletter.php'; ?>
    <?php require_once 'parts/footer.php'; ?>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>