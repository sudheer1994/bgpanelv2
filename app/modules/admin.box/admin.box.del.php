<?php

/**
 * LICENSE:
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @package		Bright Game Panel V2
 * @version		0.1
 * @category	Systems Administration
 * @author		warhawk3407 <warhawk3407@gmail.com> @NOSPAM
 * @copyright	Copyleft 2015, Nikita Rousseau
 * @license		GNU General Public License version 3.0 (GPLv3)
 * @link		http://www.bgpanel.net/
 */

/**
 * Load Plugin
 */

require( MODS_DIR . '/' . basename(__DIR__) . '/admin.box.class.php' );

$module = new BGP_Module_Admin_Box_Del();

/**
 * Call GUI Builder
 */
$gui = new Core_GUI( $module );

/**
 * Javascript Generator
 */
$js = new Core_JS_GUI();

/**
 * Build Page Header
 */
$gui->getHeader();

/**
 * Object Attributes
 */
$thisObjId = $GLOBALS['OBJ_ID'];
if ( empty($thisObjId) || !is_numeric($thisObjId) ) {
	trigger_error('Object ID is missing or malformed !');
}

// DB
$dbh = Core_DBH::getDBH(); // Get Database Handle

$rows = array();

$sth = $dbh->prepare("
	SELECT box_id, name
	FROM " . DB_PREFIX . "box
	WHERE
		box_id = :box_id
	;");

$sth->bindParam(':box_id', $thisObjId);

$sth->execute();

$rows = $sth->fetchAll( PDO::FETCH_ASSOC );
if ( empty($rows) ) {
	trigger_error('Invalid Object ID !');
}
$rows = $rows[0];


/**
 * PAGE BODY
 */
//------------------------------------------------------------------------------------------------------------+
?>
					<!-- CONTENTS -->
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<div class="panel panel-default">

								<div class="panel-heading">
									<h3 class="panel-title"><?php echo T_('Delete Operation'); ?></h3>
								</div>

								<div class="panel-body">
									<div class="alert alert-danger" role="alert">
										<strong><?php echo T_('Are you sure you want to delete box:'); ?>
										<h3><?php echo htmlspecialchars( $rows['name'], ENT_QUOTES); ?> (<?php echo htmlspecialchars( $rows['box_id'], ENT_QUOTES); ?>) ?</h3>
										</strong>
									</div>

									<div class="well" style="max-width: 400px; margin: 0 auto 10px; padding-left: 35px; padding-right: 35px;">
										<form ng-submit="processForm()">
											<div class="row">
												<div class="text-center">
													<button class="btn btn-danger btn-lg btn-block" type="submit"><?php echo T_('Yes'); ?>,&nbsp;<?php echo T_('delete!'); ?></button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- END: CONTENTS -->

					<!-- SCRIPT -->
<?php

/**
 * Generate AngularJS Code
 */

$js->getAngularController( 'deleteBox', $module::getModuleName( '/' ), array(), './admin/box');

?>
					<!-- END: SCRIPT -->

<?php
//------------------------------------------------------------------------------------------------------------+
/**
 * END: PAGE BODY
 */

/**
 * Build Page Footer
 */
$gui->getFooter();

// Clean Up
unset( $module, $gui, $js );

?>