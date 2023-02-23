<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use Exception;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;

class InvoiceController extends ApiController
{

    public function create(InvoiceRequest $request)
    {
        try {

            $params = $request->validated();
            $invoiceData = $this->processInvoiceData($params);
            Invoice::create($invoiceData);
            return $this->getSuccessResponse(["message" => "Invoice created."]);
        } catch (Exception $e) {
            return $this->getFailureResponse(["message" => $e->getMessage()]);
        }
    }

    public function processInvoiceData($params)
    {
        try {

            $customer = Customer::find($params["customer_id"]);
            if(empty($customer)) {
                throw new Exception("No customer found.");
            }
            $products = Product::all()->pluck("name", "id");
            $invoiceData["customer_id"] = $customer->id;
            $invoiceData["customer_name"] = $customer->name;
            $invoiceData["total_amount"] = 0;
            if(!empty($params["products"])) {
                foreach($params["products"] as $product) {
                    $qty = !empty($product["qty"]) ? $product["qty"] : 0;
                    $rate = !empty($product["rate"]) ? $product["rate"] : 0;
                    $invoiceData["items"][] = [
                        "id" => $product["id"],
                        "name" => !empty($products[$product["id"]]) ? $products[$product["id"]] : "",
                        "qty" => $qty,
                        "rate" => $rate
                    ];
                    $invoiceData["total_amount"] += $qty * $rate;
                }
            }
            $invoiceData["items"] = json_encode($invoiceData["items"]);
            $invoiceData["user_id"] = auth("api")->user()->id;
            return $invoiceData;

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getInvoices()
    {
        try {

            $invoices = Invoice::where("user_id", auth("api")->user()->id)->get();
            return $this->getSuccessResponse(["invoices" => $invoices]);

        } catch (Exception $e) {
            return $this->getFailureResponse(["message" => $e->getMessage()]);
        }
    }

    public function getInvoice(Request $request)
    {
        try {

            if(empty($request->id)) {
                throw new Exception("No invoice found.");
            }
            $invoice = Invoice::with("customer")->where("user_id", auth("api")->user()->id)->where("id", $request->id)->first();
            if(empty($invoice)) {
                throw new Exception("No invoice found.");
            }
            $invoiceRes = [
                "customer_id" => $invoice->customer_id,
                "customer_name" => !empty($invoice->customer) ? $invoice->customer->name : $invoice->customer_name,
                "items" => !empty($invoice->items) ? json_decode($invoice->items) : [],
                "created_at" => $invoice->created_at,
                "id" => $invoice->id,
                "total_amount" => $invoice->total_amount
            ];
            return $this->getSuccessResponse(["invoice" => $invoiceRes]);

        } catch (Exception $e) {
            return $this->getFailureResponse(["message" => $e->getMessage()]);
        }
    }

    public function edit(InvoiceRequest $request)
    {
        try {

            if(empty($request->id)) {
                throw new Exception("No invoice found.");
            }
            $invoice = Invoice::where("user_id", auth("api")->user()->id)->where("id", $request->id)->first();
            if(empty($invoice)) {
                throw new Exception("No invoice found.");
            }
            $params = $request->validated();
            $invoiceData = $this->processInvoiceData($params);
            $invoice->update($invoiceData);
            return $this->getSuccessResponse(["message" => "Invoice updated."]);

        } catch (Exception $e) {
            return $this->getFailureResponse(["message" => $e->getMessage()]);
        }
    }

    public function delete(Request $request) {
        try {

            if(empty($request->id)) {
                throw new Exception("No invoice found.");
            }

            $invoice = Invoice::where("user_id", auth("api")->user()->id)->where("id", $request->id)->first();
            if(empty($invoice)) {
                throw new Exception("No invoice found.");
            }
            $invoice->delete();
            return $this->getSuccessResponse(["message" => "Invoice deleted."]);

        } catch (Exception $e) {
            return $this->getFailureResponse(["message" => $e->getMessage()]);
        }
    }


    
}
