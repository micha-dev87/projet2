<!DOCTYPE html>
<html>
<head>
    <title><?php echo $strTitreApplication; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo $strNomFichierCSS; ?>"/>
    <!--    bootstrap integration-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<div class="container mt-5">

    <div class="card text-center">
        <div id="divEntete" class="card-header">
            <p class="sTitreApplication">
                <?php echo "$strTitreApplication\n"; ?>
                <span class="sTitreSection">
               <br/>par <span class="sRouge"><?php echo $strNomAuteur; ?></span>

            </p>
        </div>

        <div class="card-body">
