<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\accountingController;
use App\Http\Controllers\departmentsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('frontend/main');
})->name('home');
Route::get('/logout',function(){
    $user = Auth::user();
    $user->is_online = 0;
    $user->save();
    Auth::logout();
    session()->flash('logout_success',true);
    return redirect()->route('login');
});

Auth::routes(['verify' => true]);

Route::get('/admin',[App\Http\Controllers\Admin::class,'index'])->name('admin-dashboard');
Route::get('/admin/agencyThemeSetting/{id}/{value}',[App\Http\Controllers\Admin::class,'agencyThemeSetting']);
Route::get('/admin/clients',[App\Http\Controllers\Admin::class,'clients'])->name('admin-clients');
Route::get('/admin/clients/userThemeSetting/{id}/{value}',[App\Http\Controllers\Admin::class,'userThemeSetting']);
Route::get('/admin/clients/emailModule/{id}/{value}',[App\Http\Controllers\Admin::class,'userEmailSetting']);
Route::get('/admin/clients/invoiceModule/{id}/{value}',[App\Http\Controllers\Admin::class,'userInvoiceSetting']);
Route::post('/admin/changeTheme/{id}',[App\Http\Controllers\Admin::class,'changeTheme']);
Route::post('/changeLogo/{id}',[App\Http\Controllers\Admin::class,'changeLogo']);
Route::get('/removeLogo/{id}',[App\Http\Controllers\Admin::class,'removeLogo']);
Route::get('/admin/projects',[App\Http\Controllers\Admin::class,'projects'])->name('admin-projects');
Route::get('/admin/settings',[App\Http\Controllers\Admin::class,'settings'])->name('admin-settings');
Route::post('/admin/settings',[App\Http\Controllers\Admin::class,'update_settings'])->name('admin-settings');
Route::get('/admin/agency/{id}',[App\Http\Controllers\Admin::class,'delete_agency'])->name('delete-agency');
Route::get('/admin/agency/projects/{id}',[App\Http\Controllers\Admin::class,'agency_projects'])->name('agency_projects');
Route::get('/admin/agency/unlink-client/{chat_id}/{project_id}',[App\Http\Controllers\Admin::class,'unlink_client'])->name('unlink-agency');
Route::get('/admin/usage/{id}',[App\Http\Controllers\Admin::class,'usage'])->name('admin-usage');

Route::put('/admin/client/update/{id}',[App\Http\Controllers\Admin::class,'update_client'])->name('admin-update-client');
Route::get('/admin/client/edit/{id}',[App\Http\Controllers\Admin::class,'edit_client'])->name('admin-edit-client');
Route::get('/admin/client/{id}',[App\Http\Controllers\Admin::class,'delete_client'])->name('delete-client');
Route::get('/admin/approve/agency/{id}',[App\Http\Controllers\Admin::class,'approve_agency'])->name('approve-agency');
Route::get('/admin/notification/{id}',[App\Http\Controllers\Admin::class,'changeNotificationStatus'])->name('user-notification-status');
Route::get('/admin/lite/agencies/',[App\Http\Controllers\Admin::class,'lite_agecnies'])->name('lite_agecnies');
Route::get('/admin/liteAgenciesThemeSetting/{id}/{value}',[App\Http\Controllers\Admin::class,'liteAgenciesThemeSetting']);
Route::get('/admin/lite-agency/{id}',[App\Http\Controllers\Admin::class,'manage_lite_agency'])->name('manage_lite_agency');

Route::put('/admin/lite-agency/{id}',[App\Http\Controllers\Admin::class,'update_lite_agency'])->name('update_lite_agency');


Route::get('/admin/agency/edit/{id}',[App\Http\Controllers\Admin::class,'manage_agency'])->name('manage_agency');

Route::put('/admin/agency/{id}',[App\Http\Controllers\Admin::class,'update_agency'])->name('update_agency');
Route::get('/admin/agency/emailModule/{id}/{value}',[App\Http\Controllers\Admin::class,'agencyEmailSetting']);
Route::get('/admin/agency/invoiceModule/{id}/{value}',[App\Http\Controllers\Admin::class,'agencyInvoiceSetting']);
Route::get('/admin/lite-agency/emailModule/{id}/{value}',[App\Http\Controllers\Admin::class,'liteagencyEmailSetting']);
Route::get('/admin/lite-agency/invoiceModule/{id}/{value}',[App\Http\Controllers\Admin::class,'liteagencyInvoiceSetting']);

/* Admin Bookkeeping */
Route::get('/admin/bookkeepingTool/{id}/{value}',[App\Http\Controllers\Admin::class,'bookkeepingTool']);
Route::get('/admin/accountingTool/{id}/{value}',[App\Http\Controllers\Admin::class,'accountingTool']);
Route::get('/admin/departments_module/{id}/{value}',[App\Http\Controllers\Admin::class,'departments_module']);

