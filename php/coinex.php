<?php

namespace ccxt;

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code

use Exception as Exception; // a common import

class coinex extends Exchange {

    public function describe () {
        return array_replace_recursive (parent::describe (), array (
            'id' => 'coinex',
            'name' => 'CoinEx',
            'version' => 'v1',
            'countries' => array ( 'CN' ),
            'rateLimit' => 1000,
            'has' => array (
                'fetchTickers' => true,
                'fetchOHLCV' => true,
                'fetchOrder' => true,
                'fetchOpenOrders' => true,
                'fetchClosedOrders' => true,
                'fetchMyTrades' => true,
                'withdraw' => true,
                'fetchDeposits' => true,
                'fetchWithdrawals' => true,
            ),
            'timeframes' => array (
                '1m' => '1min',
                '3m' => '3min',
                '5m' => '5min',
                '15m' => '15min',
                '30m' => '30min',
                '1h' => '1hour',
                '2h' => '2hour',
                '4h' => '4hour',
                '6h' => '6hour',
                '12h' => '12hour',
                '1d' => '1day',
                '3d' => '3day',
                '1w' => '1week',
            ),
            'urls' => array (
                'logo' => 'https://user-images.githubusercontent.com/1294454/38046312-0b450aac-32c8-11e8-99ab-bc6b136b6cc7.jpg',
                'api' => 'https://api.coinex.com',
                'www' => 'https://www.coinex.com',
                'doc' => 'https://github.com/coinexcom/coinex_exchange_api/wiki',
                'fees' => 'https://www.coinex.com/fees',
                'referral' => 'https://www.coinex.com/register?refer_code=yw5fz',
            ),
            'api' => array (
                'public' => array (
                    'get' => array (
                        'common/currency/rate',
                        'common/asset/config',
                        'market/info',
                        'market/list',
                        'market/ticker',
                        'market/ticker/all',
                        'market/depth',
                        'market/deals',
                        'market/kline',
                    ),
                ),
                'private' => array (
                    'get' => array (
                        'balance/coin/deposit',
                        'balance/coin/withdraw',
                        'balance/info',
                        'future/account',
                        'future/config',
                        'future/limitprice',
                        'future/loan/history',
                        'future/market',
                        'margin/account',
                        'margin/config',
                        'margin/loan/history',
                        'margin/market',
                        'order',
                        'order/deals',
                        'order/finished',
                        'order/finished/{id}',
                        'order/pending',
                        'order/status',
                        'order/status/batch',
                        'order/user/deals',
                    ),
                    'post' => array (
                        'balance/coin/withdraw',
                        'future/flat',
                        'future/loan',
                        'future/transfer',
                        'margin/flat',
                        'margin/loan',
                        'margin/transfer',
                        'order/batchlimit',
                        'order/ioc',
                        'order/limit',
                        'order/market',
                        'sub_account/transfer',
                    ),
                    'delete' => array (
                        'balance/coin/withdraw',
                        'order/pending/batch',
                        'order/pending',
                    ),
                ),
            ),
            'fees' => array (
                'trading' => array (
                    'maker' => 0.001,
                    'taker' => 0.001,
                ),
                'funding' => array (
                    'withdraw' => array (
                        'BCH' => 0.0,
                        'BTC' => 0.001,
                        'LTC' => 0.001,
                        'ETH' => 0.001,
                        'ZEC' => 0.0001,
                        'DASH' => 0.0001,
                    ),
                ),
            ),
            'limits' => array (
                'amount' => array (
                    'min' => 0.001,
                    'max' => null,
                ),
            ),
            'precision' => array (
                'amount' => 8,
                'price' => 8,
            ),
            'options' => array (
                'createMarketBuyOrderRequiresPrice' => true,
            ),
        ));
    }

