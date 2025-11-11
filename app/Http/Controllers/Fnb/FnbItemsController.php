<?php

namespace App\Http\Controllers\Fnb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Config;
use App\models\FnB\KitchenStations;
use App\models\FnB\MenuCategories;
use App\models\FnB\FnbItem;
use App\models\Sales\Terminals;
use App\models\System\Companies;
use App\models\Accounting\VatAccounts;
use App\models\FnB\Modifier;
use App\models\Inventory\Products;
use App\models\System\Currency;
use Milon\Barcode\DNS1D;


class FnbItemsController extends Controller
{
    public function ListItems(Request $request)
    {
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_kitchens = KitchenStations::whereKsIsDeleted(0)->get();
        $data = array(
            "lst_companies" => $lst_companies,
            "lst_kitchens" => $lst_kitchens
        );
        return Response()->view('fnb.menu-items.items', $data);
    }



    public function DisplayListItems(Request $request)
    {
        $page_number   = $request->input('page_number');
        $search_query  = $request->input('search_query');
        $branch_id     = $request->input('branch_id');
        $kitchen_id    = $request->input('kitchen_id');

        $nbr_rows_per_pages = Config::get('apmconfig.max_rows_per_page', 10);

        $skip = ($page_number > 1)
            ? ($page_number - 1) * $nbr_rows_per_pages
            : 0;

        $query = FnbItem::where('fi_is_deleted', 0);

        if ($branch_id > 0) {
            $query->where('fi_branch_id', $branch_id);
        }

        if ($kitchen_id > 0) {
            $query->where('fi_kitchen_id', $kitchen_id);
        }

        if (!empty($search_query)) {
            $query->where('fi_item_name', 'LIKE', '%' . $search_query . '%');
        }

        // Pagination count
        $total_items  = $query->count();
        $total_pages  = max(1, ceil($total_items / $nbr_rows_per_pages));

        $lst_products = $query->orderBy('fi_item_name', 'ASC')
            ->skip($skip)
            ->take($nbr_rows_per_pages)
            ->get();

        $data = [
            "lst_products" => $lst_products,
        ];

        $result_array = [
            'total_pages' => $total_pages,
            'display'     => view("fnb.menu-items.displaylistitems", $data)->render(),
        ];

        return response()->json($result_array);
    }

    public function AddProductItem()
    {
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_categories = MenuCategories::whereMcIsDeleted(0)->get();
        $lst_kitchens = KitchenStations::whereKsIsDeleted(0)->get();
        $lst_stations = Terminals::wherePtIsDeleted(0)->get();
        $lst_taxes = VatAccounts::whereAvIsDeleted(0)->get();

        $rand_barcode = rand(10000000, 99999999999);

        $barcode_obj = new DNS1D();
        $bar_code_png = $barcode_obj->getBarcodePNG($rand_barcode, "C39", 2, 50);

        $data = [
            "lst_companies" => $lst_companies,
            "lst_categories" => $lst_categories,
            "lst_kitchens" => $lst_kitchens,
            "lst_stations" => $lst_stations,
            "lst_taxes" => $lst_taxes,
            "rand_barcode" => $rand_barcode,
            "bar_code_png" => $bar_code_png
        ];

        return response()->view('fnb.menu-items.additem', $data);
    }


    public function saveItem(Request $request)
    {
        $fi_id = $request->input('fi_id');
        $fi_item_name = $request->input('fi_item_name');
        $fi_branch_id = $request->input('fi_branch_id');
        $fi_kitchen_id = $request->input('fi_kitchen_id');
        $fi_category_id = $request->input('fi_category_id');
        $fi_station_id = $request->input('fi_station_id');
        $fi_tax_id = $request->input('fi_tax_id');
        $fi_is_active = $request->input('fi_is_active') ? 1 : 0;
        $fi_is_sellable = $request->input('fi_is_sellable') ? 1 : 0;
        $fi_is_stock_item = $request->input('fi_is_stock_item') ? 1 : 0;
        $fi_print_to_kitchen = $request->input('fi_print_to_kitchen') ? 1 : 0;
        $fi_barcode = $request->input('p_bar_code');

        $result_array = array();

        $item_info = new FnbItem();
        if ($fi_id != null) {
            $item_info = FnbItem::find($fi_id);
        }

        $item_info->fi_branch_id = $fi_branch_id;
        $item_info->fi_kitchen_id = $fi_kitchen_id;
        $item_info->fi_category_id = $fi_category_id;
        $item_info->fi_station_id = $fi_station_id;
        $item_info->fi_tax_id = $fi_tax_id;
        $item_info->fi_is_active = $fi_is_active;
        $item_info->fi_is_sellable = $fi_is_sellable;
        $item_info->fi_is_stock_item = $fi_is_stock_item;
        $item_info->fi_print_to_kitchen = $fi_print_to_kitchen;
        $item_info->fi_barcode = $fi_barcode;
        $item_info->fi_item_name = $fi_item_name;

        $item_info->save();

        $fi_id = $item_info->fi_id;

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Information Has been saved';

        return Response()->json($result_array);
    }

    public function EditItem($fi_id)
    {
        $item_info = FnbItem::findOrFail($fi_id);

        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_categories = MenuCategories::whereMcIsDeleted(0)->get();
        $lst_kitchens = KitchenStations::whereKsIsDeleted(0)->get();
        $lst_stations = Terminals::wherePtIsDeleted(0)->get();
        $lst_taxes = VatAccounts::whereAvIsDeleted(0)->get();

        $barcode_obj = new DNS1D();
        $bar_code_png = $barcode_obj->getBarcodePNG($item_info->fi_barcode, "C39+", 150, 50);

        $lst_modifiers = Modifier::whereMIsDeleted(0)->get();
        $lst_products = Products::wherePProductIsDeleted(0)->get();
        $lst_currencies = Currency::get();

        return view('fnb.menu-items.edititem', [
            'item_info'      => $item_info,
            'lst_companies'  => $lst_companies,
            'lst_categories' => $lst_categories,
            'lst_kitchens'   => $lst_kitchens,
            'lst_stations'   => $lst_stations,
            'lst_taxes'      => $lst_taxes,
            'bar_code_png'   => $bar_code_png,
            'lst_modifiers'  => $lst_modifiers,
            'lst_products'   => $lst_products,
            'lst_currencies' => $lst_currencies
        ]);
    }

    public function DeleteItem(Request $request)
    {
        $fi_id = $request->input('fi_id');

        $item_info = FnbItem::find($fi_id);
        $item_info->fi_is_deleted = 1;
        $item_info->fi_deleted_by = Session('user_id');
        $item_info->save();

        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }

}
