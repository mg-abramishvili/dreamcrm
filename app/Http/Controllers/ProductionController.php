<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductionItem;
use App\Models\Project;
use App\Models\Calculation;
use App\Models\Reserve;
use App\Models\StockItem;
use App\Models\StockBalance;
use App\Models\StockNeed;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductionController extends Controller
{
    public function index()
    {
        return Production::with('project.calculations.boxes')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function production($id)
    {
        return Production::with([
                'project',
                'user',
                'items.stockItem',
                'items.reserves.stockBalance',
                'items.stockNeeds'
            ])
            ->find($id);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'project' => 'required',
            'name' => 'required',
        ]);

        $project = Project::with('user')->find($request->project);
        
        $calculation = Calculation::where('project_id', $project->id)->with('boxes.stockItems', 'catalogItems.stockItems')->first();
        
        $production = new Production();
        $production->project_id = $project->id;
        $production->user_id = $project->user->id;
        $production->name = $request->name;
        $production->status = 'new';
        $production->priority = $request->priority;
        $production->ral = $request->ral;
        $production->payment_type = $request->payment_type;
        $production->supply_info = $request->supply_info;
        $production->invoice_number = $request->invoice_number;
        $production->serial_number = $request->serial_number;
        $production->activation_key = $request->activation_key;
        $production->contacts = $request->contacts;
        $production->email = $request->email;
        $production->description = $request->description;
        $production->start_date = $request->start_date;
        $production->end_date = $request->end_date;
        
        $production->save();
        
        foreach($calculation->boxes as $box) {
            foreach($box->stockItems as $stockItem) {
                $productionItem = new ProductionItem();
                $productionItem->production_id = $production->id;
                $productionItem->stock_item_id = $stockItem->id;
                $productionItem->save();

                $quantityNeeds = $stockItem->pivot->quantity;

                $stockBalances = StockBalance::where('stock_item_id', $stockItem->id)->where('quantity', '>', 0)->orderBy('id', 'asc')->get();
                $stockBalancesCount = $stockBalances->count();

                if($stockBalancesCount > 0) {
                    $stockBalance = $stockBalances->first();

                    $this->createReserve($quantityNeeds, $stockItem, $stockBalance, $stockBalances, $production, $productionItem);
                } else {
                    $this->createStockNeed($quantityNeeds, $stockItem, $productionItem);
                }
            }
        }

        foreach($calculation->catalogItems as $catalogItem) {
            if($catalogItem->price > 0) {
                foreach($catalogItem->stockItems as $stockItem) {
                    $productionItem = new ProductionItem();
                    $productionItem->production_id = $production->id;
                    $productionItem->stock_item_id = $stockItem->id;
                    $productionItem->save();

                    $quantityNeeds = $stockItem->pivot->quantity;

                    $stockBalances = StockBalance::where('stock_item_id', $stockItem->id)->where('quantity', '>', 0)->orderBy('id', 'asc')->get();
                    $stockBalancesCount = $stockBalances->count();

                    if($stockBalancesCount > 0) {
                        $stockBalance = $stockBalances->first();

                        $this->createReserve($quantityNeeds, $stockItem, $stockBalance, $stockBalances, $production, $productionItem);
                    } else {
                        $this->createStockNeed($quantityNeeds, $stockItem, $productionItem);
                    }
                }
            }
        }

        return $production->id;
    }

    public function createReserve($quantityNeeds, $stockItem, $stockBalance, $stockBalances, $production, $productionItem)
    {
        if($stockBalance->quantity > 0) {
            $reserve = new Reserve();
            $reserve->production_item_id = $productionItem->id;
            $reserve->stock_balance_id = $stockBalance->id;
            $reserve->price = $stockBalance->price;
            $reserve->pre_rub = $stockBalance->pre_rub;
            $reserve->pre_usd = $stockBalance->pre_usd;
            
            if($stockBalance->quantity >= $quantityNeeds) {
                $reserve->quantity = $quantityNeeds;
                $reserve->price_total = $stockBalance->price * $quantityNeeds;
                
                $quantityNeedsLeft = 0;
                
                $this->updateStockBalance($stockBalance, $quantityNeeds);
            } else {
                $reserve->quantity = $stockBalance->quantity;
                $reserve->price_total = $stockBalance->price * $stockBalance->quantity;
                
                $quantityNeedsLeft = $quantityNeeds - $stockBalance->quantity;
                
                $this->updateStockBalance($stockBalance, $stockBalance->quantity);
            }            
            
            $reserve->save();

            if($quantityNeedsLeft > 0) {
                $stockBalance = $stockBalances->where('id', '>', $stockBalance->id)->first();
                
                if($stockBalance) {
                    $this->createReserve($quantityNeedsLeft, $stockItem, $stockBalance, $stockBalances, $production, $productionItem);
                } else {
                    $this->createStockNeed($quantityNeedsLeft, $stockItem, $productionItem);
                }
            }
        } else {
            $stockBalance = $stockBalances->where('id', '>', $stockBalance->id)->first();
            
            if($stockBalance) {
                $this->createReserve($quantityNeeds, $stockItem, $stockBalance, $stockBalances, $production, $productionItem);
            } else {
                $this->createStockNeed($quantityNeeds, $stockItem, $productionItem);
            }
        }
    }

    public function updateStockBalance($stockBalance, $quantity)
    {
        $stockBalance->quantity = $stockBalance->quantity - $quantity;
        
        $stockBalance->save();
    }

    public function createStockNeed($quantity, $stockItem, $productionItem)
    {
        $stockNeed = new StockNeed();
        $stockNeed->quantity = $quantity;
        $stockNeed->stock_item_id = $stockItem->id;
        $stockNeed->production_item_id = $productionItem->id;
        $stockNeed->save();
    }

    public function importRedmineIssues($offset, $limit)
    {
        $url = "https://red.shop-guess.ru/issues.json?key=3a0839320a2044da9bccef46a2a467c6f5268616";
        $url .= "&offset=";
        $url .= $offset;
        $url .= "&limit=";
        $url .= $limit;
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Accept: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl); curl_close($curl);

        $redmineIssues = json_decode($response)->issues;

        if(empty($redmineIssues)) {
            return response('Ошибка получения данных по API', 500);
        }

        foreach($redmineIssues as $redmineIssue)
        {
            // STATUS
            $status = '';
            if($redmineIssue->status->name == 'Новая') {
                $status = 'new';
            }
            if($redmineIssue->status->name == 'Ждет отзыва клиента') {
                $status = 'waiting_for_feedback';
            }
            if($redmineIssue->status->name == 'Сварка') {
                $status = 'svarka';
            }
            if($redmineIssue->status->name == 'СКЛАД' || $redmineIssue->status->name == 'Склад') {
                $status = 'warehouse';
            }
            if($redmineIssue->status->name == 'Сборка') {
                $status = 'building';
            }

            // PRIORITY
            $priority = '';
            if($redmineIssue->priority->name == 'Нормальный') {
                $priority = 'normal';
            }
            if($redmineIssue->priority->name == 'Высокий') {
                $priority = 'high';
            }
            if($redmineIssue->priority->name == 'Срочный') {
                $priority = 'urgent';
            }

            // USER
            $userId = '';
            if($redmineIssue->author->name == 'Михаил Г.') {
                $userId = 5;
            }
            if($redmineIssue->author->name == 'Юра Г.') {
                $userId = 2;
            }
            if($redmineIssue->author->name == 'Саша Г.') {
                $userId = 6;
            }
            if($redmineIssue->author->name == 'Анна М.') {
                $userId = 4;
            }

            // RAL
            $fieldId = 15;
            $result = array_column($redmineIssue->custom_fields, "value", "id");
            if(isset($result[$fieldId])){
                $ral = $result[$fieldId];
            }

            // PAYMENT TYPE
            $fieldId = 18;
            $result = array_column($redmineIssue->custom_fields, "value", "id");
            if(isset($result[$fieldId])){
                $payment_type = $result[$fieldId];
            }

            // SUPPLY INFO
            $fieldId = 1;
            $result = array_column($redmineIssue->custom_fields, "value", "id");
            if(isset($result[$fieldId])){
                $supply_info = $result[$fieldId];
            }
            
            // INVOICE NUMBER
            $fieldId = 19;
            $result = array_column($redmineIssue->custom_fields, "value", "id");
            if(isset($result[$fieldId])){
                $invoice_number = $result[$fieldId];
            }

            // ADDITIONAL HARDWARE
            $fieldId = 10;
            $result = array_column($redmineIssue->custom_fields, "value", "id");
            if(isset($result[$fieldId])){
                $additional_hardware = implode(', ', $result[$fieldId]);
            }

            // ADDITIONAL FOR TRIBUNE
            $fieldId = 11;
            $result = array_column($redmineIssue->custom_fields, "value", "id");
            if(isset($result[$fieldId])){
                $additional_for_tribune = implode(', ', $result[$fieldId]);
            }

            // CONTACTS
            $fieldId = 14;
            $result = array_column($redmineIssue->custom_fields, "value", "id");
            if(isset($result[$fieldId])){
                $contacts = $result[$fieldId];
            }

            // EMAIL
            $fieldId = 17;
            $result = array_column($redmineIssue->custom_fields, "value", "id");
            if(isset($result[$fieldId])){
                $email = $result[$fieldId];
            }

            // SERIAL NUMBER
            $fieldId = 20;
            $result = array_column($redmineIssue->custom_fields, "value", "id");
            if(isset($result[$fieldId])){
                $serial_number = $result[$fieldId];
            }

            // ACTIVATION KEY
            $fieldId = 21;
            $result = array_column($redmineIssue->custom_fields, "value", "id");
            if(isset($result[$fieldId])){
                $activation_key = $result[$fieldId];
            }

            // DESCRIPTION
            $str = ['\r\n', '\"', '* ', '*', '\/'];
            $rplc =['<br>', '"', '', '', '/'];
            $description = str_replace($str, $rplc, json_encode($redmineIssue->description, JSON_UNESCAPED_UNICODE));
            $description = substr($description, 1, -1);

            $production = Production::updateOrCreate(
                [
                    'id' => $redmineIssue->id,
                ],
                [
                    'status' => $status,
                    'priority' => $priority,
                    'user_id' => $userId,
                    'name' => $redmineIssue->subject,
                    'description' => $description,
                    'invoice_number' => $invoice_number,
                    'ral' => $ral,
                    'payment_type' => $payment_type,
                    'supply_info' => $supply_info,
                    'additional_hardware' => $additional_hardware,
                    'additional_for_tribune' => $additional_for_tribune,
                    'contacts' => $contacts,
                    'email' => $email,
                    'serial_number' => $serial_number,
                    'activation_key' => $activation_key,
                    'created_at' => Carbon::parse($redmineIssue->created_on),
                    'start_date' => Carbon::parse($redmineIssue->start_date)->format('Y-m-d'),
                    'end_date' => Carbon::parse($redmineIssue->due_date)->format('Y-m-d'),
                ]
            );
        }

        return response('Загрузка завершена', 200);
    }
}
