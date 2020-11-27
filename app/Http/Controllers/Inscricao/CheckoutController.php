<?php

namespace App\Http\Controllers\Inscricao;

use App\Payment\PagSeguro\CreditCard;
use App\Payment\PagSeguro\Notification;
use Illuminate\Http\Request;
// use Ramsey\Uuid\Uuid;

class CheckoutController extends Controller
{
	public function index()
    {
    	try {
		    if(!auth()->check()) {
			    return redirect()->route('login');
		    }

		    if(!session()->has('inscricao')) return redirect()->route('home');

		    $this->makePagSeguroSession();

		    $items = array_map(function($line){
			    return $line['amount'] * $line['price'];
		    }, session()->get('inscricao'));

		    $cartItems = array_sum($cartItems);

		    return view('checkout', compact('items'));

	    } catch (\Exception $e) {
			session()->forget('pagseguro_session_code');
			redirect()->route('checkout.index');
	    }
    }


    private function makePagSeguroSession()
    {
		if(!session()->has('pagseguro_session_code')) {

			$sessionCode = \PagSeguro\Services\Session::create(
				\PagSeguro\Configuration\Configure::getAccountCredentials()
			);

			return session()->put('pagseguro_session_code', $sessionCode->getResult());
		}
    }
}