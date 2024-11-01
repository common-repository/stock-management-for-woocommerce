<?php

//Check WooCommerce is installed --------------------------------------------------------------------
$wooCommerceActivated = false;
if ( class_exists( 'WooCommerce' ) )
{
    $wooCommerceActivated = true;
}
//Check WooCommerce is installed --------------------------------------------------------------------


?>

<!-- Main container -->
<div id="mp-stoman-container" class="mp-stoman container">

    <!-- ajax loader -->
    <div class="mp-stoman-ajax-loader" id="mp-stoman-ajaxloader">
        <img src="<?php echo mp_stoman_consts_pluginUrl . "/images/loader.gif"; ?>" />
    </div>
    <!-- ajax loader -->

	<?php

	//including top menu
	include( mp_stoman_consts_pluginPath . '/controls/topmenu.php' );

    ?>

	<!-- control title -->
	<div class="mp-stoman-controltitle">

		<?php

		if ( $wooCommerceActivated == true )
		{

			?><div class="mp-stoman-controltitle-title" id="mp-stoman-control-titlemain">Products Stock Details Management</div><?php

		}
		else
		{

            ?><div class="mp-stoman-controltitle-title" id="mp-stoman-control-titlemain">WooCommerce plugin not installed or not activated</div><?php

		}
		
		?>

		<div class="clear"></div>

	</div>
	<!-- control title -->

	<?php if ( $wooCommerceActivated == true ) { ?>

	<div id="mp-stoman-contentpane">

		<div class="row">

			<div class="col-md-3">

				<div class="mp-stoman-panels-panel">

					<div class="mp-stoman-panels-panel-header">

						<div class="mp-stoman-panels-panel-title">
							<?php _e("Search and Filter Products", "mp-stoman") ?>
						</div>

						<div class="clear"></div>

					</div>

					<div class="mp-stoman-panels-panel-body">

                        <div style="height:10px;"></div>

                        <div>
                            <strong>
                                <?php _e("Search by Product Name or SKU:", "mp-stoman") ?>
                            </strong>
                        </div>

                        <div class="input-group">
                            <input type="text" class="form-control mp-stoman-panels-input" id="mp-stoman-dashboard-txt-searchproduct" />
                            <span class="input-group-addon">
                                <i class="fa fa-bolt"></i>
                            </span>
                        </div>

                        <div style="height:10px;"></div>

                        <button type="button" class="btn btn-labeled btn-primary" onclick="mp_stoman_dashboard_loadproducts();">
                            <span class="btn-label">
                                <i class="glyphicon glyphicon-search"></i>
                            </span><?php _e("Search", "mp-stoman") ?>
                        </button>

                        <div class="clear"></div>

                        <div class="mp-stoman-panels-sidepanelseparator"></div>

                        <div style="height:10px;"></div>

                        <div>
                            <strong>
								<?php _e("Filter By Product Category:", "mp-stoman") ?>
                            </strong>
                        </div>

                        <div id="mp-stoman-tree"></div>

                        <div class="clear"></div>

					</div>

				</div>

			</div>

            <div class="col-md-9">

                <div class="mp-stoman-panels-panel">

                    <div class="mp-stoman-panels-panel-header">

                        <div class="mp-stoman-panels-panel-title">
                            <?php _e("Products List", "mp-stoman") ?>
                        </div>

                        <div class="clear"></div>

                    </div>

                    <div class="mp-stoman-panels-panel-body">

                        <table class="table table-striped" id="prodTable">
                            <thead>
                                <tr>
                                    <th>
                                        <?php _e("Id", "mp-stoman") ?>
                                    </th>
                                    <th>
                                        <?php _e("SKU", "mp-stoman") ?>
                                    </th>
                                    <th>
                                        <?php _e("Name", "mp-stoman") ?>
                                    </th>
                                    <th>
                                        <?php _e("Enable Stock", "mp-stoman") ?>
                                    </th>
                                    <th>
                                        <?php _e("Stock Qty", "mp-stoman") ?>
                                    </th>
                                    <th>
                                        <?php _e("Action", "mp-stoman") ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>

                </div>

            </div>

		</div>

	</div>

	<?php } ?>

	<div class="clear"></div>
	
</div>
