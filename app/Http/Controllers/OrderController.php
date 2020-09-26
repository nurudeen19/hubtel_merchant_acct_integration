<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jowusu837\HubtelMerchantAccount\OnlineCheckout\Item;
use HubtelMerchantAccount;

class OrderController extends Controller
{
    
      public function payOnline(Request $request)
    {
    	// Dummy Items to be processed for checkout
        $items = [
            ['product_name' => 'apple 1',
                'quantity' => 1,
                'price' => 5
            ],
            ['product_name' => 'apple 2',
                'quantity' => 2,
                'price' => 7
            ],
            ['product_name' => 'apple 3',
                'quantity' => 1,
                'price' => 10
            ]
        ];

        // Initiate online checkout
        $ocRequest = new \Jowusu837\HubtelMerchantAccount\OnlineCheckout\Request();
        $ocRequest->invoice->description = "Invoice description";
        $ocRequest->invoice->total_amount = 50;
        $ocRequest->store->name = "My Shop";
        $ocRequest->store->logo_url = asset('images/logo.png');
        $ocRequest->store->phone = "026xxxxxx";
        $ocRequest->store->postal_address = "P. O. Box 123456";
        $ocRequest->store->tagline = "Best online shop ever";
        $ocRequest->store->website_url = env('APP_URL');
        $ocRequest->actions->cancel_url = url('/checkout/done');
        $ocRequest->actions->return_url = url('/checkout/done');

        // get item info for online checkout
        foreach ($items as $item) {
            $invoiceItem = new Item();
            $invoiceItem->name = $item['product_name'];
            $invoiceItem->description = "";
            $invoiceItem->quantity = $item['quantity'];
            $invoiceItem->unit_price = $item['price'];
            $invoiceItem->total_price = $item['price'] * $item['quantity'];

            $ocRequest->invoice->addItem($invoiceItem);
        }

        // submit data to inline checkout form
        HubtelMerchantAccount::onlineCheckout($ocRequest);
    }

    // when request is cancel or completed
    public function done(){
        return 'request done';
    }
}