/* Contacts */
Route::get('/contacts', [App\Http\Controllers\Admin::class,'contacts'])->name('contacts');
Route::get('/add_contact', [App\Http\Controllers\Admin::class,'add_contact'])->name('add_contact');
Route::post('/store_contact', [App\Http\Controllers\Admin::class,'store_contact'])->name('store_contact');
Route::get('/edit_contact/{id}', [App\Http\Controllers\Admin::class,'edit_contact'])->name('edit_contact');
Route::post('/update_contact', [App\Http\Controllers\Admin::class,'update_contact'])->name('update_contact');
Route::get('/delete_contact/{id}', [App\Http\Controllers\Admin::class,'delete_contact'])->name('delete_contact');
/* End Contacts */

/* Companies */
Route::get('/companies', [App\Http\Controllers\Admin::class,'companies'])->name('companies');
Route::get('/add_company', [App\Http\Controllers\Admin::class,'add_company'])->name('add_company');
Route::post('/store_company', [App\Http\Controllers\Admin::class,'store_company'])->name('store_company');
Route::get('/edit_company/{id}', [App\Http\Controllers\Admin::class,'edit_company'])->name('edit_company');
Route::post('/update_company', [App\Http\Controllers\Admin::class,'update_company'])->name('update_company');
Route::get('/delete_company/{id}', [App\Http\Controllers\Admin::class,'delete_company'])->name('delete_company');
/* End Companies */


/* Tasks */
Route::get('/tasks', [App\Http\Controllers\Admin::class,'tasks'])->name('tasks');
Route::post('/store_task', [App\Http\Controllers\Admin::class,'store_task'])->name('store_task');
Route::post('/update_task', [App\Http\Controllers\Admin::class,'update_task'])->name('update_task');
Route::get('/delete_task/{id}', [App\Http\Controllers\Admin::class,'delete_task'])->name('delete_task');
/* End Tasks */

/* Enable or Disable crm */
Route::get('/crm_module/{user_id}/{val}', [App\Http\Controllers\Admin::class,'crm_module'])->name('crm_module');

/*
Agency Routes
 */

Route::get('/lite-agency/projects', [App\Http\Controllers\LiteAgency::class, 'index'])->name('lite-agency-dashboard');
Route::get('/lite-agency/artisan',function(){
    \Artisan::call('make:mail SendMail');
});
Route::get('/lite-agency/mail', [App\Http\Controllers\LiteAgency::class, 'mail'])->name('lite-agency-mail');
Route::post('/lite-agency/sendmail', [App\Http\Controllers\LiteAgency::class, 'sendmail'])->name('lite-agency-sendmail');
Route::get('/lite-agency/delete/account', [App\Http\Controllers\LiteAgency::class, 'deleteAccount'])->name('lite-agency-deleteAccount');
Route::get('/lite-agency/cancel/account', [App\Http\Controllers\LiteAgency::class, 'cancelAccount'])->name('lite-agency-cancelAccount');
Route::get('/lite-agency/search', [App\Http\Controllers\LiteAgency::class, 'search'])->name('lite-agency-search');
Route::post('/lite-agency/search/ajax', [App\Http\Controllers\LiteAgency::class, 'search_ajax'])->name('lite-agency-search-ajax');
Route::get('/lite-agency/newproject', [App\Http\Controllers\LiteAgency::class, 'new_project'])->name('lite-agency-newproject');
Route::get('/lite-agency/inbox/',[App\Http\Controllers\LiteAgency::class,'agency_inbox'])->name('lite-agency-inbox');
Route::get('/lite-agency/inbox/archived/',[App\Http\Controllers\LiteAgency::class,'agency_archived_inbox'])->name('lite-agency-inbox-archived');
Route::post('/lite-agency/inbox/archive',[App\Http\Controllers\LiteAgency::class,'unarchive_post'])->name('lite-unarchive-post');
Route::post('/lite-agency/inbox/unarchive',[App\Http\Controllers\LiteAgency::class,'archive_post'])->name('lite-archive-post');

