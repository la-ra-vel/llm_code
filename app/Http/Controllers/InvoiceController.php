<?php

namespace App\Http\Controllers;

use App\Models\ClientCase;
use App\Models\GeneralSetting;
use App\Models\PaymentDetail;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Session;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;

class InvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(
        InvoiceService $invoiceService,

    ) {
        $this->invoiceService = $invoiceService;

    }
    /********************************************************************/
    public function authenticateRole($roles = null)
    {
        $permissionRole = [];
        foreach ($roles as $key => $value) {

            $permissionCheck = checkRolePermission($value);

            $permissionRole[] = [
                'role' => $value,
                'access' => $permissionCheck->access
            ];
        }

        if (@$permissionRole[0]['access'] == 0 && @$permissionRole[1]['access'] == 0) {
            Session::flash('flash_message_warning', 'You have no permission');
            return redirect(route('dashboard'))->send();
        }
    }
    /******************************************************************************/
    public function list(Request $request)
    {
        if ($request->ajax()) {
            Cache::forget('cases');
            $data = $this->invoiceService->getAllInvoices();
            // echo "<pre>"; print_r($data->toArray()); exit;

            return DataTables::of($data)
                ->addColumn('counter', function () {
                    static $counter = 1;
                    return $counter++;
                })
                ->editColumn('client_name', function ($row) {
                    return $row->client ? $row->client->full_name : '';
                })
                ->editColumn('total_fee_amount', function ($row) {
                    return $row->total_fee_amount ?? 0;
                })

                ->editColumn('total_payment_amount', function ($row) {
                    return $row->total_payment_amount ?? 0;
                })
                ->editColumn('mobile', function ($row) {
                    return $row->client ? $row->client->mobile : '';
                })
                ->editColumn('remaining_amount', function ($row) {
                    $remainingAmount = ($row->total_fee_amount ?? 0) - ($row->total_payment_amount ?? 0);
                    return $remainingAmount;
                })
                ->editColumn('invoice', function ($row) {
                    $counter = $row->counter_invoice_download+1;

                    return '<a href="javascript:void(0);" data-URL="' . route('invoice.generate') . '" data-counter="' . $counter . '" data-caseID="' . $row->caseID . '" data-ID="' . $row->id . '" class="downloadInvoice" style="text-decoration: none; cursor: pointer; color:blue;">INVOICE</a>';


                })

                ->addColumn('action', function ($row) {
                    return '
                    <a href="javascript:void(0);" data-URL="' . route('send.email', $row->id) . '" data-Email="' . $row->client->email . '" data-ID="' . $row->id . '" class="btn btn-xs btn-primary sendMail">Send Email</a>


                ';
                })

                ->rawColumns(['invoice', 'action']) // This is important to render HTML content
                ->make(true);
        }

    }
    /******************************************************************************/
    public function index(Request $request)
    {
        $roles = [
            '0' => 'invoice'
        ];
        $this->authenticateRole($roles);
        $pageTitle = "Invoices";

        return view('invoice.index', compact('pageTitle'));
    }
    /******************************************************************************/
    public function invoiceGenerate(Request $request)
    {
        $data = $request->all(); // Get the data from the request
        $clientCasePid = $data['id'];
        $mergedDescriptions = $this->invoiceService->getAmountsByFeeDescription($clientCasePid);
        // echo "<pre>"; print_r($mergedDescriptions); exit;
        $general = GeneralSetting::first();

        $logoPath = public_path('uploads/logo/' . ($general->logo ?: $general->default_image));

        $logo = imageToBase64($logoPath); // Convert local file to base64

        $invoice_no = ClientCase::where('id',$clientCasePid)->sum('counter_invoice_download');
        $invoice_no++;
        // Generate the PDF
        $pdf = PDF::loadView('pdf.invoice', compact('general', 'logo', 'mergedDescriptions','invoice_no'));
        ClientCase::find(id: $clientCasePid)->update([
            'counter_invoice_download' => $invoice_no
        ]);
        // Return the PDF as a stream for download
        // return $pdf->download('invoice.pdf');

        return response($pdf->output(), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('invoice_no', $invoice_no);
    }
    public function sendEmail(Request $request, $id = null)
    {
        if ($request->ajax()) {
            try {
                $data = $request->all(); // Get the data from the request
                $clientCasePid = $data['id'];
                $mergedDescriptions = $this->invoiceService->getAmountsByFeeDescription($clientCasePid);
                // echo "<pre>"; print_r($mergedDescriptions['clientCase']->invoice_no); exit;
                $general = GeneralSetting::first();

                $logoPath = public_path('uploads/logo/' . ($general->logo ?: $general->default_image));

                $logo = imageToBase64($logoPath); // Convert local file to base64
                $client_invoice = ClientCase::select('id','caseID','counter_invoice_download')->where('id',$clientCasePid)->first();
                // echo "<pre>"; print_r($client_invoice->toArray()); exit;
                $invoice_no = PaymentDetail::where('client_case_pid',$clientCasePid)->count();
                // Generate the PDF
                $pdf = PDF::loadView('pdf.invoice', compact('general', 'logo', 'mergedDescriptions','invoice_no'));
                $receiptName = "invoice#_{$client_invoice->caseID}-{$client_invoice->counter_invoice_download}.pdf";

                $receiptFullPath = public_path('uploads/invoice/' . $receiptName);

                $pdf->save($receiptFullPath);

                $documents = [

                    [
                        "fileName" => $receiptName,
                        "pdfFullPath" => $receiptFullPath,
                    ],

                ];
                $user = (object) [
                    'username' => $mergedDescriptions['client']->full_name,
                    'email' => $mergedDescriptions['client']->email,
                ];
                // echo "<pre>"; print_r($user); exit;
                sendMail('CASE_INVOICE', ['data' => ['invoice_no' => $mergedDescriptions['clientCase']->invoice_no]], $user, $documents);
                return response()->json(['success' => true, 'message' => 'Attachment sent to client']);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 500);
            }


        }
    }
}
