<?php

namespace App\Payment\PagSeguro;

use App\Models\Users\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\HandlesAuthorization;

class NOtification
{
	

	

	public function getTransaction()
	{
		
		if (!\PagSeguro\Helpers\Xhr::hasPost()) throw new \InvalidArgumentException($_POST); 
        
        $response = \PagSeguro\Services\Transactions\Notification::check(
            \PagSeguro\Configuration\Configure::getAccountCredentials()
        );

        return $response;
	    
		
	}

}