Route::post('/lite-agency/inbox/deletechat',[App\Http\Controllers\LiteAgency::class,'delete_chat'])->name('lite-delete-chat');
Route::get('/lite-agency/profile/',[App\Http\Controllers\LiteAgency::class,'profile'])->name('lite-agency-profile');
Route::get('/lite-agency/clients/',[App\Http\Controllers\LiteAgency::class,'clients_search'])->name('lite-clients-search');
Route::get('/lite-agency/client/{id}',[App\Http\Controllers\LiteAgency::class,'delete_client'])->name('lite-delete-client');
Route::post('/lite-agency/profile/update',[App\Http\Controllers\LiteAgency::class,'profile_update'])->name('lite-agency-profile-update');
Route::get('/lite-agency/project/{id}',[App\Http\Controllers\LiteAgency::class,'project_show'])->name('lite-agency-project-show');
Route::get('/lite-agency/project/{id}/edit',[App\Http\Controllers\LiteAgency::class,'project_edit'])->name('lite-agency-project-edit');

/* Invoices */
Route::get('/lite-agency/invoices',[App\Http\Controllers\LiteAgency::class,'invoices'])->name('liteAgency_invoices');
Route::get('/lite-agency/addInvoice',[App\Http\Controllers\LiteAgency::class,'addInvoice'])->name('liteAgency_addInvoice');
Route::post('/lite-agency/addNewInvoice',[App\Http\Controllers\LiteAgency::class,'addNewInvoice'])->name('liteAgency_addNewInvoice');
Route::get('/lite-agency/editInvoice/{id}',[App\Http\Controllers\LiteAgency::class,'editInvoices'])->name('liteAgency_editInvoices');
Route::get('/lite-agency/copyInvoice/{id}',[App\Http\Controllers\LiteAgency::class,'copyInvoice'])->name('liteAgency_copyInvoice');
Route::get('/lite-agency/viewInvoice/{id}',[App\Http\Controllers\LiteAgency::class,'viewInvoice'])->name('liteAgency_viewInvoice');
Route::post('/lite-agency/saveInvoice',[App\Http\Controllers\LiteAgency::class,'saveInvoice'])->name('liteAgency_saveInvoice');
Route::get('/lite-agency/deleteInvoice/{id}',[App\Http\Controllers\LiteAgency::class,'deleteInvoice'])->name('liteAgency_deleteInvoice');
/*

/* Estimates */
Route::get('/lite-agency/estimates',[App\Http\Controllers\LiteAgency::class,'estimates'])->name('liteAgency_estimates');
Route::get('/lite-agency/addEstimate',[App\Http\Controllers\LiteAgency::class,'addEstimate'])->name('liteAgency_addEstimate');
Route::post('/lite-agency/addNewEstimate',[App\Http\Controllers\LiteAgency::class,'addNewEstimate'])->name('liteAgency_addNewEstimate');
Route::get('/lite-agency/editEstimate/{id}',[App\Http\Controllers\LiteAgency::class,'editEstimate'])->name('liteAgency_editEstimate');
Route::get('/lite-agency/copyEstimate/{id}',[App\Http\Controllers\LiteAgency::class,'copyEstimate'])->name('liteAgency_copyEstimate');
Route::get('/lite-agency/viewEstimate/{id}',[App\Http\Controllers\LiteAgency::class,'viewEstimate'])->name('liteAgency_viewEstimate');
Route::post('/lite-agency/saveEstimate',[App\Http\Controllers\LiteAgency::class,'saveEstimate'])->name('liteAgency_saveEstimate');
Route::get('/lite-agency/deleteEstimate/{id}',[App\Http\Controllers\LiteAgency::class,'deleteEstimate'])->name('liteAgency_deleteEstimate');
/* Estimates */

/* Purchase Order */
Route::get('/lite-agency/purchaseOrder',[App\Http\Controllers\LiteAgency::class,'purchaseOrder'])->name('liteAgency_purchaseOrder');
Route::get('/lite-agency/addPurchaseOrder',[App\Http\Controllers\LiteAgency::class,'addPurchaseOrder'])->name('liteAgency_addPurchaseOrder');
Route::post('/lite-agency/addNewPurchaseOrder',[App\Http\Controllers\LiteAgency::class,'addNewPurchaseOrder'])->name('liteAgency_addNewPurchaseOrder');
Route::get('/lite-agency/editPurchaseOrder/{id}',[App\Http\Controllers\LiteAgency::class,'editPurchaseOrder'])->name('liteAgency_editPurchaseOrder');
Route::get('/lite-agency/copyPurchaseOrder/{id}',[App\Http\Controllers\LiteAgency::class,'copyPurchaseOrder'])->name('liteAgency_copyPurchaseOrder');
Route::get('/lite-agency/viewPurchaseOrder/{id}',[App\Http\Controllers\LiteAgency::class,'viewPurchaseOrder'])->name('liteAgency_viewPurchaseOrder');
Route::post('/lite-agency/savePurchaseOrder',[App\Http\Controllers\LiteAgency::class,'savePurchaseOrder'])->name('liteAgency_savePurchaseOrder');
Route::get('/lite-agency/deletePurchaseOrder/{id}',[App\Http\Controllers\LiteAgency::class,'deleteEstimate'])->name('liteAgency_deleteEstimate');
/* Purchase Order */