    public function fetch_markets ($params = array ()) {
        $response = $this->publicGetMarketInfo ($params);
        //
        //     {
        //         "code" => 0,
        //         "data" => {
        //             "WAVESBTC" => {
        //                 "name" => "WAVESBTC",
        //                 "min_amount" => "1",
        //                 "maker_fee_rate" => "0.001",
        //                 "taker_fee_rate" => "0.001",
        //                 "pricing_name" => "BTC",
        //                 "pricing_decimal" => 8,
        //                 "trading_name" => "WAVES",
        //                 "trading_decimal" => 8
        //             }
        //         }
        //     }
        //
        $markets = $this->safe_value($response, 'data', array());
        $result = array();
        $keys = is_array($markets) ? array_keys($markets) : array();
        for ($i = 0; $i < count ($keys); $i++) {
            $key = $keys[$i];
            $market = $markets[$key];
            $id = $this->safe_string($market, 'name');
            $baseId = $this->safe_string($market, 'trading_name');
            $quoteId = $this->safe_string($market, 'pricing_name');
            $base = $this->safe_currency_code($baseId);
            $quote = $this->safe_currency_code($quoteId);
            $symbol = $base . '/' . $quote;
            $precision = array (
                'amount' => $this->safe_integer($market, 'trading_decimal'),
                'price' => $this->safe_integer($market, 'pricing_decimal'),
            );
            $active = null;
            $result[] = array (
                'id' => $id,
                'symbol' => $symbol,
                'base' => $base,
                'quote' => $quote,
                'baseId' => $baseId,
                'quoteId' => $quoteId,
                'active' => $active,
                'taker' => $this->safe_float($market, 'taker_fee_rate'),
                'maker' => $this->safe_float($market, 'maker_fee_rate'),
                'info' => $market,
                'precision' => $precision,
                'limits' => array (
                    'amount' => array (
                        'min' => $this->safe_float($market, 'min_amount'),
                        'max' => null,
                    ),
                    'price' => array (
                        'min' => pow(10, -$precision['price']),
                        'max' => null,
                    ),
                ),
            );
        }
        return $result;
    }

    public function parse_ticker ($ticker, $market = null) {
        $timestamp = $this->safe_integer($ticker, 'date');
        $symbol = null;
        if ($market !== null) {
            $symbol = $market['symbol'];
        }
        $ticker = $this->safe_value($ticker, 'ticker', array());
        $last = $this->safe_float($ticker, 'last');
        return array (
            'symbol' => $symbol,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601 ($timestamp),
            'high' => $this->safe_float($ticker, 'high'),
            'low' => $this->safe_float($ticker, 'low'),
            'bid' => $this->safe_float($ticker, 'buy'),
            'bidVolume' => null,
            'ask' => $this->safe_float($ticker, 'sell'),
            'askVolume' => null,
            'vwap' => null,
            'open' => null,
            'close' => $last,
            'last' => $last,
            'previousClose' => null,
            'change' => null,
            'percentage' => null,
            'average' => null,
            'baseVolume' => $this->safe_float_2($ticker, 'vol', 'volume'),
            'quoteVolume' => null,
            'info' => $ticker,
        );
    }

    public function fetch_ticker ($symbol, $params = array ()) {
        $this->load_markets();
        $market = $this->market ($symbol);
        $request = array (
            'market' => $market['id'],
        );
        $response = $this->publicGetMarketTicker (array_merge ($request, $params));
        return $this->parse_ticker($response['data'], $market);
    }

    public function fetch_tickers ($symbols = null, $params = array ()) {
        $this->load_markets();
        $response = $this->publicGetMarketTickerAll ($params);
        $data = $this->safe_value($response, 'data');
        $timestamp = $this->safe_integer($data, 'date');
        $tickers = $this->safe_value($data, 'ticker');
        $marketIds = is_array($tickers) ? array_keys($tickers) : array();
        $result = array();
        for ($i = 0; $i < count ($marketIds); $i++) {
            $marketId = $marketIds[$i];
            $symbol = $marketId;
            $market = null;
            if (is_array($this->markets_by_id) && array_key_exists($marketId, $this->markets_by_id)) {
                $market = $this->markets_by_id[$marketId];
                $symbol = $market['symbol'];
            }
            $ticker = array (
                'date' => $timestamp,
                'ticker' => $tickers[$marketId],
            );
            $result[$symbol] = $this->parse_ticker($ticker, $market);
        }
        return $result;
    }

