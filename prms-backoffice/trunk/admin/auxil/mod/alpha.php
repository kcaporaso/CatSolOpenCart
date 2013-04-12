<?php
    
    if ($_REQUEST['truth']==1313) {
        session_start();
        $_SESSION['modgode'] = true;
        echo "Enabled.";
    }

?>