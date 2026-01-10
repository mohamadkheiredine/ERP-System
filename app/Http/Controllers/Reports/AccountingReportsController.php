<?php
/***********************************************************
CRMReportsController.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 6, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
List Reports for CRM Module
***********************************************************/

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\models\Accounting\ChartAccounts;
use App\models\Inventory\WareHouses;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use League\Csv\Writer;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Config;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\Users\Users;
use App\models\System\Industry;
use App\models\CRM\CRMLeadSources;
use App\models\System\Countries;
use App\models\CRM\CRMClientCategories;
use App\models\CRM\CRMLeadStatus;
use App\models\CRM\CRMLeads;
use App\models\CRM\CRMAccountTypes;
use App\models\CRM\CRMAccounts;


class AccountingReportsController extends Controller
{
    public function TrialBalanceReport(Request $request)
    {
        $date_from = $request->input('date_from') ?: date('Y-m-01');
        $date_to   = $request->input('date_to')   ?: date('Y-m-t');
        $account_id   = $request->input('account_id')   ?: "";

        $company_id = session('default_company_id');

        $lst_accounts = ChartAccounts::whereAaIsDeleted(0)->get();

        $query = "
        SELECT
            acc.aa_id,
            acc.aa_account_ref,
            acc.aa_account AS account_code,
            acc.aa_account_label AS account_name,

            /* Opening Balance */
            IFNULL(SUM(
                CASE
                    WHEN tm.tm_transaction_date < ?
                    THEN tm.tm_debit - tm.tm_credit
                    ELSE 0
                END
            ), 0) AS opening_balance,

            /* Period Debits */
            IFNULL(SUM(
                CASE
                    WHEN tm.tm_transaction_date BETWEEN ? AND ?
                    THEN tm.tm_debit
                    ELSE 0
                END
            ), 0) AS period_debits,

            /* Period Credits */
            IFNULL(SUM(
                CASE
                    WHEN tm.tm_transaction_date BETWEEN ? AND ?
                    THEN tm.tm_credit
                    ELSE 0
                END
            ), 0) AS period_credits

        FROM acc_accounting_accounts acc
        LEFT JOIN acc_transaction_movements tm
            ON tm.tm_ledger_account = acc.aa_id
            AND tm.tm_company_id = ?

        WHERE acc.aa_is_deleted = 0
    ";

        if(strlen($account_id) > 0) {
            $query .= " AND acc.aa_account_id = " . $account_id;
        }


        $query .="
        GROUP BY acc.aa_id, acc.aa_account_ref, acc.aa_account, acc.aa_account_label
        ORDER BY acc.aa_account ASC";

        $lst_trial_balance = DB::select($query, [
            $date_from,          // <
            $date_from, $date_to, // BETWEEN
            $date_from, $date_to, // BETWEEN
            $company_id
        ]);

        return response()->view('reports.trialbalance', [
            'lst_trial_balance' => $lst_trial_balance,
            'lst_accounts' => $lst_accounts
        ]);

    }


    public function DisplayListTrialBalance(Request $request)
    {
        /* ============================
           Dates
           ============================ */
        $date_from = $request->input('date_from') ?: date('Y-m-01');
        $date_to   = $request->input('date_to')   ?: date('Y-m-t');
        $account_id   = $request->input('account_id')   ?: "";

        $company_id = session('default_company_id');

        /* ============================
           Trial Balance Query
           ============================ */
        $query = "
        SELECT
            acc.aa_id,
            acc.aa_account_ref,
            acc.aa_account AS account_code,
            acc.aa_account_label AS account_name,

            /* Opening Balance */
            IFNULL(SUM(
                CASE
                    WHEN tm.tm_transaction_date < ?
                    THEN tm.tm_debit - tm.tm_credit
                    ELSE 0
                END
            ), 0) AS opening_balance,

            /* Period Debits */
            IFNULL(SUM(
                CASE
                    WHEN tm.tm_transaction_date BETWEEN ? AND ?
                    THEN tm.tm_debit
                    ELSE 0
                END
            ), 0) AS period_debits,

            /* Period Credits */
            IFNULL(SUM(
                CASE
                    WHEN tm.tm_transaction_date BETWEEN ? AND ?
                    THEN tm.tm_credit
                    ELSE 0
                END
            ), 0) AS period_credits

        FROM acc_accounting_accounts acc
        LEFT JOIN acc_transaction_movements tm
            ON tm.tm_ledger_account = acc.aa_id
            AND tm.tm_company_id = ?";

        if($account_id  > 0) {
            $query .= " where acc.aa_account = " . $account_id;
        }
        $query .="
        GROUP BY acc.aa_id, acc.aa_account_ref, acc.aa_account, acc.aa_account_label
        ORDER BY acc.aa_account ASC";
        $lst_trial_balance = DB::select($query, [
            $date_from,            // opening <
            $date_from, $date_to,  // debits BETWEEN
            $date_from, $date_to,  // credits BETWEEN
            $company_id            // company filter
        ]);

        /* ============================
           Response
           ============================ */
        return response()->json([
            'is_error'  => 0,
            'error_msg' => 'Operation Completed Successfully',
            'display'   => view(
                'reports.lsttrialbalance',
                ['lst_trial_balance' => $lst_trial_balance]
            )->render()
        ]);
    }



