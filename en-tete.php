<!DOCTYPE html>
<html>
<head>
   <title><?php echo $strTitreApplication; ?></title>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
   <link rel="stylesheet" type="text/css" href="<?php echo $strNomFichierCSS; ?>" /></head>
<body>
   <form id="frmSaisie" method="" action="">
      <div id="divEntete" class="">
         <p class="sTitreApplication">
            <?php echo "$strTitreApplication\n"; ?>
            <span class="sTitreSection">
               <br />par <span class="sRouge"><?php echo $strNomAuteur; ?></span>
            <input id="btnActualiser" name="btnActualiser" type="button" value="Actualiser"
               style="font-size:12px;vertical-align:3px; color:black; " 
               onclick="window.location = document.location.href;" />
               </span>
         </p>
      </div>
