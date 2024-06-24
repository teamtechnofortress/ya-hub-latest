<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectsController;
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
Route::get('/admin/lite-agency/emailModule/{id}/{value}',[App\Http\Controllers\Admin::class,'liteagencyEmailSetting']);

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
/*
End Agency Routes
*/

Route::resource('projects', App\Http\Controllers\ProjectsController::class);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/download',[App\Http\Controllers\HomeController::class, 'filedownload'])->name('download');
Route::post('/add-mail-media',[App\Http\Controllers\HomeController::class, 'mailMedia'])->name('add-mail-media');