    public function fetch_order_book ($symbol, $limit = 20, $params = array ()) {
        $this->load_markets();
        if ($limit === null) {
            $limit = 20; // default
        }
        $request = array (
            'market' => $this->market_id($symbol),
            'merge' => '0.0000000001',
            'limit' => (string) $limit,
        );
        $response = $this->publicGetMarketDepth (array_merge ($request, $params));
        return $this->parse_order_book($response['data']);
    }

    public function parse_trade ($trade, $market = null) {
        // this method parses both public and private trades
        $timestamp = $this->safe_integer($trade, 'create_time');
        if ($timestamp === null) {
            $timestamp = $this->safe_integer($trade, 'date_ms');
        } else {
            $timestamp = $timestamp * 1000;
        }
        $tradeId = $this->safe_string($trade, 'id');
        $orderId = $this->safe_string($trade, 'order_id');
        $price = $this->safe_float($trade, 'price');
        $amount = $this->safe_float($trade, 'amount');
        $marketId = $this->safe_string($trade, 'market');
        $market = $this->safe_value($this->markets_by_id, $marketId, $market);
        $symbol = null;
        if ($market !== null) {
            $symbol = $market['symbol'];
        }
        $cost = $this->safe_float($trade, 'deal_money');
        if (!$cost) {
            $cost = floatval ($this->cost_to_precision($symbol, $price * $amount));
        }
        $fee = null;
        $feeCost = $this->safe_float($trade, 'fee');
        if ($feeCost !== null) {
            $feeCurrencyId = $this->safe_string($trade, 'fee_asset');
            $feeCurrencyCode = $this->safe_currency_code($feeCurrencyId);
            $fee = array (
                'cost' => $feeCost,
                'currency' => $feeCurrencyCode,
            );
        }
        $takerOrMaker = $this->safe_string($trade, 'role');
        $side = $this->safe_string($trade, 'type');
        return array (
            'info' => $trade,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601 ($timestamp),
            'symbol' => $symbol,
            'id' => $tradeId,
            'order' => $orderId,
            'type' => null,
            'side' => $side,
            'takerOrMaker' => $takerOrMaker,
            'price' => $price,
            'amount' => $amount,
            'cost' => $cost,
            'fee' => $fee,
        );
    }

    public function fetch_trades ($symbol, $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        $market = $this->market ($symbol);
        $request = array (
            'market' => $market['id'],
        );
        $response = $this->publicGetMarketDeals (array_merge ($request, $params));
        return $this->parse_trades($response['data'], $market, $since, $limit);
    }

    public function parse_ohlcv ($ohlcv, $market = null, $timeframe = '5m', $since = null, $limit = null) {
        return [
            $ohlcv[0] * 1000,
            floatval ($ohlcv[1]),
            floatval ($ohlcv[3]),
            floatval ($ohlcv[4]),
            floatval ($ohlcv[2]),
            floatval ($ohlcv[5]),
        ];
    }

    public function fetch_ohlcv ($symbol, $timeframe = '5m', $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        $market = $this->market ($symbol);
        $request = array (
            'market' => $market['id'],
            'type' => $this->timeframes[$timeframe],
        );
        $response = $this->publicGetMarketKline (array_merge ($request, $params));
        return $this->parse_ohlcvs($response['data'], $market, $timeframe, $since, $limit);
    }

