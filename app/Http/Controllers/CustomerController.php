<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use Exception;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends ApiController
{
    public function getCustomers() {
        try {
            $authUser = auth("api")->user();
            $customers = Customer::where("user_id", $authUser->id)->get();
            return $this->getSuccessResponse(["customers" => $customers]);
        } catch (Exception $e) {
            return $this->getFailureResponse(["message" => $e->getMessage()]);
        }
    }

    public function create(CustomerRequest $request) {
        try {
            $params = $request->validated();
            $params["user_id"] = auth("api")->user()->id;
            $customer = Customer::create($params);
            return $this->getSuccessResponse(["message" => "Customer saved.", "customer" => $customer]);
        } catch (Exception $e) {
            return $this->getFailureResponse(["message" => $e->getMessage()]);
        }
    }

    public function edit(CustomerRequest $request) {
        try {

            $authUser = auth("api")->user();
            $customer = Customer::where("user_id", $authUser->id)->where("id", $request->id)->first();
            if(empty($customer)) {
                throw new Exception("No customer found.");
            }
            $params = $request->validated();
            $customer->update($params);
            return $this->getSuccessResponse(["message" => "Customer saved.", "customer" => $customer]);
        } catch (Exception $e) {
            return $this->getFailureResponse(["message" => $e->getMessage()]);
        }
    }
    
    public function getCustomerDetails(Request $request) {
        try {
            $authUser = auth("api")->user();
            $customer = Customer::where("user_id", $authUser->id)->where("id", $request->id)->first();
            if(empty($customer)) {
                throw new Exception("No customer found.");
            }
            return $this->getSuccessResponse(["customer" => $customer]);
        } catch (Exception $e) {
            return $this->getFailureResponse(["message" => $e->getMessage()]);
        }
    }

    public function delete(Request $request) {
        try {
            $authUser = auth("api")->user();
            $customer = Customer::where("user_id", $authUser->id)->where("id", $request->id)->first();
            if(empty($customer)) {
                throw new Exception("No customer found.");
            }
            $customer->delete();
            return $this->getSuccessResponse(["message" => "Customer deleted."]);
        } catch (Exception $e) {
            return $this->getFailureResponse(["message" => $e->getMessage()]);
        }
    }
}