/*
End Agency Routes
*/



/*
Client Routes
 */

Route::get('/client/projects', [App\Http\Controllers\Client::class, 'index'])->name('client-dashboard');
Route::get('/client/mail', [App\Http\Controllers\Client::class, 'mail'])->name('client-mail');
Route::post('/client/sendmail', [App\Http\Controllers\Client::class, 'sendmail'])->name('client-sendmail');
Route::get('/client/delete/account', [App\Http\Controllers\Client::class, 'deleteAccount'])->name('client-deleteAccount');
Route::get('/client/cancel/account', [App\Http\Controllers\Client::class, 'cancelAccount'])->name('client-cancelAccount');
Route::get('/client/search', [App\Http\Controllers\Client::class, 'search'])->name('client-search');
Route::get('/client/search/ajax', [App\Http\Controllers\Client::class, 'search_ajax'])->name('client-search-ajax');
Route::get('/client/newproject', [App\Http\Controllers\Client::class, 'new_project'])->name('client-newproject');
Route::get('/client/startchat/{id}',[App\Http\Controllers\Client::class,'start_chat'])->name('client-start-chat');
Route::get('/client/inbox/',[App\Http\Controllers\Client::class,'client_inbox'])->name('client-inbox');
Route::get('/client/profile/',[App\Http\Controllers\Client::class,'profile'])->name('client-profile');
Route::post('/client/profile/update',[App\Http\Controllers\Client::class,'profile_update'])->name('client-profile-update');

Route::get('/client/inbox/archived/',[App\Http\Controllers\Client::class,'client_archived_inbox'])->name('client-inbox-archived');
Route::post('/client/inbox/archive',[App\Http\Controllers\Client::class,'unarchive_post'])->name('client-unarchive-post');
Route::post('/client/inbox/unarchive',[App\Http\Controllers\Client::class,'archive_post'])->name('client-archive-post');

/* Invoices */
Route::get('/client/invoices',[App\Http\Controllers\Client::class,'client_invoices'])->name('client_invoices');
Route::get('/client/viewInvoice/{id}',[App\Http\Controllers\Client::class,'clientviewInvoice'])->name('clientviewInvoice');
Route::post('/client/payInvoice',[App\Http\Controllers\Client::class,'clientPayInvoice'])->name('clientPayInvoice');

/* Estimate */
Route::get('/client/estimates',[App\Http\Controllers\Client::class,'client_estimates'])->name('client_estimates');
Route::get('/client/viewEstimate/{id}',[App\Http\Controllers\Client::class,'clientviewEstimate'])->name('clientviewEstimate');
Route::post('/client/payEstimate',[App\Http\Controllers\Client::class,'clientPayEstimate'])->name('clientPayEstimate');
Route::get('/client/acceptEstimate/{id}',[App\Http\Controllers\Client::class,'clientAcceptEstimate'])->name('clientAcceptEstimate');

/*
End Client Routes
*/

/*
Chat Routes
 */

Route::get('/chat/{id}/',[App\Http\Controllers\ChatController::class,'index'])->name('chat');

Route::get('/chats/backup/',[App\Http\Controllers\ChatController::class,'backup'])->name('chat');


Route::post('/chat/ajax/{currentAgencyId}',[App\Http\Controllers\ChatController::class,'chat_ajax'])->name('chat-ajax');
Route::post('/chat/ajax',[App\Http\Controllers\ChatController::class,'chat_ajax'])->name('chat-ajax');
Route::get('chat-edit/{id}',[App\Http\Controllers\ChatController::class,'chat_edit'])->name('chat-edit');
Route::post('/chat/update',[App\Http\Controllers\ChatController::class,'chat_update'])->name('chat-update');
/*
End Chat Routes
 */

/*
Agency Routes
 */

