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
		
		

    	//Instantiate a new direct payment request, using Credit Card
		$creditCard = new \PagSeguro\Domains\Requests\DirectPayment\CreditCard();

		
		$creditCard->setReceiverEmail(env('PAGSEGURO_EMAIL'));

		// Set a reference code for this payment request. It is useful to identify this payment
		// in future notifications.
		$creditCard->setReference($this->reference);

		// Set the currency
		$creditCard->setCurrency("BRL");

		// Add an item for this payment request
		$amount = number_format($this->cardInfo['valorTotal'], 2, '.', '');
		$creditCard->addItems()->withParameters(
		    $this->reference,
		    $this->item,
		    1, 
		    $amount
		);

		// Set your customer information.
		// If you using SANDBOX you must use an email @sandbox.pagseguro.com.br
		$user = $this->user;
		$email = env('PAGSEGURO_ENV') == 'sandbox' ? 'test@sandbox.pagseguro.com.br' : $user->email;

		$creditCard->setSender()->setName($this->cardInfo['card_name']);
		$creditCard->setSender()->setEmail($email);
		#$user->celular
		$creditCard->setSender()->setPhone()->withParameters(
		    87,
		    981216574
		);
		#$user->cpf
		$creditCard->setSender()->setDocument()->withParameters(
		    'CPF',
		    '70516476440'
		);

		$creditCard->setSender()->setHash($this->cardInfo['hash']);

		$creditCard->setSender()->setIp('127.0.0.0');

		// Set shipping information for this payment request
		$creditCard->setShipping()->setAddress()->withParameters(
		    'Av. Brig. Faria Lima',
		    '1384',
		    'Jardim Paulistano',
		    '01452002',
		    'São Paulo',
		    'SP',
		    'BRA',
		    'apto. 114'
		);

		//Set billing information for credit card
		$creditCard->setBilling()->setAddress()->withParameters(
		    'Av. Brig. Faria Lima',
		    '1384',
		    'Jardim Paulistano',
		    '01452002',
		    'São Paulo',
		    'SP',
		    'BRA',
		    'apto. 114'
		);

		// Set credit card token
		$creditCard->setToken($this->cardInfo['card_token']);

		// Set the installment quantity and value (could be obtained using the Installments
		// service, that have an example here in \public\getInstallments.php)
		list($quantity, $installmentAmount) = explode('|', $this->cardInfo['installment']);
		$installmentAmount = number_format($installmentAmount, 2, '.', '');
		// dd($installmentAmount);
		$creditCard->setInstallment()->withParameters($quantity, $installmentAmount);

		// Set the credit card holder information
		$creditCard->setHolder()->setBirthdate('01/10/1979');
		$creditCard->setHolder()->setName($this->cardInfo['card_name']); // Equals in Credit Card

		$creditCard->setHolder()->setPhone()->withParameters(
		    87,
		    981216574
		);

		$creditCard->setHolder()->setDocument()->withParameters(
		    'CPF',
		    '70516476440'
		);

		// Set the Payment Mode for this payment request
		$creditCard->setMode('DEFAULT');

		// Set a reference code for this payment request. It is useful to identify this payment
		// in future notifications.

		$result = $creditCard->register(
	        \PagSeguro\Configuration\Configure::getAccountCredentials()
	    );

	    return $result;
	}

}