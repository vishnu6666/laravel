<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ReportHelper;
use App\Http\Controllers\Controller;
use App\Model\Category;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Response;
use App\Model\MonthlyBonusMasterDetail;


class AdminReportController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Admin Report Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles  Admin report for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    /**
     * Payment Report view
     *
     * @return void
     */
    public function userReportView()
    {
        return view('admin.reports.user_report');
    }


    /**
     * Payment Report details
     *
     * @param Request $request
     * @return void
     */
    public function userReport(Request $request)
    {
        $this->validate($request, [
            'daterange' => 'required',
            ]);
        
        $fileNo =  Str::random(6);
        $userReport = ReportHelper::userReportExport($request);

        if($userReport->isEmpty())
        {
            return redirect()->route('admin.userReport')->with('error',trans('messages.adminreport.error'));
        }
        
        $reportInfo = ReportHelper::getReportInfo($request);

        if($request->export_type == 'Pdf')
        {
            $pdf = PDF::loadView('admin.exports.user_report_export',[
                'userReports' => $userReport,
                'reportInfo' => $reportInfo,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('user_report-'.$fileNo. str_replace(" - ","",$request->rangedate).'.pdf');
        }
        elseif($request->export_type == 'Csv')
        {

            $fileNo =  Str::random(6);
            $columnNames = ['No','name', 'email', 'mobileNumber', 'referralCode'];

            $rows = [];
          
                foreach ($userReport as $key => $user) {

                $rows [] = array($key = $key + 1,$user['name'], $user['email'], $user['mobileNumber'], $user['referralCode']);
                    // Add a new row with data
                 
                }


            return self::getCsv($columnNames, $rows, 'user_report-' . $fileNo . str_replace(" - ", "", $request->rangedate) . '.csv');
        }       
    }

  

    public static function getCsv($columnNames, $rows, $fileName = 'file.csv')
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=" . $fileName,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        $callback = function () use ($columnNames,
            $rows
        ) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columnNames);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function gameReportView()
    {
        return view('admin.reports.game_report');
    }

    public function gameReport(Request $request)
    {
        $this->validate($request, [
            'daterange' => 'required'
        ]);

        $fileNo =  Str::random(6);
        $gameReport = ReportHelper::gameReportExport($request);


        if($gameReport->isEmpty()){
            return redirect()->route('admin.gameReport')->with('error', trans('messages.adminreport.error'));
        }

        $reportInfo = ReportHelper::getReportInfo($request);

        if ($request->export_type == 'Pdf'
        ) {
            $pdf = PDF::loadView('admin.exports.game_report_export', [
                'gameReport' => $gameReport,
                'reportInfo' => $reportInfo,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('game_report-' . $fileNo . str_replace(" - ", "", $request->rangedate) . '.pdf');
        } elseif ($request->export_type == 'Csv') {
            $fileNo =  Str::random(6);
            $columnNames = ['No', 'Name', 'Full Name', 'Launch Date'];
            $rows = [];
            foreach ($gameReport as $key => $value) {
                $rows[] = array($key = $key + 1, $value['gameName'], $value['gameFullName'], $value['launchDate']);
                // Add a new row with data
            }
            return self::getCsv($columnNames, $rows,
                'game_report-' . $fileNo . str_replace(" - ", "", $request->rangedate) . '.csv'
            );
        }
    }



    public function gameTripReportView()
    {
        return view('admin.reports.game_trip_report');
    }


    public function gameTripReport(Request $request)
    {
        
        $this->validate($request, [
            'daterange' => 'required',
        ]);

        $fileNo =  Str::random(6);
        $gameTripReports = ReportHelper::gameTripReportExport($request);

        if($gameTripReports->isEmpty()){
            return redirect()->route('admin.gameTripReport')->with('error', trans('messages.adminreport.error'));
        }

        $reportInfo = ReportHelper::getReportInfo($request);

        if ($request->export_type == 'Pdf') {

           
            // return view ('admin.exports.game_trip_report_export', [
            //     'gameTripReports' => $gameTripReports,
            //     'reportInfo' => $reportInfo,
            // ]);

            // exit;



            $pdf = PDF::loadView('admin.exports.game_trip_report_export', [
                'gameTripReports' => $gameTripReports,
                'reportInfo' => $reportInfo,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('game_trip_report-' . $fileNo . str_replace(" - ", "", $request->rangedate) . '.pdf');
        } elseif ($request->export_type == 'Csv') {

            $fileNo =  Str::random(6);
            $columnNames = ['No', 'Package Name', 'Game Name', 'Tip Name', 'Odds', 'Units', 'Win Loss','Text'];
            $rows = [];
            foreach ($gameTripReports as $key => $value) {
                $rows[] = array(
                    $key = $key + 1,
                $value['packageName'], $value['gameName'], $value['tips'], $value['odds'],
                $value['units'], $value['IsWin'], $value['text']);
            }
            return self::getCsv($columnNames, $rows, 'game_trip_report-' . $fileNo . str_replace(" - ", "", $request->rangedate) . '.csv');
        }
    }

    public function paymentTransactionReportView()
    {
        return view('admin.reports.payment_transaction_report');
    }

    public function paymentTransactionReport(Request $request)
    {

        $this->validate($request, [
            'daterange' => 'required',
        ]);

        $fileNo =  Str::random(6);
        $paymentTransactionReports = ReportHelper::paymentTransactionExport($request);

        if($paymentTransactionReports->isEmpty()){
            return redirect()->route('admin.paymentTransactionReport')->with('error', trans('messages.adminreport.error'));
        }

        $reportInfo = ReportHelper::getReportInfo($request);

        if ($request->export_type == 'Pdf') {

            $pdf = PDF::loadView('admin.exports.payment_transaction_report_export', [
                'paymentTransactionReports' => $paymentTransactionReports,
                'reportInfo' => $reportInfo,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('payment_transaction_report-' . $fileNo . str_replace(" - ", "", $request->rangedate) . '.pdf');
        } elseif ($request->export_type == 'Csv') {

            $fileNo =  Str::random(6);
            $columnNames = ['No', 'User Name', 'Plan Name', 'Plan Type', 'Paid Amount', 'Expiry Date'];
            $rows = [];
            foreach ($paymentTransactionReports as $key => $value) {
                $rows[] = array(
                    $key = $key + 1,
                    $value['name'], $value['planName'], $value['planType'],
                    $value['amount'],  $value['subscriptionExpiryDate']
                );
            }
            return self::getCsv($columnNames, $rows, 'payment_transaction_report-' . $fileNo . str_replace(" - ", "", $request->rangedate) . '.csv');
        }
    }


    public function feedbackReportView()
    {
        return view('admin.reports.feedback_report');
    }

    public function feedbackReport(Request $request)
    {

      

        $this->validate($request, [
            'daterange' => 'required',
    
        ]);

        $fileNo =  Str::random(6);
        $contactUsReports = ReportHelper::contactUsExport($request);

    
        if($contactUsReports->isEmpty()){
            return redirect()->route('admin.feedbackReport')->with('error', trans('messages.adminreport.error'));
        }

        $reportInfo = ReportHelper::getReportInfo($request);

        if ($request->export_type == 'Pdf') {
            
            $pdf = PDF::loadView('admin.exports.feedback_report_export', [
                'contactUsReports' => $contactUsReports,
                'reportInfo' => $reportInfo,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('feedback_report-' . $fileNo . str_replace(" - ", "", $request->rangedate) . '.pdf');
        } elseif ($request->export_type == 'Csv') {

            $fileNo =  Str::random(6);
            $columnNames = ['No', 'User Name', 'Subject', 'Message', 'Reply Message'];
            $rows = [];
            foreach ($contactUsReports as $key => $value) {
                $rows[] = array(
                    $key = $key + 1,
                    $value['name'], $value['subject'], $value['message'], $value['reply_message']
                );
            }
            return self::getCsv($columnNames, $rows, 'feedback_report-' . $fileNo . str_replace(" - ", "", $request->rangedate) . '.csv');
        }



    }

    /**
     * game result Report view
     *
     * @return void
     */
    public function gameResultReportView()
    {
        return view('admin.reports.game_result_report');
    }

        /**
     * game result  Report details
     *
     * @param Request $request
     * @return void
     */
    public function gameResultReport(Request $request)
    {
        $this->validate($request, [
            'daterange' => 'required',
            // 'type' => 'required'
            ]);
        
        $fileNo =  Str::random(6);
        $gameResultReport = ReportHelper::gameResultReportExport($request);

        if($gameResultReport->isEmpty())
        {
            return redirect()->route('admin.gameResult')->with('error',trans('messages.adminreport.error'));
        }
        
        $reportInfo = ReportHelper::getReportInfo($request);

        if($request->export_type == 'Pdf')
        {
          
            $pdf = PDF::loadView('admin.exports.game_result_report',[
                'gameResultReport' => $gameResultReport,
                'reportInfo' => $reportInfo,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('game_result_report-'.$fileNo. str_replace(" - ","",$request->rangedate).'.pdf');
        }
        elseif($request->export_type == 'Csv')
        {

            $fileNo =  Str::random(6);
            $columnNames = ['No','Updated By', 'Package Name', 'Game Name', 'Tips','Odds','Units', 'Win Loss'];

            $rows = [];
          
                foreach ($gameResultReport as $key => $report) {

                $rows [] = array($key = $key + 1,$report['name'], $report['packageName'], $report['gameName'], $report['tips'],$report['odds'], $report['units'], $report['IsWin']);
                    // Add a new row with data
                 
                }

            return self::getCsv($columnNames, $rows, 'game_result_report-' . $fileNo . str_replace(" - ", "", $request->rangedate) . '.csv');
        }       
    }




}