Route::get('/agency/projects', [App\Http\Controllers\Agency::class, 'index'])->name('agency-dashboard');
Route::get('/agency/mail', [App\Http\Controllers\Agency::class, 'mail'])->name('agency-mail');
Route::post('/agency/sendmail', [App\Http\Controllers\Agency::class, 'sendmail'])->name('agency-sendmail');
Route::get('/agency/delete/account', [App\Http\Controllers\Agency::class, 'deleteAccount'])->name('agency-deleteAccount');
Route::get('/agency/cancel/account', [App\Http\Controllers\Agency::class, 'cancelAccount'])->name('agency-cancelAccount');
Route::get('/agency/search', [App\Http\Controllers\Agency::class, 'search'])->name('agency-search');
Route::post('/agency/search/ajax', [App\Http\Controllers\Agency::class, 'search_ajax'])->name('agency-search-ajax');
Route::get('/agency/newproject', [App\Http\Controllers\Agency::class, 'new_project'])->name('agency-newproject');
Route::get('/agency/inbox/',[App\Http\Controllers\Agency::class,'agency_inbox'])->name('agency-inbox');
Route::get('/agency/inbox/archived/',[App\Http\Controllers\Agency::class,'agency_archived_inbox'])->name('agency-inbox-archived');
Route::post('/agency/inbox/archive',[App\Http\Controllers\Agency::class,'unarchive_post'])->name('unarchive-post');
Route::post('/agency/inbox/unarchive',[App\Http\Controllers\Agency::class,'archive_post'])->name('archive-post');

Route::post('/agency/inbox/deletechat',[App\Http\Controllers\Agency::class,'delete_chat'])->name('delete-chat');
Route::get('/agency/profile/',[App\Http\Controllers\Agency::class,'profile'])->name('agency-profile');
Route::get('/agency/clients/',[App\Http\Controllers\Agency::class,'clients_search'])->name('clients-search');
Route::get('/agency/client/{id}',[App\Http\Controllers\Agency::class,'delete_client'])->name('delete-client');
Route::post('/agency/profile/update',[App\Http\Controllers\Agency::class,'profile_update'])->name('agency-profile-update');

/* Invoices */
Route::get('/agency/invoices',[App\Http\Controllers\Agency::class,'invoices'])->name('invoices');
Route::get('/agency/addInvoice',[App\Http\Controllers\Agency::class,'addInvoice'])->name('addInvoice');
Route::post('/agency/addNewInvoice',[App\Http\Controllers\Agency::class,'addNewInvoice'])->name('addNewInvoice');
Route::get('/agency/editInvoice/{id}',[App\Http\Controllers\Agency::class,'editInvoices'])->name('editInvoices');
Route::get('/agency/viewInvoice/{id}',[App\Http\Controllers\Agency::class,'viewInvoice'])->name('viewInvoice');
Route::get('/agency/copyInvoice/{id}',[App\Http\Controllers\Agency::class,'copyInvoice'])->name('agencyCopyInvoice');
Route::post('/agency/saveInvoice',[App\Http\Controllers\Agency::class,'saveInvoice'])->name('saveInvoice');
Route::get('/agency/deleteInvoice/{id}',[App\Http\Controllers\Agency::class,'deleteInvoice'])->name('deleteInvoice');
/*

/* Estimates */
Route::get('/agency/estimates',[App\Http\Controllers\Agency::class,'estimates'])->name('estimates');
Route::get('/agency/addEstimate',[App\Http\Controllers\Agency::class,'addEstimate'])->name('addEstimate');
Route::post('/agency/addNewEstimate',[App\Http\Controllers\Agency::class,'addNewEstimate'])->name('addNewEstimate');
Route::get('/agency/editEstimate/{id}',[App\Http\Controllers\Agency::class,'editEstimate'])->name('editEstimate');
Route::get('/agency/copyEstimate/{id}',[App\Http\Controllers\Agency::class,'copyEstimate'])->name('agencyCopyEstimate');
Route::get('/agency/viewEstimate/{id}',[App\Http\Controllers\Agency::class,'viewEstimate'])->name('viewEstimate');
Route::post('/agency/saveEstimate',[App\Http\Controllers\Agency::class,'saveEstimate'])->name('saveEstimate');
Route::get('/agency/deleteEstimate/{id}',[App\Http\Controllers\Agency::class,'deleteEstimate'])->name('deleteEstimate');
/* Estimates */

/* Purchase Order */
Route::get('/agency/purchaseOrder',[App\Http\Controllers\Agency::class,'purchaseOrder'])->name('purchaseOrder');
Route::get('/agency/addPurchaseOrder',[App\Http\Controllers\Agency::class,'addPurchaseOrder'])->name('addPurchaseOrder');
Route::post('/agency/addNewPurchaseOrder',[App\Http\Controllers\Agency::class,'addNewPurchaseOrder'])->name('addNewPurchaseOrder');
Route::get('/agency/editPurchaseOrder/{id}',[App\Http\Controllers\Agency::class,'editPurchaseOrder'])->name('editPurchaseOrder');
Route::get('/agency/copyPurchaseOrder/{id}',[App\Http\Controllers\Agency::class,'copyPurchaseOrder'])->name('agencyCopyPurchaseOrder');
Route::get('/agency/viewPurchaseOrder/{id}',[App\Http\Controllers\Agency::class,'viewPurchaseOrder'])->name('viewPurchaseOrder');
Route::post('/agency/savePurchaseOrder',[App\Http\Controllers\Agency::class,'savePurchaseOrder'])->name('savePurchaseOrder');
Route::get('/agency/deletePurchaseOrder/{id}',[App\Http\Controllers\Agency::class,'deletePurchaseOrder'])->name('deletePurchaseOrder');
/* Purchase Order */

