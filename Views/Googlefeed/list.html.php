<?php
if ('index' == $tmpl) {
    $view->extend('EcommerceBundle:Googlefeed:index.html.php');

}
?>
<?php if (count($items)): ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered googlefeed-list" id="googlefeedTable">
            <thead>
                <tr>
                <?php
                    echo $view->render(
                        'MauticCoreBundle:Helper:tableheader.html.php',
                        [
                            'sessionVar' => 'googlefeed',
                            'orderBy'    => 'gf.id',
                            'text'       => 'mautic.core.id',
                            'class'      => 'col-asset-id',
                        ]
                    );
                    echo $view->render(
                        'MauticCoreBundle:Helper:tableheader.html.php',
                        [
                            'sessionVar' => 'googlefeed',
                            'text'       => 'mautic.ecommerce.url',
                            //'default'    => true,
                        ]
                    );
                    echo $view->render(
                        'MauticCoreBundle:Helper:tableheader.html.php',
                        [
                            'sessionVar' => 'googlefeed',
                            'orderBy'    => 'gf.status',
                            'text'       => 'mautic.ecommerce.status',
                        ]
                    );
                    echo $view->render(
                        'MauticCoreBundle:Helper:tableheader.html.php',
                        [
                            'sessionVar' => 'googlefeed',
                            'orderBy'    => 'gf.dateModified',
                            'text'       => 'mautic.ecommerce.date',
                        ]
                    );
                    echo $view->render(
                        'MauticCoreBundle:Helper:tableheader.html.php',
                        [
                            'sessionVar' => 'googlefeed',
                            'text'       => 'mautic.ecommerce.action',
                            //'default'    => true,
                        ]
                    );
                ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $k => $item): ?>
                <tr>
                    <td class="">
                        <span><?php echo $item->getId(); ?></span>
                    </td>
                    <td class="visible-md visible-lg">
                        <a href="<?php echo $item->getUrl(); ?>" target="_blank">
                            <span><?php echo $item->getUrl(); ?></span>
                        </a>
                    </td>
                    <td class="">
                        <?php if ($item->getStatus()) : ?>
                        <span class="label label-primary pa-4">Enabled</span>
                        <?php else : ?>
                        <span class="label label-default pa-4">Disabled</span>
                        <?php endif; ?>
                    </td>
                    <td class="">
                        <?php echo $item->getDateModified()->format('Y-m-d H:i:s'); ?>
                    </td>
                    <td class="">
                        <?php
                        $editUrl = $view['router']->path(
                            'mautic_googlefeed_action',
                            [
                                'objectAction' => 'edit',
                                'objectId' => $item->getId()
                            ]
                        );
                        ?>
                        <a href="<?php echo $editUrl; ?>" data-toggle="ajax">
                            <span>Edit</span>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <?php echo $view->render('MauticCoreBundle:Helper:noresults.html.php', []); ?>
<?php endif; ?>