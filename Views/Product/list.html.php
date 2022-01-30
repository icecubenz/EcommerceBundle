<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
if ('index' == $tmpl) {
    $view->extend('EcommerceBundle:Product:index.html.php');
}


?>
<?php if (count($items)): ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered product-list" id="productTable">
            <thead>
            <tr>
                <?php

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'product',
                        'text'       => 'mautic.ecommerce.image',
                        'class'      => 'col-product-image',
                        'default'    => false,
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'product',
                        'orderBy'    => 'p.product_id',
                        'text'       => 'mautic.ecommerce.product.product_id',
                        'class'      => 'visible-md visible-lg col-asset-id',
                        'default'    => true,
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'product',
                        'orderBy'    => 'p.name',
                        'text'       => 'mautic.core.name',
                        'class'      => 'col-product-name',
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'product',
                        'orderBy'    => 'p.price',
                        'text'       => 'mautic.ecommerce.product.price',
                        'class'      => 'col-product-mpn',
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'product',
                        'orderBy'    => 'p.sale_price',
                        'text'       => 'mautic.ecommerce.product.sale_price',
                        'class'      => 'col-product-mpn',
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'product',
                        'orderBy'    => 'p.mpn',
                        'text'       => 'mautic.ecommerce.product.mpn',
                        'class'      => 'col-product-mpn',
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'product',
                        'orderBy'    => 'p.store_id',
                        'text'       => 'mautic.ecommerce.product.store_id',
                        'class'      => 'visible-md visible-lg col-asset-id',
                    ]
                );
                ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $k => $item): ?>
                <tr>
                    <td class="">
                        <?php if ($item->getImageUrl()) : ?>
                        <a href="<?php echo $view['router']->path(
                            'mautic_product_action',
                            ['objectAction' => 'view', 'objectId' => $item->getId()]
                        ); ?>" data-toggle="ajax"
                           >
                            <img src="<?php echo $item->getImageUrl() ?>" alt="<?php echo $item->getName(); ?>" class="img-thumbnail" style="max-width: 100px; display: block; margin: auto"/>
                        </a>
                        <?php endif; ?>
                    </td>
                    <td class="visible-md visible-lg"><?php echo $item->getProductId(); ?></td>
                    <td>
                        <div>
                            <a href="<?php echo $view['router']->path(
                                'mautic_product_action',
                                ['objectAction' => 'view', 'objectId' => $item->getId()]
                            ); ?>"
                               data-toggle="ajax">
                                <?php echo $item->getName(); ?>
                            </a>
                        </div>
                        <?php if ($description = $item->getShortDescription()): ?>
                            <div class="text-muted mt-4">
                                <small><?php echo $description; ?></small>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="visible-md visible-lg"><?php echo $item->getPrice(); ?></td>
                    <td class="visible-md visible-lg"><?php echo $item->getSalePrice(); ?></td>
                    <td class="visible-md visible-lg"><?php echo $item->getMpn(); ?></td>
                    <td class="visible-md visible-lg"><?php echo $item->getStoreId(); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="panel-footer">
        <?php echo $view->render(
            'MauticCoreBundle:Helper:pagination.html.php',
            [
                'totalItems' => count($items),
                'page'       => $page,
                'limit'      => $limit,
                'menuLinkId' => 'mautic_product_index',
                'baseUrl'    => $view['router']->path('mautic_product_index'),
                'sessionVar' => 'product',
            ]
        ); ?>
    </div>
<?php else: ?>
    <?php echo $view->render('MauticCoreBundle:Helper:noresults.html.php', ['tip' => 'mautic.ecommerce.product.noresults.tip']); ?>
<?php endif; ?>

<?php echo $view->render(
    'MauticCoreBundle:Helper:modal.html.php',
    [
        'id'     => 'ProductPreviewModal',
        'header' => false,
    ]
);