    public function DownloadTrialBalanceReport(Request $request)
    {
        $type = $request->input('type');

        $date_from = $request->input('date_from') ?: date('Y-m-01');
        $date_to   = $request->input('date_to')   ?: date('Y-m-t');

        $company_id = session('default_company_id');

        /* ============================
           Trial Balance Query
           ============================ */
        $query = "
        SELECT
            acc.aa_id,
            acc.aa_account_ref,
            acc.aa_account AS account_code,
            acc.aa_account_label AS account_name,

            /* Opening Balance */
            IFNULL(SUM(
                CASE
                    WHEN tm.tm_transaction_date < ?
                    THEN tm.tm_debit - tm.tm_credit
                    ELSE 0
                END
            ), 0) AS opening_balance,

            /* Period Debits */
            IFNULL(SUM(
                CASE
                    WHEN tm.tm_transaction_date BETWEEN ? AND ?
                    THEN tm.tm_debit
                    ELSE 0
                END
            ), 0) AS period_debits,

            /* Period Credits */
            IFNULL(SUM(
                CASE
                    WHEN tm.tm_transaction_date BETWEEN ? AND ?
                    THEN tm.tm_credit
                    ELSE 0
                END
            ), 0) AS period_credits

        FROM acc_accounting_accounts acc
        LEFT JOIN acc_transaction_movements tm
            ON tm.tm_ledger_account = acc.aa_id
            AND tm.tm_company_id = ?

        WHERE acc.aa_is_deleted = 0

        GROUP BY acc.aa_id, acc.aa_account_ref, acc.aa_account, acc.aa_account_label
        ORDER BY acc.aa_account ASC
    ";

        $trial_balance = DB::select($query, [
            $date_from,            // opening <
            $date_from, $date_to,  // debits BETWEEN
            $date_from, $date_to,  // credits BETWEEN
            $company_id            // company filter
        ]);

        /* ==========================================================
           CSV EXPORT
           ========================================================== */
        if ($type === 'csv') {

            $data = [];
            $data[] = [
                'Account Code',
                'Account Name',
                'Opening Balance',
                'Debits',
                'Credits',
                'Closing Balance'
            ];

            foreach ($trial_balance as $row) {
                $closing = $row->opening_balance + ($row->period_debits - $row->period_credits);

                $data[] = [
                    $row->account_code,
                    $row->account_name,
                    number_format($row->opening_balance, 2),
                    number_format($row->period_debits, 2),
                    number_format($row->period_credits, 2),
                    number_format($closing, 2),
                ];
            }

            $csv = Writer::createFromFileObject(new \SplTempFileObject());
            $csv->insertAll($data);

            return $csv->output('trial-balance.csv');
        }

        /* ==========================================================
           PDF EXPORT
           ========================================================== */
        $data = [
            'trial_balance' => $trial_balance,
            'date_from'     => $date_from,
            'date_to'       => $date_to
        ];

        $html = view('reports.print_trial_balance', $data)->render();

        return PDF::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->setOption('encoding', 'UTF-8')
            ->download('trial-balance-report.pdf');
    }


    public function BalanceSheetReport(Request $request)
    {
        $year = $request->input('year') ?: date('Y');
        $company_id = session('default_company_id');

        $query = "
        SELECT
            acc.aa_id,
            acc.aa_account AS account_code,
            acc.aa_account_label AS account_name,
            acc.aa_category_id,

            /* Opening balance before Jan 1 */
            IFNULL(SUM(
                CASE
                    WHEN tm.tm_transaction_date < CONCAT(?, '-01-01')
                    THEN tm.tm_debit - tm.tm_credit
                    ELSE 0
                END
            ), 0) AS opening_balance,

            /* Movement during year */
            IFNULL(SUM(
                CASE
                    WHEN tm.tm_transaction_date BETWEEN CONCAT(?, '-01-01')
                                                   AND CONCAT(?, '-12-31')
                    THEN tm.tm_debit - tm.tm_credit
                    ELSE 0
                END
            ), 0) AS yearly_movement,

            /* Final balance */
            IFNULL(SUM(
                CASE
                    WHEN tm.tm_transaction_date <= CONCAT(?, '-12-31')
                    THEN tm.tm_debit - tm.tm_credit
                    ELSE 0
                END
            ), 0) AS final_balance

        FROM acc_accounting_accounts acc
        LEFT JOIN acc_transaction_movements tm
            ON tm.tm_ledger_account = acc.aa_id
            AND tm.tm_company_id = ?

        WHERE acc.aa_is_deleted = 0

        GROUP BY acc.aa_id, acc.aa_account, acc.aa_account_label, acc.aa_category_id
        ORDER BY acc.aa_category_id, acc.aa_account ASC
    ";

        $balance_sheet = DB::select($query, [
            $year,
            $year, $year,
            $year,
            $company_id
        ]);

        return response()->view('reports.balancesheet', [
            'balance_sheet' => $balance_sheet,
            'year'          => $year
        ]);
    }


