<?php

namespace App\Http\Controllers\Fnb;

use App\Http\Controllers\Controller;
use App\library\MenuItemsManager;
use Illuminate\Http\Request;
use Config;
use App\models\FnB\KitchenStations;
use App\models\FnB\MenuCategories;
use App\models\FnB\FnbItem;
use App\models\Sales\Terminals;
use App\models\System\Companies;
use App\models\Accounting\VatAccounts;
use App\models\FnB\FnbMenuItem;
use App\Models\FnB\FnbMenuItemModifier;
use App\models\FnB\Modifier;
use App\models\Inventory\Products;
use App\models\System\Currency;
use App\models\System\SystemStatus;
use App\models\System\Units;
use Milon\Barcode\DNS1D;
use Termwind\Components\Raw;

class FnbItemsController extends Controller
{
    public function ListItems(Request $request)
    {
        $lst_categories = MenuCategories::whereMcIsDeleted(0)->get();
        $data = array(
            "lst_categories" => $lst_categories
        );
        return Response()->view('fnb.menu-items.items', $data);
    }



    public function DisplayListItems(Request $request)
    {
        $page_number   = $request->input('page_number');
        $search_query  = $request->input('search_query');
        $category_id     = $request->input('category_id');

        $nbr_rows_per_pages = Config::get('apmconfig.max_rows_per_page', 10);

        $skip = ($page_number > 1)
            ? ($page_number - 1) * $nbr_rows_per_pages
            : 0;

        $query = FnbMenuItem::where('mi_is_deleted', 0);

        if ($category_id > 0) {
            $query->where('mi_category_id', $category_id);
        }

        if (!empty($search_query)) {
            $query->where('mi_item_name', 'LIKE', '%' . $search_query . '%');
        }

        // Pagination count
        $total_items  = $query->count();
        $total_pages  = max(1, ceil($total_items / $nbr_rows_per_pages));

        $lst_products = $query
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
        $lst_categories = MenuCategories::whereMcIsDeleted(0)->get();
        $lst_currencies = Currency::all();
        $lst_units = Units::where('su_is_deleted', 0)->get();

        $lst_kitchens = KitchenStations::where('ks_is_deleted', 0)
            ->where('ks_is_active', 1)
            ->get();

        $rand_barcode = rand(10000000, 99999999999);

        $barcode_obj = new DNS1D();
        $bar_code_png = $barcode_obj->getBarcodePNG($rand_barcode, "C39", 2, 50);

        $data = [
            "lst_units" => $lst_units,
            "lst_kitchens" => $lst_kitchens,
            "lst_categories" => $lst_categories,
            "lst_currencies" => $lst_currencies,
            "rand_barcode" => $rand_barcode,
            "bar_code_png" => $bar_code_png
        ];

        return response()->view('fnb.menu-items.additem', $data);
    }