Route::post('/changeCurrency/{id}',[App\Http\Controllers\Agency::class,'changeCurrency'])->name('changeCurrency');
Route::post('/changeProject/{id}',[App\Http\Controllers\Agency::class,'changeProject'])->name('changeProject');
Route::get('/changePaymentStatus/{id}/{status}',[App\Http\Controllers\Agency::class,'changePaymentStatus'])->name('changePaymentStatus');
Route::post('/assignEstimates/{id}',[App\Http\Controllers\Agency::class,'assignEstimates'])->name('assignEstimates');
Route::post('/assignInvoices/{id}',[App\Http\Controllers\Agency::class,'assignInvoices'])->name('assignInvoices');
Route::get('/convertToEstimate/{id}',[App\Http\Controllers\Agency::class,'convertToEstimate'])->name('convertToEstimate');
Route::get('/convertToPos/{id}',[App\Http\Controllers\Agency::class,'convertToPos'])->name('convertToPos');
Route::get('/markPaid/{item}/{user}',[App\Http\Controllers\Agency::class,'markPaid'])->name('markPaid');
Route::post('/updateCompany/{id}', [App\Http\Controllers\Agency::class,'updateCompany'])->name('updateCompany');

Route::get('/changeAssignStatus/{id}/{status}',[App\Http\Controllers\Agency::class,'changeAssignStatus'])->name('changeAssignStatus');
Route::get('/changeAcceptStatus/{id}/{status}',[App\Http\Controllers\Agency::class,'changeAcceptStatus'])->name('changeAcceptStatus');

/*
End Agency Routes
*/

Route::resource('projects', App\Http\Controllers\ProjectsController::class);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/download',[App\Http\Controllers\HomeController::class, 'filedownload'])->name('download');
Route::post('/add-mail-media',[App\Http\Controllers\HomeController::class, 'mailMedia'])->name('add-mail-media');


/* Route for Bookkeeping */
Route::get('/bookkeeping', [App\Http\Controllers\BookkeepingController::class, 'index'])->name('bookkeeping');
Route::post('/add_bank', [App\Http\Controllers\BookkeepingController::class, 'add_bank'])->name('add_bank');
Route::get('/detail_bank/{id}', [App\Http\Controllers\BookkeepingController::class, 'detail_bank'])->name('detail_bank');
Route::post('/update_bank/{id}', [App\Http\Controllers\BookkeepingController::class, 'update_bank'])->name('update_bank');
Route::get('/delete_bank/{id}', [App\Http\Controllers\BookkeepingController::class, 'delete_bank'])->name('delete_bank');

/* Bank categories */
Route::post('/add_bank_cat', [App\Http\Controllers\BookkeepingController::class, 'add_bank_cat'])->name('add_bank_cat');
Route::post('/edit_categories_update/{id}', [App\Http\Controllers\BookkeepingController::class, 'edit_categories_update'])->name('edit_categories_update');
Route::get('/delete_categories/{id}', [App\Http\Controllers\BookkeepingController::class, 'delete_categories'])->name('delete_categories');

/* Bank Details */
Route::post('/add_bank_detail', [App\Http\Controllers\BookkeepingController::class, 'add_bank_detail'])->name('add_bank_detail');
Route::get('/delete_bank_detail/{id}', [App\Http\Controllers\BookkeepingController::class, 'delete_bank_detail'])->name('delete_bank_detail');
Route::post('/update_bank_detail/{id}', [App\Http\Controllers\BookkeepingController::class, 'update_bank_detail'])->name('update_bank_detail');

/* Attachments */
Route::get('/attachments/{contact}', [App\Http\Controllers\BookkeepingController::class, 'attachments'])->name('attachments');
Route::post('/attachments/add/{contact}', [App\Http\Controllers\BookkeepingController::class, 'add_attachment'])->name('add_attachment');
Route::get('/attachments/delete/{id}', [App\Http\Controllers\BookkeepingController::class, 'delete_attachment'])->name('delete_attachment');

/* Reciept */
Route::get('/reciept/{id}', [App\Http\Controllers\Client::class, 'reciept'])->name('reciept');