    public function DisplayListBalanceSheet(Request $request)
    {
        $year = $request->input('year') ?: date('Y');
        $company_id = session('default_company_id');

        $query = "
        SELECT
            acc.aa_id,
            acc.aa_account AS account_code,
            acc.aa_account_label AS account_name,
            acc.aa_category_id,

            /* Opening balance before Jan 1 */
            IFNULL(SUM(
                CASE
                    WHEN tm.tm_transaction_date < CONCAT(?, '-01-01')
                    THEN tm.tm_debit - tm.tm_credit
                    ELSE 0
                END
            ), 0) AS opening_balance,

            /* Movement during year */
            IFNULL(SUM(
                CASE
                    WHEN tm.tm_transaction_date BETWEEN CONCAT(?, '-01-01')
                                                   AND CONCAT(?, '-12-31')
                    THEN tm.tm_debit - tm.tm_credit
                    ELSE 0
                END
            ), 0) AS yearly_movement,

            /* Final balance */
            IFNULL(SUM(
                CASE
                    WHEN tm.tm_transaction_date <= CONCAT(?, '-12-31')
                    THEN tm.tm_debit - tm.tm_credit
                    ELSE 0
                END
            ), 0) AS final_balance

        FROM acc_accounting_accounts acc
        LEFT JOIN acc_transaction_movements tm
            ON tm.tm_ledger_account = acc.aa_id
            AND tm.tm_company_id = ?

        WHERE acc.aa_is_deleted = 0

        GROUP BY acc.aa_id, acc.aa_account, acc.aa_account_label, acc.aa_category_id
        ORDER BY acc.aa_category_id, acc.aa_account ASC
    ";

        $balance_sheet = DB::select($query, [
            $year,
            $year, $year,
            $year,
            $company_id
        ]);

        return response()->json([
            'is_error'  => 0,
            'error_msg' => 'Operation Completed Successfully',
            'display'   => view(
                'reports.lstbalancesheet',
                ['balance_sheet' => $balance_sheet]
            )->render()
        ]);
    }



    public function DownloadBalanceSheetReport(Request $request)
    {
        $type = $request->input('type');
        $year = $request->input('year') ?: date('Y');
        $company_id = session('default_company_id');

        $query = "
        SELECT
            acc.aa_id,
            acc.aa_account AS account_code,
            acc.aa_account_label AS account_name,
            acc.aa_category_id,

            /* Opening balance before Jan 1 */
            IFNULL(SUM(
                CASE
                    WHEN tm.tm_transaction_date < CONCAT(?, '-01-01')
                    THEN tm.tm_debit - tm.tm_credit
                    ELSE 0
                END
            ), 0) AS opening_balance,

            /* Movement during year */
            IFNULL(SUM(
                CASE
                    WHEN tm.tm_transaction_date BETWEEN CONCAT(?, '-01-01')
                                                   AND CONCAT(?, '-12-31')
                    THEN tm.tm_debit - tm.tm_credit
                    ELSE 0
                END
            ), 0) AS yearly_movement,

            /* Final balance */
            IFNULL(SUM(
                CASE
                    WHEN tm.tm_transaction_date <= CONCAT(?, '-12-31')
                    THEN tm.tm_debit - tm.tm_credit
                    ELSE 0
                END
            ), 0) AS final_balance

        FROM acc_accounting_accounts acc
        LEFT JOIN acc_transaction_movements tm
            ON tm.tm_ledger_account = acc.aa_id
            AND tm.tm_company_id = ?

        WHERE acc.aa_is_deleted = 0

        GROUP BY acc.aa_id, acc.aa_account, acc.aa_account_label, acc.aa_category_id
        ORDER BY acc.aa_category_id, acc.aa_account ASC
    ";

        $balance_sheet = DB::select($query, [
            $year,
            $year, $year,
            $year,
            $company_id
        ]);

        if ($type === 'csv') {

            $data[] = ['Category', 'Account Code', 'Account Name', 'Balance'];

            foreach ($balance_sheet as $row) {
                $category = match ($row->aa_category_id) {
                    1 => 'Assets',
                    2 => 'Liabilities',
                    3 => 'Equity',
                };

                $data[] = [
                    $category,
                    $row->account_code,
                    $row->account_name,
                    number_format($row->final_balance, 2)
                ];
            }

            $csv = Writer::createFromFileObject(new \SplTempFileObject());
            $csv->insertAll($data);

            return $csv->output('balance-sheet-' . $year . '.csv');
        }

        $html = view('reports.print_balance_sheet', [
            'balance_sheet' => $balance_sheet,
            'year'          => $year
        ])->render();

        return PDF::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->setOption('encoding', 'UTF-8')
            ->download('balance-sheet-' . $year . '.pdf');
    }

}