    public function saveItem(Request $request)
    {
        $mi_id = $request->input('mi_id');
        $mi_unit_id = $request->input('mi_unit_id');
        $mi_item_name = $request->input('mi_item_name');
        $mi_item_description = $request->input('mi_item_description');
        $mi_barcode = $request->input('mi_barcode');
        $mi_base_price = $request->input('mi_base_price');
        $mi_cost_price = $request->input('mi_cost_price');
        $mi_currency_id = $request->input('mi_currency_id');
        $mi_category_id = $request->input('mi_category_id');
        $mi_sku_code = $request->input('mi_sku_code');
        $mi_preparation_time_minutes = $request->input('mi_preparation_time_minutes');
        $mi_tax_percentage = $request->input('mi_tax_percentage');
        $mi_pos_order_display = $request->input('mi_pos_order_display');
        $mi_calories = $request->input('mi_calories');
        $mi_max_order_quantity = $request->input('mi_max_order_quantity');
        $mi_is_available = $request->has('mi_is_available') ? 1 : 0;
        $mi_is_vegetarian = $request->has('mi_is_vegetarian') ? 1 : 0;
        $mi_is_spicy = $request->has('mi_is_spicy') ? 1 : 0;
        $mi_is_active = $request->has('fi_is_active') ? 1 : 0;
        $mi_kitchen_station_id = $request->input('mi_kitchen_station_id');
        $mi_loyalty_points = (int) $request->input('mi_loyalty_points', 0);

        $image_data = null;
        if ($request->hasFile('mi_avatar_pic')) {
            $itemManager = new MenuItemsManager();
            $upload = $itemManager->UploadItemImage($mi_id);
            if ($upload['is_error'] == 0) {
                $image_data = $upload['data'];
            }
        }

        if ($mi_id != null) {
            $item = FnbMenuItem::find($mi_id);
        } else {
            $item = new FnbMenuItem();
            $item->mi_created_by = session('user_id');
        }
        $item->mi_item_name = $mi_item_name;
        $item->mi_item_description = $mi_item_description;
        $item->mi_barcode = $mi_barcode;
        $item->mi_base_price = $mi_base_price;
        $item->mi_cost_price = $mi_cost_price;
        $item->mi_currency_id = $mi_currency_id;
        $item->mi_unit_id = $mi_unit_id;

        $item->mi_category_id = $mi_category_id;
        $item->mi_sku_code = $mi_sku_code;
        $item->mi_preparation_time_minutes = $mi_preparation_time_minutes;
        $item->mi_tax_percentage = $mi_tax_percentage;
        $item->mi_pos_order_display = $mi_pos_order_display;
        $item->mi_calories = $mi_calories;
        $item->mi_max_order_quantity = $mi_max_order_quantity;
        $item->mi_is_available = $mi_is_available;
        $item->mi_is_vegetarian = $mi_is_vegetarian;
        $item->mi_is_spicy = $mi_is_spicy;
        $item->mi_is_active = $mi_is_active;
        $item->mi_kitchen_station_id = $mi_kitchen_station_id;
        $item->mi_loyalty_points = $mi_loyalty_points;

        if ($image_data != null) {
            $item->mi_image_base_src  = $image_data['mi_image_base_src'];
            $item->mi_image_file_name = $image_data['mi_image_file_name'];
            $item->mi_image_extension = $image_data['mi_image_extension'];
        }

        $item->mi_updated_by = session('user_id');

        $item->save();

        $result_array = array();

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = 'Item saved successfully';


        return  Response()->json($result_array);
    }

    public function EditItem($mi_id)
    {
        $item_info = FnbMenuItem::findOrFail($mi_id);

        $lst_units = Units::where('su_is_deleted', 0)->get();
        $lst_kitchens = KitchenStations::where('ks_is_deleted', 0)
            ->where('ks_is_active', 1)
            ->get();
        $lst_categories = MenuCategories::whereMcIsDeleted(0)->get();
        $lst_currencies = Currency::all();

        $barcode_obj = new DNS1D();
        $bar_code_png = $barcode_obj->getBarcodePNG($item_info->mi_barcode, "C39+", 150, 50);

        $lst_modifiers = Modifier::whereMIsDeleted(0)->with(["Item", "Currency"])->get();


        return view('fnb.menu-items.edititem', [
            'item_info'      => $item_info,
            'lst_units'  => $lst_units,
            'lst_kitchens' => $lst_kitchens,
            'lst_categories' => $lst_categories,
            'bar_code_png'   => $bar_code_png,
            'lst_modifiers'  => $lst_modifiers,
            'lst_currencies' => $lst_currencies
        ]);
    }

    public function getDetails(Request $request)
    {
        $modifier_id = $request->input('modifier_id');

        $modifier = Modifier::with(['Currency'])
            ->where('m_id', $modifier_id)
            ->where('m_is_deleted', 0)
            ->first();

        if (!$modifier) {
            return response()->json(['success' => false]);
        }

        $product = Products::where('p_id', $modifier->m_item_id)
            ->where('p_product_is_deleted', 0)
            ->first();

        return response()->json([
            'success'      => true,
            'product_id'   => $product->p_id ?? 0,
            'product_name' => $product->p_product_name ?? '',
            'currency_id'  => $modifier->m_currency_id,
            'cost'         => $modifier->m_cost_modifier
        ]);
    }



    public function DeleteItem(Request $request)
    {
        $mi_id = $request->input('mi_id');

        $item_info = FnbMenuItem::find($mi_id);
        $item_info->mi_is_deleted = 1;
        $item_info->mi_deleted_by = Session('user_id');
        $item_info->save();

        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }
}
