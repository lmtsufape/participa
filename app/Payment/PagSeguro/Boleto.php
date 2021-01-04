<?php

namespace App\Payment\PagSeguro;

use App\Models\Users\User;
use App\Models\Submissao\Evento;
use App\Models\Users\ComissaoEvento;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\HandlesAuthorization;

class CreditCard
{
	private $user;
	private $item;
	private $cardInfo;
	private $reference;

	public function __construct($user, $item, $cardInfo, $reference)
	{
		$this->user 	= $user;
		$this->item 	= $item;
		$this->cardInfo = $cardInfo;
		$this->reference = $reference;

	}

	public function doPayment()
	{
		
		//Instantiate a new Boleto Object
	$boleto = new \PagSeguro\Domains\Requests\DirectPayment\Boleto();

	// Set the Payment Mode for this payment request
	$boleto->setMode('DEFAULT');

	/**
	 * @todo Change the receiver Email
	 */
	//$boleto->setReceiverEmail('vendedor@lojamodelo.com.br'); 

	// Set the currency
	$boleto->setCurrency("BRL");

	// Add an item for this payment request
	$boleto->addItems()->withParameters(
	    '0001',
	    'Notebook prata',
	    2,
	    130.00
	);

	// Add an item for this payment request
	$boleto->addItems()->withParameters(
	    '0002',
	    'Notebook preto',
	    2,
	    430.00
	);

	// Set a reference code for this payment request. It is useful to identify this payment
	// in future notifications.
	$boleto->setReference("LIBPHP000001-boleto");

	//set extra amount
	$boleto->setExtraAmount(11.5);

	// Set your customer information.
	// If you using SANDBOX you must use an email @sandbox.pagseguro.com.br
	$boleto->setSender()->setName('João Comprador');
	$boleto->setSender()->setEmail('email@comprador.com.br');

	$boleto->setSender()->setPhone()->withParameters(
	    11,
	    56273440
	);

	$boleto->setSender()->setDocument()->withParameters(
	    'CPF',
	    'insira um numero de CPF valido'
	);

	$boleto->setSender()->setHash('3dc25e8a7cb3fd3104e77ae5ad0e7df04621caa33e300b27aeeb9ea1adf1a24f');

	$boleto->setSender()->setIp('127.0.0.0');

	// Set shipping information for this payment request
	$boleto->setShipping()->setAddress()->withParameters(
	    'Av. Brig. Faria Lima',
	    '1384',
	    'Jardim Paulistano',
	    '01452002',
	    'São Paulo',
	    'SP',
	    'BRA',
	    'apto. 114'
	);

	// If your payment request don't need shipping information use:
	// $boleto->setShipping()->setAddressRequired()->withParameters('FALSE');


    //Get the crendentials and register the boleto payment
    $result = $boleto->register(
        \PagSeguro\Configuration\Configure::getAccountCredentials()
    );

	    return $result;
	}

}