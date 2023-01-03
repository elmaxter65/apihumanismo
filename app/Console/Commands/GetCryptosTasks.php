<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Http;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Illuminate\Http\Client\ConnectionException;
use Validator;
use Exception;
use DB;
use Auth;
use App\Models\ApiCrypto;
use App\Models\UserCrypto;
use \App\Models\ApiSparklineCrypto;
use Mail;
use Carbon\Carbon;

class GetCryptosTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getcryptos:tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tarea programada para obtener las criptomonedas desde CoinGecko cada 10 minutos.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            set_time_limit(0);

            $msg = '';

            $client = new CoinGeckoClient();
            $cryptocurrencies = $client->coins()->getMarkets('usd',['order' => 'market_cap_desc', 'per_page' => '250', 'page' => '1', 'sparkline' => 'true', 'price_change_percentage' => '7d']);

            $apicryptos = ApiCrypto::count();

            if ( $apicryptos > 0 ) {

                foreach ($cryptocurrencies as $cryptocurrency) {
                    $apicrypto = ApiCrypto::where('cg_id', '=', $cryptocurrency['id'])->first();
                    if($apicrypto != null) {
                        $apicrypto->order = $cryptocurrency['market_cap_rank'];
                        if( isset($cryptocurrency['id']) && $cryptocurrency['id'] != null ) $apicrypto->slug = $cryptocurrency['id'];
                        if( isset($cryptocurrency['id']) && $cryptocurrency['id'] != null ) $apicrypto->cg_id = $cryptocurrency['id'];
                        if( isset($cryptocurrency['symbol']) && $cryptocurrency['symbol'] != null ) $apicrypto->cg_symbol = $cryptocurrency['symbol'];
                        if( isset($cryptocurrency['name']) && $cryptocurrency['name'] != null ) $apicrypto->cg_name = $cryptocurrency['name'];
                        if( isset($cryptocurrency['image']) && $cryptocurrency['image'] != null ) $apicrypto->cg_image = $cryptocurrency['image'];
                        if( isset($cryptocurrency['current_price']) && $cryptocurrency['current_price'] != null ) $apicrypto->cg_current_price = $cryptocurrency['current_price'];
                        if( isset($cryptocurrency['market_cap']) && $cryptocurrency['market_cap'] != null ) $apicrypto->cg_market_cap = $cryptocurrency['market_cap'];
                        if( isset($cryptocurrency['market_cap_rank']) && $cryptocurrency['market_cap_rank'] != null ) $apicrypto->cg_market_cap_rank = $cryptocurrency['market_cap_rank'];
                        if( isset($cryptocurrency['fully_diluted_valuation']) && $cryptocurrency['fully_diluted_valuation'] != null ) $apicrypto->cg_fully_diluted_valuation = $cryptocurrency['fully_diluted_valuation'];
                        if( isset($cryptocurrency['total_volume']) && $cryptocurrency['total_volume'] != null ) $apicrypto->cg_total_volume = $cryptocurrency['total_volume'];
                        if( isset($cryptocurrency['high_24h']) && $cryptocurrency['high_24h'] != null ) $apicrypto->cg_high_24h = $cryptocurrency['high_24h'];
                        if( isset($cryptocurrency['low_24h']) && $cryptocurrency['low_24h'] != null ) $apicrypto->cg_low_24h = $cryptocurrency['low_24h'];
                        if( isset($cryptocurrency['price_change_24h']) && $cryptocurrency['price_change_24h'] != null ) $apicrypto->cg_price_change_24h = $cryptocurrency['price_change_24h'];
                        if( isset($cryptocurrency['price_change_percentage_24h']) && $cryptocurrency['price_change_percentage_24h'] != null ) $apicrypto->cg_price_change_percentage_24h = $cryptocurrency['price_change_percentage_24h'];
                        if( isset($cryptocurrency['market_cap_change_24h']) && $cryptocurrency['market_cap_change_24h'] != null ) $apicrypto->cg_market_cap_change_24h = $cryptocurrency['market_cap_change_24h'];
                        if( isset($cryptocurrency['market_cap_change_percentage_24h']) && $cryptocurrency['market_cap_change_percentage_24h'] != null ) $apicrypto->cg_market_cap_change_percentage_24h = $cryptocurrency['market_cap_change_percentage_24h'];
                        if( isset($cryptocurrency['circulating_supply']) && $cryptocurrency['circulating_supply'] != null ) $apicrypto->cg_circulating_supply = $cryptocurrency['circulating_supply'];
                        if( isset($cryptocurrency['total_supply']) && $cryptocurrency['total_supply'] != null ) $apicrypto->cg_total_supply = $cryptocurrency['total_supply'];
                        if( isset($cryptocurrency['max_supply']) && $cryptocurrency['max_supply'] != null ) $apicrypto->cg_max_supply = $cryptocurrency['max_supply'];
                        if( isset($cryptocurrency['ath']) && $cryptocurrency['ath'] != null ) $apicrypto->cg_ath = $cryptocurrency['ath'];
                        if( isset($cryptocurrency['ath_change_percentage']) && $cryptocurrency['ath_change_percentage'] != null ) $apicrypto->cg_ath_change_percentage = $cryptocurrency['ath_change_percentage'];
                        if( isset($cryptocurrency['ath_date']) && $cryptocurrency['ath_date'] != null ) $apicrypto->cg_ath_date = $cryptocurrency['ath_date'];
                        if( isset($cryptocurrency['atl']) && $cryptocurrency['atl'] != null ) $apicrypto->cg_atl = $cryptocurrency['atl'];
                        if( isset($cryptocurrency['atl_change_percentage']) && $cryptocurrency['atl_change_percentage'] != null ) $apicrypto->cg_atl_change_percentage = $cryptocurrency['atl_change_percentage'];
                        if( isset($cryptocurrency['atl_date']) && $cryptocurrency['atl_date'] != null ) $apicrypto->cg_atl_date = $cryptocurrency['atl_date'];
                        if( isset($cryptocurrency['price_change_percentage_7d_in_currency']) && $cryptocurrency['price_change_percentage_7d_in_currency'] != null ) $apicrypto->cg_price_change_percentage_7d_in_currency = $cryptocurrency['price_change_percentage_7d_in_currency'];
                        if( isset($cryptocurrency['last_updated']) && $cryptocurrency['last_updated'] != null ) $apicrypto->cg_last_updated = $cryptocurrency['last_updated'];
                        $svApiCrypto = $apicrypto->save();

                        if ($svApiCrypto) {

                            $prices = [];

                            if (isset($cryptocurrency['sparkline_in_7d']['price']) && count($cryptocurrency['sparkline_in_7d']['price']) > 0) {

                                $prices = $cryptocurrency['sparkline_in_7d']['price'];

                                ApiSparklineCrypto::where('api_crypto_id', '=', $apicrypto->id)->forceDelete();

                                foreach ($prices as $index => $value) {

                                    ApiSparklineCrypto::create([
                                        'cg_price' => $value,
                                        'api_crypto_id' => $apicrypto->id
                                    ]);
                                }


                            }
                        }


                    }


                }


                $msg = 'Registros actualizados';

            } else {
                foreach ($cryptocurrencies as $cryptocurrency) {
                    $apicrypto = ApiCrypto::create([
                        'order' => $cryptocurrency['market_cap_rank'],
                        'slug' => $cryptocurrency['id'],
                        'cg_id' => $cryptocurrency['id'],
                        'cg_symbol' => $cryptocurrency['symbol'],
                        'cg_name' => $cryptocurrency['name'],
                        'cg_image' => $cryptocurrency['image'],
                        'cg_current_price' => $cryptocurrency['current_price'],
                        'cg_market_cap' => $cryptocurrency['market_cap'],
                        'cg_market_cap_rank' => $cryptocurrency['market_cap_rank'],
                        'cg_fully_diluted_valuation' => $cryptocurrency['fully_diluted_valuation'],
                        'cg_total_volume' => $cryptocurrency['total_volume'],
                        'cg_high_24h' => $cryptocurrency['high_24h'],
                        'cg_low_24h' => $cryptocurrency['low_24h'],
                        'cg_price_change_24h' => $cryptocurrency['price_change_24h'],
                        'cg_price_change_percentage_24h' => $cryptocurrency['price_change_percentage_24h'],
                        'cg_market_cap_change_24h' => $cryptocurrency['market_cap_change_24h'],
                        'cg_market_cap_change_percentage_24h' => $cryptocurrency['market_cap_change_percentage_24h'],
                        'cg_circulating_supply' => $cryptocurrency['circulating_supply'],
                        'cg_total_supply' => $cryptocurrency['total_supply'],
                        'cg_max_supply' => $cryptocurrency['max_supply'],
                        'cg_ath' => $cryptocurrency['ath'],
                        'cg_ath_change_percentage' => $cryptocurrency['ath_change_percentage'],
                        'cg_ath_date' => $cryptocurrency['ath_date'],
                        'cg_atl' => $cryptocurrency['atl'],
                        'cg_atl_change_percentage' => $cryptocurrency['atl_change_percentage'],
                        'cg_atl_date' => $cryptocurrency['atl_date'],
                        'cg_price_change_percentage_7d_in_currency' => $cryptocurrency['price_change_percentage_7d_in_currency'],
                        'cg_last_updated' => $cryptocurrency['last_updated']
                    ]);

                    $prices = [];

                    if (isset($cryptocurrency['sparkline_in_7d']['price']) && count($cryptocurrency['sparkline_in_7d']['price']) > 0) {

                        $prices = $cryptocurrency['sparkline_in_7d']['price'];

                        foreach ($prices as $index => $value) {

                            ApiSparklineCrypto::create([
                                'cg_price' => $value,
                                'api_crypto_id' => $apicrypto->id
                            ]);
                        }
                    }

                }

                $msg = 'Registros creados';
            }


            DB::commit();
            return response()->json(["message" => $msg], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(["message" => "Error en Base de Datos: ".$e->getMessage()], 500);
        }
    }
}
