<?php

use core\Session;

$dangers = Session::getInstance()->getFlash('error');
$successes = Session::getInstance()->getFlash('success');
?>

<?php if($dangers): ?>
    <?php foreach($dangers as $danger): ?>
        <div class="alert alert-danger" role="alert">
            <?= $danger ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>


<?php if($successes): ?>
    <?php foreach($successes as $success): ?>
        <div class="alert alert-success" role="alert">
            <?= $success ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
