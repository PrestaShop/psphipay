<?php
/**
* 2007-2014 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2014 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

require_once(dirname(__FILE__).'/HipayWS.php');
require_once(dirname(__FILE__).'/HipayLocale.php');

class HipayPayment extends HipayWS
{
	protected $categories_domain = 'https://payment.hipay.com/';
	protected $categories_test_domain = 'https://test-payment.hipay.com/';
	protected $categories_url = 'order/list-categories/id/';

	protected $client_url = '/soap/payment-v2';

	/* SOAP method: codes */
	public function generate(&$results)
	{
		if (Configuration::get('PSP_HIPAY_USER_EMAIL') == false)
			die(Tools::displayError('An error occurred while redirecting to the payment processor'));

		$currency_id = $this->context->cart->id_currency;
		$currency = new Currency($currency_id);
		$user = new HipayUserAccount($this->module);

		$wesbite_account_id = $user->getWebsiteAccountIdByIsoCode($currency->iso_code);
		$website_id = $user->getWebsiteIdByIsoCode($currency->iso_code);
		$wesbite_email = Configuration::get('PSP_HIPAY_USER_EMAIL');

		if ($website_id == false)
			die(Tools::displayError('An error occurred while redirecting to the payment processor'));

		$locale = new HipayLocale($this->module);
		$free_data = $this->getFreeData();

		$cart_id = $this->context->cart->id;
		$secure_key = $this->context->customer->secure_key;
		$accept_url = $this->context->link->getModuleLink('psphipay', 'confirmation', array('cart_id' => $cart_id, 'secure_key' => $secure_key), true);
		$callback_url = $this->context->link->getModuleLink('psphipay', 'validation', array(), true);
		$cancel_url = $this->context->link->getPageLink('order', null, null, array('step' => '3'), true);
		$decline_url = $this->context->link->getModuleLink('psphipay', 'confirmation', array('cart_id' => $cart_id, 'secure_key' => $secure_key), true);
		$logo_url = $this->context->link->getMediaLink(_PS_IMG_.Configuration::get('PS_LOGO'));

		$params = array(
			'websiteId' => (int)$website_id,
			'amount' => $this->context->cart->getOrderTotal(),
			'categoryId' => $this->getCategory(),
			'currency' => $this->context->currency->iso_code,
			'customerEmail' => $this->context->customer->email,
			'customerIpAddress' => Tools::getRemoteAddr(),
			'description' => Configuration::get('PS_SHOP_NAME'),
			'emailCallback' => Configuration::get('PSP_HIPAY_USER_EMAIL'),
			'executionDate' => date('Y-m-d\TH:i:s'),
			'locale' => $locale->getLocale(),
			'manualCapture' => (int)false,
			'rating' => 'ALL',
			'wsSubAccountId' => $wesbite_account_id,
			'wsSubAccountLogin' => $wesbite_email,

			// URLs
			'urlAccept' => $accept_url,
			'urlCallback' => $callback_url,
			'urlCancel' => $cancel_url,
			'urlDecline' => $decline_url,
			'urlLogo' => $logo_url,

			'freeData' => $free_data,
		);

		$results = $this->executeQuery('generate', $params);

		if ($results->generateResult->code === 0)
			return Tools::redirect($results->generateResult->redirectUrl);

		return false;
	}

	protected function getFreeData()
	{
		return array(
			'item' => array(
				array('key' => 'cart_id', 'value' => $this->context->cart->id),
				array('key' => 'customer_id', 'value' => $this->context->customer->id),
				array('key' => 'secure_key', 'value' => $this->context->customer->secure_key),
				array('key' => 'token', 'value' => Tools::encrypt($this->context->cart->id)),
			),
		);
	}

	protected function getCategory()
	{
		$sandbox_mode = (bool)Configuration::get('PSP_HIPAY_SANDBOX_MODE');

		if ($sandbox_mode)
			$website_id = (int)Configuration::get('PSP_HIPAY_SANDBOX_WEBSITE_ID');
		else
			$website_id = (int)Configuration::get('PSP_HIPAY_WEBSITE_ID');

		if ($sandbox_mode === true)
			$url = $this->categories_test_domain.$this->categories_url.$website_id;
		else
			$url = $this->categories_domain.$this->categories_url.$website_id;

		$categories_xml = Tools::file_get_contents($url);
		$categories = Tools::jsonDecode(Tools::jsonEncode((array)simplexml_load_string($categories_xml)), 1);

		if (isset($categories['result']['status']) && ($categories['result']['status'] == 'error'))
			die(Tools::displayError('Error occurred while getting categories list.'));

		if (isset($categories['categoriesList']['category']))
		{
			$categories_keys = array_keys($categories['categoriesList']['category']);
			return array_shift($categories_keys);
		}

		return 0;
	}

}
