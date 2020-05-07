<?php

use core\Helper;
use core\View;

/**
 * @var $this View
 * @var $totalPages int
 */

$currentPage = Helper::getCurrentPage();

?>

<?php if ($totalPages > 1): ?>

    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php foreach (range(1, $totalPages) as $page): ?>
                <li class="page-item <?= $currentPage === $page ? 'disabled active' : '' ?>">
                    <a class="page-link" href="?<?= Helper::getPageUrl($page) ?>"><?= $page ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
<?php endif; ?>