    public function fetch_balance ($params = array ()) {
        $this->load_markets();
        $response = $this->privateGetBalanceInfo ($params);
        //
        //     {
        //       "$code" => 0,
        //       "data" => {
        //         "BCH" => array (                     # BCH $account
        //           "available" => "13.60109",   # Available BCH
        //           "frozen" => "0.00000"        # Frozen BCH
        //         ),
        //         "BTC" => array (                     # BTC $account
        //           "available" => "32590.16",   # Available BTC
        //           "frozen" => "7000.00"        # Frozen BTC
        //         ),
        //         "ETH" => array (                     # ETH $account
        //           "available" => "5.06000",    # Available ETH
        //           "frozen" => "0.00000"        # Frozen ETH
        //         }
        //       ),
        //       "message" => "Ok"
        //     }
        //
        $result = array( 'info' => $response );
        $balances = $this->safe_value($response, 'data');
        $currencyIds = is_array($balances) ? array_keys($balances) : array();
        for ($i = 0; $i < count ($currencyIds); $i++) {
            $currencyId = $currencyIds[$i];
            $code = $this->safe_currency_code($currencyId);
            $balance = $this->safe_value($balances, $currencyId, array());
            $account = $this->account ();
            $account['free'] = $this->safe_float($balance, 'available');
            $account['used'] = $this->safe_float($balance, 'frozen');
            $result[$code] = $account;
        }
        return $this->parse_balance($result);
    }

    public function parse_order_status ($status) {
        $statuses = array (
            'not_deal' => 'open',
            'part_deal' => 'open',
            'done' => 'closed',
            'cancel' => 'canceled',
        );
        return $this->safe_float($statuses, $status, $status);
    }

    public function parse_order ($order, $market = null) {
        //
        // fetchOrder
        //
        //     {
        //         "$amount" => "0.1",
        //         "asset_fee" => "0.22736197736197736197",
        //         "avg_price" => "196.85000000000000000000",
        //         "create_time" => 1537270135,
        //         "deal_amount" => "0.1",
        //         "deal_fee" => "0",
        //         "deal_money" => "19.685",
        //         "fee_asset" => "CET",
        //         "fee_discount" => "0.5",
        //         "id" => 1788259447,
        //         "left" => "0",
        //         "maker_fee_rate" => "0",
        //         "$market" => "ETHUSDT",
        //         "order_type" => "limit",
        //         "$price" => "170.00000000",
        //         "$status" => "done",
        //         "taker_fee_rate" => "0.0005",
        //         "$type" => "sell",
        //     }
        //
        $timestamp = $this->safe_integer($order, 'create_time');
        if ($timestamp !== null) {
            $timestamp *= 1000;
        }
        $price = $this->safe_float($order, 'price');
        $cost = $this->safe_float($order, 'deal_money');
        $amount = $this->safe_float($order, 'amount');
        $filled = $this->safe_float($order, 'deal_amount');
        $average = $this->safe_float($order, 'avg_price');
        $symbol = null;
        $marketId = $this->safe_string($order, 'market');
        $market = $this->safe_value($this->markets_by_id, $marketId);
        $feeCurrencyId = $this->safe_string($order, 'fee_asset');
        $feeCurrency = $this->safe_currency_code($feeCurrencyId);
        if ($market !== null) {
            $symbol = $market['symbol'];
            if ($feeCurrency === null) {
                $feeCurrency = $market['quote'];
            }
        }
        $remaining = $this->safe_float($order, 'left');
        $status = $this->parse_order_status($this->safe_string($order, 'status'));
        $type = $this->safe_string($order, 'order_type');
        $side = $this->safe_string($order, 'type');
        return array (
            'id' => $this->safe_string($order, 'id'),
            'datetime' => $this->iso8601 ($timestamp),
            'timestamp' => $timestamp,
            'lastTradeTimestamp' => null,
            'status' => $status,
            'symbol' => $symbol,
            'type' => $type,
            'side' => $side,
            'price' => $price,
            'cost' => $cost,
            'average' => $average,
            'amount' => $amount,
            'filled' => $filled,
            'remaining' => $remaining,
            'trades' => null,
            'fee' => array (
                'currency' => $feeCurrency,
                'cost' => $this->safe_float($order, 'deal_fee'),
            ),
            'info' => $order,
        );
    }

