<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookkeeping;
use App\Models\BankCategories;
use App\Models\BankDetails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Auth;

class BookkeepingController extends Controller
{
    public function index(){
        $banks = Bookkeeping::where('user_id',Auth::user()->id)->get();
        return view('bookkeeping.banks', compact('banks'));
    }

    /* Add Bank Account */
    public function add_bank(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:banks,name|max:50',
                'code' => 'required|max:3',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return redirect()->back()->withErrors($errors->first());
            }
            else{
                $banks = new Bookkeeping;
                $banks->code = $request->code;
                $banks->name = $request->name;
                $banks->currency = $request->currency;
                $banks->user_id = Auth::user()->id;
                if($banks->save()) return redirect()->back()->withSuccess('New bank saved!');
                else return redirect()->back()->withErrors('Something went wrong!');
            }
        }catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong!');
        }
    }

    /* Edit Bank Account */
    public function update_bank(Request $request,$id){
        try{
            $validator = Validator::make($request->all(), [
                'name' => ['required', Rule::unique('banks')->ignore($id),'max:50'],
                'code' => 'required|max:3',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return redirect()->back()->withErrors($errors->first());
            }
            else{
                $banks = Bookkeeping::find($id);
                $banks->code = $request->code;
                $banks->name = $request->name;
                if($banks->save()) return redirect()->back()->withSuccess('Bank saved!');
                else return redirect()->back()->withErrors('Something went wrong!');
            }
        }catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong!');
        }
    }

    /* Delete Bank */
    public function delete_bank($bank_id){
        $banks = Bookkeeping::where('id',$bank_id)->delete();
        if($banks) return redirect()->back()->withSuccess('Bank deleted!');
        else return redirect()->back()->withErrors('Something went wrong!');
    }

    /* Bank categories */
    public function add_bank_cat(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'category_bank' => 'required|max:50',
                'category_name' => [
                    'required',Rule::unique('BankCategories','name')->where(function ($query) use ($request) {
                        return $query->where('bank_id', $request->input('category_bank'))
                                     ->where('code', $request->input('category_code'));
                    }),
                    'max:50',
                ],
                'category_code' => 'required|max:3',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return redirect()->back()->withErrors($errors->first());
            }
            else{
                $banks = new BankCategories;
                $banks->code = $request->category_code;
                $banks->name = $request->category_name;
                $banks->bank_id = $request->category_bank;
                if($banks->save()) return redirect()->back()->withSuccess('New Category saved!');
                else return redirect()->back()->withErrors('Something went wrong!');
            }
        }catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong!');
        }
    }

    /* Edit Bank Category */
    public function edit_categories_update(Request $request,$id){
        try{
            $validator = Validator::make($request->all(), [
                'category_bank' => 'required|max:50',
                'category_name' => [
                    'required',Rule::unique('BankCategories','name')->where(function ($query) use ($request) {
                        return $query->where('bank_id', $request->input('category_bank'))
                                     ->where('code', $request->input('category_code'));
                    })->ignore($id),
                    'max:50',
                ],
                'category_code' => 'required|max:3',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return redirect()->back()->withErrors($errors->first());
            }
            else{
                $banks = BankCategories::find($id);
                $banks->code = $request->category_code;
                $banks->name = $request->category_name;
                $banks->bank_id = $request->category_bank;
                if($banks->save()) return redirect()->back()->withSuccess('Category saved!');
                else return redirect()->back()->withErrors('Something went wrong!');
            }
        }catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong!');
        }
    }

    /* Delete Category */
    public function delete_categories($id){
        $cats = BankCategories::where('id',$id)->delete();
        if($cats) return redirect()->back()->withSuccess('Category deleted!');
        else return redirect()->back()->withErrors('Something went wrong!');
    }

    /* Bank Account Detail */
    public function detail_bank($id){
        $banks = Bookkeeping::find($id);
        $details = BankDetails::where(['bank_id'=>$id])->get();
        return view('bookkeeping.bankDetails', compact('details','banks'));
    }

    /* Add Bank Account Details */
    public function add_bank_detail(Request $request){
        try{
            $detail = new BankDetails;
            $detail->bank_id = $request->bank_id;
            $detail->date = $request->date;
            $detail->type = $request->type;
            $detail->category_id = $request->category_id;
            $detail->des = $request->des;
            $detail->currency = $request->currency;
            $detail->vat = $request->vat;
            $detail->vat_amount = $request->vat_amount;
            $detail->total = $request->total;
            if($detail->save()) return redirect()->back()->withSuccess('Transaction saved!');
            else return redirect()->back()->withErrors('Something went wrong!');
        }catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong!');
        }
    }

    /* Edit Bank Details */
    public function update_bank_detail(Request $request,$id){
        try{
            $detail = BankDetails::find($id);
            $detail->date = $request->date;
            $detail->type = $request->type;
            $detail->category_id = $request->category_id;
            $detail->des = $request->des;
            $detail->currency = $request->currency;
            $detail->vat = $request->vat;
            $detail->vat_amount = $request->vat_amount;
            $detail->total = $request->total;
            if($detail->save()) return redirect()->back()->withSuccess('Transaction saved!');
            else return redirect()->back()->withErrors('Something went wrong!');
        }catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong!');
        }
    }

    /* Delete Bank Detail */
    public function delete_bank_detail($id){
        $detail = BankDetails::where('id',$id)->delete();
        if($detail) return redirect()->back()->withSuccess('Transaction deleted!');
        else return redirect()->back()->withErrors('Something went wrong!');
    }

    /* Attachments */
    public function attachments($contact){
        $attachments = \DB::table('attachments')->where('contact_id',$contact)->get();
        return view('attachments.attachment',compact('attachments','contact'));
    }

    /* Attachments Add */
    public function add_attachment(Request $request,$contact){
        try{
            if($request->hasFile('attachments')){
                $files = $request->file('attachments');
                foreach ($files as $file) {
                    $filename = date('YmdHi').$file->getClientOriginalName();
                    $file->move('uploads/attachments/', $filename);
                    $path = asset("uploads/attachments/".$filename);
                    $res = \DB::table('attachments')->insert([
                        'contact_id' => $contact,
                        'title' => $request->title,
                        'path' => $path
                    ]);
                }
                return redirect()->back()->withSuccess('Attachments saved!');
            }
        }
        catch (\Exception $e) {
            return redirect()->back()->withErrors('Something went wrong!');
        }
    }

    /* Delete Attachments */
    public function delete_attachment($id){
        $detail = \DB::table('attachments')->where('id',$id)->get();
        $delete = \DB::table('attachments')->where('id',$id)->delete();
        if(File::exists($detail[0]->path)){
            File::delete($detail[0]->path);
        }
        if($delete) return redirect()->back()->withSuccess('Attachment deleted!');
        else return redirect()->back()->withErrors('Something went wrong!');
    }
}
