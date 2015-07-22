{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2015 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}

<div class="bootstrap modal fade" id="psphipay-poll-modal" tabindex="-1" role="dialog" aria-labelledby="psphipay-poll-modal-label">
	<form class="form-horizontal modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="psphipay-poll-modal-label">Why doing that?!</h4>
			</div>
			<div class="modal-body">
				<p>
					Why are you disabling / uninstalling this module?
				</p>

				<div class="form-group">
				    <div class="col-sm-offset-2 col-sm-10">
				        <div class="radio">
				            <label class="col-lg-12">
				                <input type="radio" name="option"> J'utilise une autre solution de paiement
				            </label>
				            <label class="col-lg-12">
				                <input type="radio" name="option"> Je ne connais pas cette solution
				            </label>
				            <label class="col-lg-12">
				                <input type="radio" name="option"> Je n'ai pas compris son fonctionnement
				            </label>
				            <label class="col-lg-12">
				                <input type="radio" name="option"> Cela ne correspond pas à mon besoin
				            </label>
						</div>
					</div>
				</div>

				<div class="form-group">
				    <div class="col-sm-offset-2 col-sm-10">
				        <div class="radio">
				            <label class="col-lg-12">
				                <input type="radio" name="option"> Autre
				            </label>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="inputPassword3" class="col-sm-2 control-label">Comment</label>
					<div class="col-sm-10">
						<textarea></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-success">Submit</button>
			</div>
		</div>
	</form>
</div>