//  Accounting 
Route::get('/accounting',[accountingController::class,'index'])->name('accounting');
Route::get('/balance',[accountingController::class, 'balance'])->name('balance');
Route::get('/events',[accountingController::class,'events'])->name('events');
Route::get('/expenses',[accountingController::class,'expenses'])->name('expenses');
Route::get('/filling',[accountingController::class,'filling'])->name('filling');
Route::get('/payments',[accountingController::class,'payments'])->name('payments');
Route::get('/reconciliation',[accountingController::class,'reconciliation'])->name('reconciliation');
Route::get('/margin',[accountingController::class,'margin'])->name('margin');
Route::get('/work_force',[accountingController::class,'work_force'])->name('work_force');
Route::get('/sales',[accountingController::class,'sales'])->name('sales');
Route::get('/trips',[accountingController::class,'trips'])->name('trips');
Route::get('/p_and_L',[accountingController::class,'p_and_L'])->name('p_and_L');
Route::get('/vat',[accountingController::class,'vat'])->name('vat');

//vat filterr
Route::post('/filter_vat_month',[accountingController::class,'filter_vat_month'])->name('filter_vat_month');
Route::post('/filter_vat_year',[accountingController::class,'filter_vat_year'])->name('filter_vat_year');

Route::post('/filter_pl_month',[accountingController::class,'filter_pl_month'])->name('filter_pl_month');
Route::post('/filter_pl_year',[accountingController::class,'filter_pl_year'])->name('filter_pl_year');
//balance
Route::post('/add_balance',[accountingController::class,'add_balance'])->name('add_balance');
Route::post('/changeBalCurrency/{id}',[accountingController::class,'changeBalCurrency'])->name('changeBalCurrency');
Route::get('/updatebalance/{id}',[accountingController::class,'updatebalance'])->name('updatebalance');
Route::post('/saveupdatebalance',[accountingController::class,'saveupdatebalance'])->name('saveupdatebalance');
Route::get('/deletebalance/{id}',[accountingController::class,'daletebalance'])->name('daletebalance');

////event
Route::post('/add_event',[accountingController::class,'add_event'])->name('add_event');
Route::get('/updateevent/{id}',[accountingController::class,'updateevent'])->name('updateevent');
Route::post('/saveupdateevent',[accountingController::class,'saveupdateevent'])->name('saveupdateevent');
Route::get('/deleteevent/{id}',[accountingController::class,'deleteevent'])->name('deleteevent');
Route::post('/changeEventCurrency/{id}',[accountingController::class,'changeEventCurrency'])->name('changeEventCurrency');

////expenses
Route::post('/add_expenses',[accountingController::class,'add_expenses'])->name('add_expenses');
Route::get('/updateexpenses/{id}',[accountingController::class,'updateexpenses'])->name('updateexpenses');
Route::post('/saveupdateexpenses',[accountingController::class,'saveupdateexpenses'])->name('saveupdateexpenses');
Route::get('/deleteexpenses/{id}',[accountingController::class,'deleteexpenses'])->name('deleteexpenses');
Route::post('/changeExpenseCurrency/{id}',[accountingController::class,'changeExpenseCurrency'])->name('changeExpenseCurrency');

////fillings
Route::post('/add_filling',[accountingController::class,'add_filling'])->name('add_filling');
Route::get('/updatefilling/{id}',[accountingController::class,'updatefilling'])->name('updatefilling');
Route::post('/saveupdatefilling',[accountingController::class,'saveupdatefilling'])->name('saveupdatefilling');
Route::get('/deletefilling/{id}',[accountingController::class,'deletefilling'])->name('deletefilling');

Route::post('/changeFillingCurrency/{id}',[accountingController::class,'changeFillingCurrency'])->name('changeFillingCurrency');

////payments
Route::post('/add_payment',[accountingController::class,'add_payment'])->name('add_payment');
Route::get('/updatepayments/{id}',[accountingController::class,'updatepayments'])->name('updatepayments');
Route::post('/saveupdatepayments',[accountingController::class,'saveupdatepayments'])->name('saveupdatepayments');
Route::get('/deletepayment/{id}',[accountingController::class,'deletepayment'])->name('deletepayment');
Route::post('/changePaymentCurrency/{id}',[accountingController::class,'changePaymentCurrency'])->name('changePaymentCurrency');

////trpis
Route::post('/add_trip',[accountingController::class,'add_trip'])->name('add_trip');
Route::get('/updatetrip/{id}',[accountingController::class,'updatetrip'])->name('updatetrip');
Route::post('/saveupdatetrip',[accountingController::class,'saveupdatetrip'])->name('saveupdatetrip');
Route::get('/deletetrip/{id}',[accountingController::class,'deletetrip'])->name('deletetrip');
Route::post('/changeTripCurrency/{id}',[accountingController::class,'changeTripCurrency'])->name('changeTripCurrency');