    public function create_order ($symbol, $type, $side, $amount, $price = null, $params = array ()) {
        $amount = floatval ($amount); // this line is deprecated
        if ($type === 'market') {
            // for $market buy it requires the $amount of quote currency to spend
            if ($side === 'buy') {
                if ($this->options['createMarketBuyOrderRequiresPrice']) {
                    if ($price === null) {
                        throw new InvalidOrder($this->id . " createOrder() requires the $price argument with $market buy orders to calculate total $order cost ($amount to spend), where cost = $amount * $price-> Supply a $price argument to createOrder() call if you want the cost to be calculated for you from $price and $amount, or, alternatively, add .options['createMarketBuyOrderRequiresPrice'] = false to supply the cost in the $amount argument (the exchange-specific behaviour)");
                    } else {
                        $price = floatval ($price); // this line is deprecated
                        $amount = $amount * $price;
                    }
                }
            }
        }
        $this->load_markets();
        $method = 'privatePostOrder' . $this->capitalize ($type);
        $market = $this->market ($symbol);
        $request = array (
            'market' => $market['id'],
            'amount' => $this->amount_to_precision($symbol, $amount),
            'type' => $side,
        );
        if ($type === 'limit') {
            $price = floatval ($price); // this line is deprecated
            $request['price'] = $this->price_to_precision($symbol, $price);
        }
        $response = $this->$method (array_merge ($request, $params));
        $order = $this->parse_order($response['data'], $market);
        $id = $order['id'];
        $this->orders[$id] = $order;
        return $order;
    }

    public function cancel_order ($id, $symbol = null, $params = array ()) {
        $this->load_markets();
        $market = $this->market ($symbol);
        $request = array (
            'id' => $id,
            'market' => $market['id'],
        );
        $response = $this->privateDeleteOrderPending (array_merge ($request, $params));
        return $this->parse_order($response['data'], $market);
    }

    public function fetch_order ($id, $symbol = null, $params = array ()) {
        if ($symbol === null) {
            throw new ArgumentsRequired($this->id . ' fetchOrder requires a $symbol argument');
        }
        $this->load_markets();
        $market = $this->market ($symbol);
        $request = array (
            'id' => $id,
            'market' => $market['id'],
        );
        $response = $this->privateGetOrder (array_merge ($request, $params));
        //
        //     {
        //         "code" => 0,
        //         "data" => array (
        //             "amount" => "0.1",
        //             "asset_fee" => "0.22736197736197736197",
        //             "avg_price" => "196.85000000000000000000",
        //             "create_time" => 1537270135,
        //             "deal_amount" => "0.1",
        //             "deal_fee" => "0",
        //             "deal_money" => "19.685",
        //             "fee_asset" => "CET",
        //             "fee_discount" => "0.5",
        //             "$id" => 1788259447,
        //             "left" => "0",
        //             "maker_fee_rate" => "0",
        //             "$market" => "ETHUSDT",
        //             "order_type" => "limit",
        //             "price" => "170.00000000",
        //             "status" => "done",
        //             "taker_fee_rate" => "0.0005",
        //             "type" => "sell",
        //         ),
        //         "message" => "Ok"
        //     }
        //
        return $this->parse_order($response['data'], $market);
    }

    public function fetch_orders_by_status ($status, $symbol = null, $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        if ($limit === null) {
            $limit = 100;
        }
        $request = array (
            'page' => 1,
            'limit' => $limit,
        );
        $market = null;
        if ($symbol !== null) {
            $market = $this->market ($symbol);
            $request['market'] = $market['id'];
        }
        $method = 'privateGetOrder' . $this->capitalize ($status);
        $response = $this->$method (array_merge ($request, $params));
        return $this->parse_orders($response['data']['data'], $market, $since, $limit);
    }

    public function fetch_open_orders ($symbol = null, $since = null, $limit = null, $params = array ()) {
        return $this->fetch_orders_by_status ('pending', $symbol, $since, $limit, $params);
    }

    public function fetch_closed_orders ($symbol = null, $since = null, $limit = null, $params = array ()) {
        return $this->fetch_orders_by_status ('finished', $symbol, $since, $limit, $params);
    }

