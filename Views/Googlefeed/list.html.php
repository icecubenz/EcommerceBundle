<?php
if ('index' == $tmpl) {
    $view->extend('EcommerceBundle:Googlefeed:index.html.php');

}
?>
<?php if (count($items)): ?>

<?php else: ?>
    <?php echo $view->render('MauticCoreBundle:Helper:noresults.html.php', []); ?>
<?php endif; ?>