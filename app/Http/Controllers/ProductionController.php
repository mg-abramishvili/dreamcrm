<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\Project;
use App\Models\Calculation;
use App\Models\StockBalance;

use Illuminate\Http\Request;

use App\Traits\deleteProductionItem;
use App\Traits\updateStockBalance;
use App\Traits\createStockNeed;
use App\Traits\createReserve;
use App\Traits\createProductionItem;

use Carbon\Carbon;

class ProductionController extends Controller
{
    use deleteProductionItem, updateStockBalance, createStockNeed, createReserve, createProductionItem;

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
                'items' => function ($q) {
                    $q->with('stockItem', 'reserves.stockBalance', 'stockNeeds')->orderBy('id', 'asc');
                }
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
        
        foreach($calculation->boxes as $box)
        {
            foreach($box->stockItems as $stockItem)
            {
                $quantity = $stockItem->pivot->quantity * $calculation->quantity;

                $this->createProductionItem($production->id, $stockItem->id, $quantity);
            }
        }

        foreach($calculation->catalogItems as $catalogItem)
        {
            if($catalogItem->price > 0)
            {
                foreach($catalogItem->stockItems as $stockItem)
                {
                    $quantity = $stockItem->pivot->quantity * $calculation->quantity;

                    $this->createProductionItem($production->id, $stockItem->id, $quantity);
                }
            }
        }

        return $production->id;
    }

    public function update($id, Request $request)
    {
        $production = Production::find($id);
        
        if(isset($request->name)) {
            $production->name = $request->name;
        }
        if(isset($request->status)) {
            $production->status = $request->status;
        }
        if(isset($request->priority)) {
            $production->priority = $request->priority;
        }
        if(isset($request->ral)) {
            $production->ral = $request->ral;
        }
        if(isset($request->payment_type)) {
            $production->payment_type = $request->payment_type;
        }
        if(isset($request->supply_info)) {
            $production->supply_info = $request->supply_info;
        }
        if(isset($request->invoice_number)) {
            $production->invoice_number = $request->invoice_number;
        }
        if(isset($request->serial_number)) {
            $production->serial_number = $request->serial_number;
        }
        if(isset($request->activation_key)) {
            $production->activation_key = $request->activation_key;
        }
        if(isset($request->contacts)) {
            $production->contacts = $request->contacts;
        }
        if(isset($request->email)) {
            $production->email = $request->email;
        }
        if(isset($request->description)) {
            $production->description = $request->description;
        }
        if(isset($request->start_date)) {
            $production->start_date = $request->start_date;
        }
        if(isset($request->end_date)) {
            $production->end_date = $request->end_date;
        }

        $production->save();
    }

    public function restart($id)
    {
        $production = Production::find($id);

        foreach($production->items as $item)
        {
            $this->deleteProductionItem($item);
        }

        foreach($production->project->calculations as $calculation)
        {
            foreach($calculation->boxes as $box)
            {
                foreach($box->stockItems as $stockItem)
                {
                    $quantity = $stockItem->pivot->quantity * $calculation->quantity;

                    $this->createProductionItem($production->id, $stockItem->id, $quantity);
                }
            }

            foreach($calculation->catalogItems as $catalogItem)
            {
                if($catalogItem->price > 0)
                {
                    foreach($catalogItem->stockItems as $stockItem)
                    {
                        $quantity = $stockItem->pivot->quantity * $calculation->quantity;

                        $this->createProductionItem($production->id, $stockItem->id, $quantity);
                    }
                }
            }
        }
    }

    public function delete($id)
    {
        $production = Production::find($id);

        foreach($production->items as $item) {
            $this->deleteProductionItem($item);
        }

        $production->delete();
    }
}