    public function fetch_my_trades ($symbol = null, $since = null, $limit = null, $params = array ()) {
        $this->load_markets();
        if ($limit === null) {
            $limit = 100;
        }
        $request = array (
            'page' => 1,
            'limit' => $limit,
        );
        $market = null;
        if ($symbol !== null) {
            $market = $this->market ($symbol);
            $request['market'] = $market['id'];
        }
        $response = $this->privateGetOrderUserDeals (array_merge ($request, $params));
        return $this->parse_trades($response['data']['data'], $market, $since, $limit);
    }

    public function withdraw ($code, $amount, $address, $tag = null, $params = array ()) {
        $this->check_address($address);
        $this->load_markets();
        $currency = $this->currency ($code);
        if ($tag) {
            $address = $address . ':' . $tag;
        }
        $request = array (
            'coin_type' => $currency['id'],
            'coin_address' => $address, // must be authorized, inter-user transfer by a registered mobile phone number or an email $address is supported
            'actual_amount' => floatval ($amount), // the actual $amount without fees, https://www.coinex.com/fees
            'transfer_method' => '1', // '1' = normal onchain transfer, '2' = internal local transfer from one user to another
        );
        $response = $this->privatePostBalanceCoinWithdraw (array_merge ($request, $params));
        //
        //     {
        //         "$code" => 0,
        //         "data" => array (
        //             "actual_amount" => "1.00000000",
        //             "$amount" => "1.00000000",
        //             "coin_address" => "1KAv3pazbTk2JnQ5xTo6fpKK7p1it2RzD4",
        //             "coin_type" => "BCH",
        //             "coin_withdraw_id" => 206,
        //             "confirmations" => 0,
        //             "create_time" => 1524228297,
        //             "status" => "audit",
        //             "tx_fee" => "0",
        //             "tx_id" => ""
        //         ),
        //         "message" => "Ok"
        //     }
        //
        $transaction = $this->safe_value($response, 'data', array());
        return $this->parse_transaction ($transaction, $currency);
    }

    public function parse_transaction_status ($status) {
        $statuses = array (
            'audit' => 'pending',
            'pass' => 'pending',
            'processing' => 'pending',
            'confirming' => 'pending',
            'not_pass' => 'failed',
            'cancel' => 'canceled',
            'finish' => 'ok',
            'fail' => 'failed',
        );
        return $this->safe_string($statuses, $status, $status);
    }