////workforce
Route::post('/add_workforce',[accountingController::class,'add_workforce'])->name('add_workforce');
Route::get('/updateworkforce/{id}',[accountingController::class,'updateworkforce'])->name('updateworkforce');
Route::post('/saveupdateworkforce',[accountingController::class,'saveupdateworkforce'])->name('saveupdateworkforce');
Route::get('/deleteworkforce/{id}',[accountingController::class,'deleteworkforce'])->name('deleteworkforce');
Route::post('/changeWorkCurrency/{id}',[accountingController::class,'changeWorkCurrency'])->name('changeWorkCurrency');

////reconciliation
Route::post('/add_reconciliation',[accountingController::class,'add_reconciliation'])->name('add_reconciliation');
Route::get('/updatereconcilation/{id}',[accountingController::class,'updatereconcilation'])->name('updatereconcilation');
Route::post('/saveupdatereconcilation',[accountingController::class,'saveupdatereconcilation'])->name('saveupdatereconcilation');
Route::get('/deletereconciliation/{id}',[accountingController::class,'deletereconciliation'])->name('deletereconciliation');
Route::post('/changeReconCurrency/{id}',[accountingController::class,'changeReconCurrency'])->name('changeReconCurrency');

////sales
Route::post('/add_sales',[accountingController::class,'add_sales'])->name('add_sales');
Route::get('/updatesales/{id}',[accountingController::class,'updatesales'])->name('updatesales');
Route::post('/saveupdatesales',[accountingController::class,'saveupdatesales'])->name('saveupdatesales');
Route::get('/deletesale/{id}',[accountingController::class,'deletesale'])->name('deletesale');
Route::post('/changeSaleCurrency/{id}',[accountingController::class,'changeSaleCurrency'])->name('changeSaleCurrency');

Route::post('/filter_sale_month',[accountingController::class,'filter_sale_month'])->name('filter_sale_month');
Route::post('/filter_sale_year',[accountingController::class,'filter_sale_year'])->name('filter_sale_year');

Route::post('/filter_trip_month',[accountingController::class,'filter_trip_month'])->name('filter_trip_month');
Route::post('/filter_trip_year',[accountingController::class,'filter_trip_year'])->name('filter_trip_year');

Route::post('/filter_balance_month',[accountingController::class,'filter_balance_month'])->name('filter_balance_month');
Route::post('/filter_balance_year',[accountingController::class,'filter_balance_year'])->name('filter_balance_year');


//Departments
Route::get('/departments',[departmentsController::class,'departments_view'])->name('departments');
Route::get('/add_view',[departmentsController::class,'add_view'])->name('add_view');

Route::post('/add_departments',[departmentsController::class,'add_departments'])->name('add_departments');
Route::get('/view_note_temp',[departmentsController::class,'Note_view'])->name('view_note_temp');
Route::post('/create_main_temp',[departmentsController::class,'create_main_temp'])->name('create_main_temp');
Route::post('/create_note_temp',[departmentsController::class,'create_note_temp'])->name('create_note_temp');
Route::get('/updatemaintemp/{id}',[departmentsController::class,'updatemaintemp'])->name('updatemaintemp');
Route::post('/updatesavemaintemp',[departmentsController::class,'updatesavemaintemp'])->name('updatesavemaintemp');
Route::get('/updatenotetemp/{id}',[departmentsController::class,'updatenotetemp'])->name('updatenotetemp');
Route::post('/updatesavenotetemp',[departmentsController::class,'updatesavenotetemp'])->name('updatesavenotetemp');
Route::get('/deletemaintemp/{id}',[departmentsController::class,'deletemaintemp'])->name('deletemaintemp');
Route::get('/deletenotetemp/{id}',[departmentsController::class,'deletenotetemp'])->name('deletenotetemp');
// Route::get('/updatenotetemp/{id}',[departmentsController::class,'updatenotetemp'])->name('updatenotetemp');
Route::get('/updatedepartments/{id}',[departmentsController::class,'updatedepartments'])->name('updatedepartments');
Route::post('/saveupdatedepartments',[departmentsController::class,'saveupdatedepartments'])->name('saveupdatedepartments');
Route::get('/delete_departments/{id}',[departmentsController::class,'delete_departments'])->name('delete_departments');
Route::get('/inside_departments/{id}',[departmentsController::class,'inside_departments'])->name('inside_departments');

Route::POST('/departmentChangeTheme/{id}',[departmentsController::class,'departmentChangeTheme'])->name('departmentChangeTheme');
Route::get('/defaultUi',[departmentsController::class,'defaultUi'])->name('defaultUi');




