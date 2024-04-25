<?php
/***********************************************************
ExchangeRatesController.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 5, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/


namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Auth;
use Config;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\Inventory\Products;
use App\models\Inventory\ProductCategories;
use App\library\ProductCategoriesManager;
use App\models\System\Departments;
use App\models\Accounting\ChartAccounts;
use App\models\System\Countries;
use App\models\Accounting\AccountingJournals;
use App\models\Accounting\Journaltypes;
use App\models\Accounting\BankAccounts;
use App\models\System\Currency;
use App\models\Accounting\VatAccounts;
use App\models\System\CurrencyExchangeRates;



class ExchangeRatesController extends Controller
{

   /**
    * Page to manage exchange rates from one currency to other on daily bases
    * with abilty to filter by currency from and to in the dropdown
    * 
    * @author Moe Mantach
    * @access public
    * @return unknown
    */
    public function index()
    { 
        $list_currencies = Currency::all();
        $data = array(
            "list_currencies" => $list_currencies
        );
        return Response()->view('accounting.exchangerates',$data);
    }
    
    
    /**
     * Display list of Journals saved in the system
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {            
        $from_currency = $request->input('from_currency');
        $to_currency = $request->input('to_currency');
        $todays_date = date("Y-m-d");
        $lstexchange_rates   = CurrencyExchangeRates::whereErDateExchange($todays_date);
        
        if($from_currency != null && $from_currency > 0)
        {
            $lstexchange_rates = $lstexchange_rates->whereErFromCurrency($from_currency);
        }
        
        if($to_currency != null && $to_currency> 0)
        {
            $lstexchange_rates = $lstexchange_rates->whereErToCurrency($to_currency);
        }
        
        $lstexchange_rates = $lstexchange_rates->get();
        
        
        $list_currencies = Currency::all();
        $currencies_array = CreateDatabaseArrayByIndex($list_currencies , 'cc_id');
        
        $data = array(
            "lstexchange_rates" => $lstexchange_rates,
            "currencies_array" => $currencies_array,
        );
        
        $result_array = array();
         
        $result_array['display'] = view("accounting.listrates",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Open form of add new Exchange Rate
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\Http\Response
     */
    public function AddForm()
    {
        
        $lst_currencies     = Currency::orderby('cc_currency_code','ASC')->get(); 
        
        
        $data = array(
            'lst_currencies' => $lst_currencies
        );
        
        return Response()->view('accounting.addexchangerate',$data);
    }
    
   /**
    * Open Page  of Edit Form Exchange Rate
    * 
    * @author Moe Mantach
    * @access public
    * @param unknown $er_id
    * @return unknown
    */
    public function EditForm( $er_id )
    {
        $exchange_rate      = CurrencyExchangeRates::find( $er_id);
        $lst_currencies     = Currency::orderby('cc_currency_code','ASC')->get(); 
        
        $data = array(
            'exchange_rate'     => $exchange_rate, 
            "lst_currencies"    => $lst_currencies
        );
        
        return Response()->view('accounting.editexchangerate',$data);
        
    }
    
    
    /**
     * function to save data of Exchange Rate to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveExchangeRateInfo(Request $request)
    {
        $er_id                      = $request->input('er_id');
        $er_from_currency           = $request->input('er_from_currency');
        $er_to_currency             = $request->input('er_to_currency');
        $er_exchange_rate           = $request->input('er_exchange_rate');
        $er_date_exchange           = date("Y-m-d"); 
        
        $ExchangeRate = new CurrencyExchangeRates();
        
        if($er_id > 0)
        {
            $ExchangeRate = CurrencyExchangeRates::find( $er_id );
        }
        else 
        {
            $ExchangeRate->er_date_exchange = $er_date_exchange; 
        }
        
        $ExchangeRate->er_from_currency = $er_from_currency;
        $ExchangeRate->er_to_currency   = $er_to_currency;
        $ExchangeRate->er_exchange_rate = $er_exchange_rate;
        $ExchangeRate->save();
        
 
        
        $result_array = array();
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        return Response()->json($result_array);
        
    }
    
    
    /**
     * Delete selected Exchange Rate save it in the database
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteExchangeRateInfo(Request $request)
    {
        $er_id  = $request->input('er_id');
        $result_array = array();
        
        
        
        $ExchangeRate = CurrencyExchangeRates::find( $er_id );
        $ExchangeRate->delete();
        
        
        
        
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }
    
    
    /**
     * get exchange rate saved in the database for the current date else
     * we will take default currency exchange rate
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GetExchangeRate(Request $request)
    {
        $exchange_rate_from = $request->input('exchange_rate_from'); 
        $exchange_rate_to  = $request->input('exchange_rate_to');
        $result_array = array();
        $exchange_rate = CurrencyExchangeRates::whereErFromCurrency($exchange_rate_from)->whereErToCurrency($exchange_rate_to)->whereRaw('Date(er_date_exchange) = CURDATE()')->get();
        
        
        $result_array['is_error'] = 0;
        
        // countif the today's record exist
        if(count($exchange_rate) == 0)
        {
            //GetCurrencyRate
            $exchange_rate = 0;
            $from_currency  = Currency::find($exchange_rate_from);
            $to_currency    = Currency::find($exchange_rate_to);
            $apikey = '90b8f7b6384738232778';
            
            $from_Currency  = urlencode($from_currency->cc_currency_code);
            $to_Currency    = urlencode($to_currency->cc_currency_code);
            $query          =  "{$from_Currency}_{$to_Currency}";

            $json = file_get_contents("https://free.currconv.com/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}");
            $obj = json_decode($json, true);
            
            $exchange_rate = floatval($obj["$query"]);
            $result_array['exchange_rate'] = $exchange_rate;
        }
        else 
        {
            $result_array['exchange_rate'] = $exchange_rate[0]->er_exchange_rate;
        }
        
        

        
        
        return Response()->json($result_array);
        
    }
    
 
}