    public function parse_transaction ($transaction, $currency = null) {
        //
        // fetchDeposits
        //
        //     {
        //         "actual_amount" => "120.00000000",
        //         "actual_amount_display" => "120",
        //         "add_explorer" => "XXX",
        //         "$amount" => "120.00000000",
        //         "amount_display" => "120",
        //         "coin_address" => "XXXXXXXX",
        //         "coin_address_display" => "XXXXXXXX",
        //         "coin_deposit_id" => 1866,
        //         "coin_type" => "USDT",
        //         "confirmations" => 0,
        //         "create_time" => 1539595701,
        //         "explorer" => "",
        //         "remark" => "",
        //         "$status" => "finish",
        //         "status_display" => "finish",
        //         "transfer_method" => "local",
        //         "tx_id" => "",
        //         "tx_id_display" => "XXXXXXXXXX"
        //     }
        //
        // fetchWithdrawals
        //
        //     {
        //         "actual_amount" => "0.10000000",
        //         "$amount" => "0.10000000",
        //         "coin_address" => "15sr1VdyXQ6sVLqeJUJ1uPzLpmQtgUeBSB",
        //         "coin_type" => "BCH",
        //         "coin_withdraw_id" => 203,
        //         "confirmations" => 11,
        //         "create_time" => 1515806440,
        //         "$status" => "finish",
        //         "tx_fee" => "0",
        //         "tx_id" => "896371d0e23d64d1cac65a0b7c9e9093d835affb572fec89dd4547277fbdd2f6"
        //     }
        //
        $id = $this->safe_string_2($transaction, 'coin_withdraw_id', 'coin_deposit_id');
        $address = $this->safe_string($transaction, 'coin_address');
        $tag = $this->safe_string($transaction, 'remark'); // set but unused
        if ($tag !== null) {
            if (strlen ($tag) < 1) {
                $tag = null;
            }
        }
        $txid = $this->safe_value($transaction, 'tx_id');
        if ($txid !== null) {
            if (strlen ($txid) < 1) {
                $txid = null;
            }
        }
        $currencyId = $this->safe_string($transaction, 'coin_type');
        $code = $this->safe_currency_code($currencyId, $currency);
        $timestamp = $this->safe_integer($transaction, 'create_time');
        if ($timestamp !== null) {
            $timestamp = $timestamp * 1000;
        }
        $type = (is_array($transaction) && array_key_exists('coin_withdraw_id', $transaction)) ? 'withdraw' : 'deposit';
        $status = $this->parse_transaction_status ($this->safe_string($transaction, 'status'), $type);
        $amount = $this->safe_float($transaction, 'amount');
        $feeCost = $this->safe_float($transaction, 'tx_fee');
        if ($type === 'deposit') {
            $feeCost = 0;
        }
        $fee = array (
            'cost' => $feeCost,
            'currency' => $code,
        );
        return array (
            'info' => $transaction,
            'id' => $id,
            'txid' => $txid,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601 ($timestamp),
            'address' => $address,
            'tag' => $tag,
            'type' => $type,
            'amount' => $amount,
            'currency' => $code,
            'status' => $status,
            'updated' => null,
            'fee' => $fee,
        );
    }

    public function fetch_withdrawals ($code = null, $since = null, $limit = null, $params = array ()) {
        if ($code === null) {
            throw new ArgumentsRequired($this->id . ' fetchWithdrawals requires a $currency $code argument');
        }
        $this->load_markets();
        $currency = $this->currency ($code);
        $request = array (
            'coin_type' => $currency['id'],
        );
        if ($limit !== null) {
            $request['Limit'] = $limit;
        }
        $response = $this->privateGetBalanceCoinWithdraw (array_merge ($request, $params));
        //
        //     {
        //         "$code" => 0,
        //         "data" => array (
        //             array (
        //                 "actual_amount" => "1.00000000",
        //                 "amount" => "1.00000000",
        //                 "coin_address" => "1KAv3pazbTk2JnQ5xTo6fpKK7p1it2RzD4",
        //                 "coin_type" => "BCH",
        //                 "coin_withdraw_id" => 206,
        //                 "confirmations" => 0,
        //                 "create_time" => 1524228297,
        //                 "status" => "audit",
        //                 "tx_fee" => "0",
        //                 "tx_id" => ""
        //             ),
        //             array (
        //                 "actual_amount" => "0.10000000",
        //                 "amount" => "0.10000000",
        //                 "coin_address" => "15sr1VdyXQ6sVLqeJUJ1uPzLpmQtgUeBSB",
        //                 "coin_type" => "BCH",
        //                 "coin_withdraw_id" => 203,
        //                 "confirmations" => 11,
        //                 "create_time" => 1515806440,
        //                 "status" => "finish",
        //                 "tx_fee" => "0",
        //                 "tx_id" => "896371d0e23d64d1cac65a0b7c9e9093d835affb572fec89dd4547277fbdd2f6"
        //             ),
        //             {
        //                 "actual_amount" => "0.00100000",
        //                 "amount" => "0.00100000",
        //                 "coin_address" => "1GVVx5UBddLKrckTprNi4VhHSymeQ8tsLF",
        //                 "coin_type" => "BCH",
        //                 "coin_withdraw_id" => 27,
        //                 "confirmations" => 0,
        //                 "create_time" => 1513933541,
        //                 "status" => "cancel",
        //                 "tx_fee" => "0",
        //                 "tx_id" => ""
        //             }
        //         ),
        //         "message" => "Ok"
        //     }
        //
        return $this->parseTransactions ($response['data'], $currency, $since, $limit);
    }

