<?php

$view->extend('MauticCoreBundle:Default:content.html.php');
$view['slots']->set('mauticContent', 'googlefeed');

$header = ($entity->getId())
    ?
    $view['translator']->trans('mautic.ecommerce.googlefeed.edit')
    :
    $view['translator']->trans('mautic.ecommerce.googlefeed.new');
$view['slots']->set('headerTitle', $header);

echo $view['form']->start($form);

?>

    <!-- start: box layout -->
    <div class="box-layout">

        <div class="col-md-9 bg-auto height-auto bdr-r">
            <div class="pa-md">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo $view['form']->row($form['url']); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <?php echo $view['form']->row($form['status']); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <?php echo $view['form']->row($form['shopId']); ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo $view['form']->row($form['userName']); ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo $view['form']->row($form['password']); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 bg-white height-auto"></div>

    </div>
    <!--/ box layout -->

<?php echo $view['form']->end($form); ?>