    public function fetch_deposits ($code = null, $since = null, $limit = null, $params = array ()) {
        if ($code === null) {
            throw new ArgumentsRequired($this->id . ' fetchDeposits requires a $currency $code argument');
        }
        $this->load_markets();
        $currency = $this->currency ($code);
        $request = array (
            'coin_type' => $currency['id'],
        );
        if ($limit !== null) {
            $request['Limit'] = $limit;
        }
        $response = $this->privateGetBalanceCoinDeposit (array_merge ($request, $params));
        //     {
        //         "$code" => 0,
        //         "data" => array (
        //             {
        //                 "actual_amount" => "4.65397682",
        //                 "actual_amount_display" => "4.65397682",
        //                 "add_explorer" => "https://etherscan.io/address/0x361XXXXXX",
        //                 "amount" => "4.65397682",
        //                 "amount_display" => "4.65397682",
        //                 "coin_address" => "0x36dabcdXXXXXX",
        //                 "coin_address_display" => "0x361X*****XXXXX",
        //                 "coin_deposit_id" => 966191,
        //                 "coin_type" => "ETH",
        //                 "confirmations" => 30,
        //                 "create_time" => 1531661445,
        //                 "explorer" => "https://etherscan.io/tx/0x361XXXXXX",
        //                 "remark" => "",
        //                 "status" => "finish",
        //                 "status_display" => "finish",
        //                 "transfer_method" => "onchain",
        //                 "tx_id" => "0x361XXXXXX",
        //                 "tx_id_display" => "0x361XXXXXX"
        //             }
        //         ),
        //         "message" => "Ok"
        //     }
        //
        return $this->parseTransactions ($response['data'], $currency, $since, $limit);
    }

    public function nonce () {
        return $this->milliseconds ();
    }

    public function sign ($path, $api = 'public', $method = 'GET', $params = array (), $headers = null, $body = null) {
        $path = $this->implode_params($path, $params);
        $url = $this->urls['api'] . '/' . $this->version . '/' . $path;
        $query = $this->omit ($params, $this->extract_params($path));
        if ($api === 'public') {
            if ($query) {
                $url .= '?' . $this->urlencode ($query);
            }
        } else {
            $this->check_required_credentials();
            $nonce = $this->nonce ();
            $query = array_merge (array (
                'access_id' => $this->apiKey,
                'tonce' => (string) $nonce,
            ), $query);
            $query = $this->keysort ($query);
            $urlencoded = $this->urlencode ($query);
            $signature = $this->hash ($this->encode ($urlencoded . '&secret_key=' . $this->secret));
            $headers = array (
                'Authorization' => strtoupper($signature),
                'Content-Type' => 'application/json',
            );
            if (($method === 'GET') || ($method === 'DELETE')) {
                $url .= '?' . $urlencoded;
            } else {
                $body = $this->json ($query);
            }
        }
        return array( 'url' => $url, 'method' => $method, 'body' => $body, 'headers' => $headers );
    }

    public function request ($path, $api = 'public', $method = 'GET', $params = array (), $headers = null, $body = null) {
        $response = $this->fetch2 ($path, $api, $method, $params, $headers, $body);
        $code = $this->safe_string($response, 'code');
        $data = $this->safe_value($response, 'data');
        if ($code !== '0' || !$data) {
            $responseCodes = array (
                '24' => '\\ccxt\\AuthenticationError',
                '25' => '\\ccxt\\AuthenticationError',
                '107' => '\\ccxt\\InsufficientFunds',
                '600' => '\\ccxt\\OrderNotFound',
                '601' => '\\ccxt\\InvalidOrder',
                '602' => '\\ccxt\\InvalidOrder',
                '606' => '\\ccxt\\InvalidOrder',
            );
            $ErrorClass = $this->safe_value($responseCodes, $code, '\\ccxt\\ExchangeError');
            throw new $ErrorClass($response['message']);
        }
        return $response;
    }
}
