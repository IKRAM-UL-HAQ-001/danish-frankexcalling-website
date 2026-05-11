<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\PhoneNumberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerCareController;
use App\Http\Controllers\AssignNumberController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DemoSendController;
use App\Http\Controllers\RejectController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\WalkController;
use App\Http\Controllers\ReferIdController;
use App\Http\Controllers\TotalCallController;
use App\Http\Controllers\NoOfCallController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TotalAmountController;
use App\Http\Controllers\NewIdController;
use App\Http\Controllers\IpAddressController;
use App\Http\Controllers\QuaterlyReportController;
use App\Http\Controllers\DatabaseExportController;
use App\Http\Controllers\DataEntryController;
use App\Http\Controllers\RejoinController;
use App\Http\Controllers\UploadFileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\DeveloperController;



// Developer Routes
// Route::get('/developer', [DeveloperController::class, 'index'])->name('developer.main');
// Route::get('/api/get-phone-numbers', [DeveloperController::class, 'getPhoneNumbers']);
// Route::post('/api/update-phone-numbers', [DeveloperController::class, 'updatePhoneNumbers']);
// Route::get('/api/update-test-entries', [DeveloperController::class, 'updatetest'])->name('updatetest');
// Route::get('/api/correct-format', [DeveloperController::class, 'updatePhoneNumbers'])->name('correct-format');



Route::get('/', [LoginController::class, 'index'])->name('auth.login');
Route::post('/post', [LoginController::class, 'login'])->name('login.post');
Route::get('/auth/logout', [LoginController::class, 'logout'])->name('login.logout');

//Excel
Route::post('/export-complaint', [ComplaintController::class, 'exportComplaint'])->name('export.complaint');
Route::get('/export-quaterly-report', [QuaterlyReportController::class, 'exportQuaterlyReport'])->name('export.quaterly');    
Route::post('/export-demoSend', [DemoSendController::class, 'exportDemoSend'])->name('export.demoSend');
Route::post('/export-followUp', [FollowUpController::class, 'exportFollowUp'])->name('export.followUp');    
Route::post('/export-newId', [NewIdController::class, 'exportNewId'])->name('export.newId');    
Route::post('/export-ReferId', [ReferIdController::class, 'exportReferId'])->name('export.referId');
Route::post('/export-Reject', [RejectController::class, 'exportReject'])->name('export.reject');
Route::post('/export-Walk', [WalkController::class, 'exportWalk'])->name('export.walk');
Route::post('/export-Rejoin', [RejoinController::class, 'exportRejoin'])->name('export.rejoin');
Route::post('/export-active-phoneNumbers', [PhoneNumberController::class, 'exportActivePhoneNumbers'])->name('export.activePhoneNumbers');

Route::post('/users', [UserController::class, 'getUsers'])->name('getusers');
Route::group(['middleware' => ['admin']], function () {

    Route::get('/admin/search/DataEntry', [SearchController::class, 'index'])->name('admin.search.list');
    Route::post('/admin/searchDataEntry/post', [SearchController::class, 'searchDataEntry'])->name('admin.searchDataEntry.post');
    Route::post('/admin/searchDataEntry/delete', [SearchController::class, 'deleteDataEntry'])->name('admin.DataEntry.delete');
    Route::post('/admin/searchPhoneEntry/delete', [SearchController::class, 'deletePhoneEntry'])->name('admin.deletePhoneEntry.post');
    
    Route::get('/admin/database', [DatabaseExportController::class, 'downloadDatabase'])->name('database.export');

    Route::get('/admin/uploadedFiles', [UploadFileController::class, 'index'])->name('admin.upload_files.list');
    Route::post('/admin/uploadedFiles/formPost', [UploadFileController::class, 'store'])->name('admin.upload_files.formPost');
    Route::post('/admin/uploadedFiles/getFile', [UploadFileController::class, 'getFile'])->name('admin.upload_files.getFile');
    Route::post('/admin/uploadedFiles/delete', [UploadFileController::class, 'destroy'])->name('admin.upload_files.delete');
    Route::post('/admin/uploadedFiles/view', [UploadFileController::class, 'displayFileData'])->name('admin.upload_files.view');

    Route::Post('/admin/userPerformance', [UserController::class, 'userPerformance'])->name('admin.user.performance');    

    Route::post('/admin/passwordUpdate', [LoginController::class, 'update'])->name('password.update');
    Route::get('/admin/password/post', [LoginController::class, 'logoutAll'])->name('logout.all');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/exchange', [ExchangeController::class, 'index'])->name('admin.exchange.list');
    Route::post('/admin/exchangeUsers', [ExchangeController::class, 'exchnageUsers'])->name('admin.exchange.userlist');
    Route::post('/admin/exchange/post', [ExchangeController::class, 'store'])->name('admin.exchange.formPost');
    Route::post('/admin/exchange/popStore', [ExchangeController::class, 'popDashboard'])->name('admin.exchange.popUpDashboard');
    Route::post('/admin/exchange/delete', [ExchangeController::class, 'destroy'])->name('admin.exchange.delete');
    Route::post('/admin/exchange/update', [ExchangeController::class, 'update'])->name('admin.exchange.update');

    Route::get('/admin/phoneNumber', [PhoneNumberController::class, 'index'])->name('admin.phone_number.list');
    Route::post('/admin/phoneNumber/delete', [PhoneNumberController::class, 'destroy'])->name('admin.phone_number.delete');
    Route::post('/admin/phoneNumber/Customdelete', [PhoneNumberController::class, 'custom_delete'])->name('admin.phone_number.custom_delete');
    Route::post('/admin/phoneNumber/search-phone', [PhoneNumberController::class, 'searchPhoneNumber'])->name('admin.phone_number.search');
    
    Route::get('/admin/numberOfCall', [NoOfCallController::class, 'index'])->name('admin.no_of_call.list');
    Route::post('/admin/numberOfCall/delete', [NoOfCallController::class, 'destroy'])->name('admin.no_of_call.delete');

    Route::get('/admin/user', [UserController::class, 'index'])->name('admin.user.list');    
    Route::post('/admin/user/status', [UserController::class, 'userStatus'])->name('admin.user.status');
    Route::post('/admin/user/delete', [UserController::class, 'destroy'])->name('admin.user.delete');
    Route::post('/admin/user/post', [UserController::class, 'store'])->name('admin.user.formPost');
    Route::post('/admin/user/update', [UserController::class, 'update'])->name('admin.user.update');
    Route::post('/admin/user/IPallow', [UserController::class, 'ip_allow'])->name('admin.user.ip_allow');

    Route::get('/admin/customerCare', [CustomerCareController::class, 'index'])->name('admin.customer_care.exchangelist');
    Route::post('/admin/customerCareUserlist', [CustomerCareController::class, 'userlist'])->name('admin.customer_care.list');
    Route::post('/admin/customerCare/popStore', [CustomerCareController::class, 'popDashboard'])->name('admin.customer_care.popUpDashboard');
    Route::post('/admin/customerCare/delete', [CustomerCareController::class, 'destroy'])->name('admin.customer_care.delete');
    Route::post('/admin/customerCare/post', [CustomerCareController::class, 'store'])->name('admin.customer_care.formPost');
    Route::post('/admin/customerCare/update', [CustomerCareController::class, 'update'])->name('admin.customer_care.update');    


    Route::get('/admin/demoSend', [DemoSendController::class, 'index'])->name('admin.demo_send.list');
    Route::post('/admin/demoSend/post', [DemoSendController::class, 'destroy'])->name('admin.demo_send.delete');

    Route::get('/admin/complaint', [ComplaintController::class, 'index'])->name('admin.complaint.list');
    Route::post('/admin/complaint/post', [ComplaintController::class, 'destroy'])->name('admin.complaint.delete');

    Route::get('/admin/followup', [FollowUpController::class, 'index'])->name('admin.follow_up.list');
    Route::post('/admin/followup/post', [FollowUpController::class, 'destroy'])->name('admin.follow_up.delete');

    Route::get('/admin/reject', [RejectController::class, 'index'])->name('admin.reject.list');
    Route::post('/admin/reject/post', [RejectController::class, 'destroy'])->name('admin.reject.delete');

    Route::get('/admin/NewId', [NewIdController::class, 'index'])->name('admin.new_id.list');
    Route::post('/admin/NewId/post', [NewIdController::class, 'destroy'])->name('admin.New_id.delete');

    Route::get('/admin/referId', [ReferIdController::class, 'index'])->name('admin.refer_id.list');
    Route::post('/admin/referId/post', [ReferIdController::class, 'destroy'])->name('admin.refer_id.delete');

    Route::get('/admin/walk', [WalkController::class, 'index'])->name('admin.walk.list');
    Route::post('/admin/walk/post', [WalkController::class, 'destroy'])->name('admin.walk.delete');

    Route::get('/admin/rejoin', [RejoinController::class, 'index'])->name('admin.rejoin.list');
    Route::post('/admin/rejoin/post', [RejoinController::class, 'destroy'])->name('admin.rejoin.delete');

    Route::post('/admin/phoneNumber/filePost', [PhoneNumberController::class, 'fileStore'])->name('admin.phone_number.filePost');
    Route::post('/admin/phoneNumber/formPost', [PhoneNumberController::class, 'formStore'])->name('admin.phone_number.formPost');

    Route::get('/admin/QuaterlyReport', [QuaterlyReportController::class, 'index'])->name('admin.quaterly_report.list');

    Route::post('/admin/dataEntry/post', [DataEntryController::class, 'update'])->name('admin.data_entry.update');

    Route::get('/run', [PhoneNumberController::class, 'run'])->name('run');
});

Route::group(['middleware' => ['assistant']], function () {
    Route::get('/assistant', [DashboardController::class, 'assistantIndex'])->name('assistant.dashboard');
    
    Route::get('/assistant/search/DataEntry', [SearchController::class, 'assistantIndex'])->name('assistant.search.list');
    Route::post('/assistant/searchDataEntry/post', [SearchController::class, 'searchDataEntry'])->name('assistant.searchDataEntry.post');
    
    Route::get('/assistant/Exchange', [ExchangeController::class, 'assistantIndex'])->name('assistant.exchange.list');
    Route::get('/assistant/PhoneNumber', [PhoneNumberController::class, 'assistantIndex'])->name('assistant.phone_number.list');
    Route::post('/assistant/phoneNumber/filePost', [PhoneNumberController::class, 'fileStore'])->name('assistant.phone_number.filePost');
    Route::post('/assistant/phoneNumber/formPost', [PhoneNumberController::class, 'formStore'])->name('assistant.phone_number.formPost');
    Route::post('/assistant/uploadedFiles/formPost', [UploadFileController::class, 'store'])->name('assistant.upload_files.formPost');
    Route::post('/assistant/uploadedFiles/getFile', [UploadFileController::class, 'getFile'])->name('assistant.upload_files.getFile');
    Route::get('/assistant/NumberOfCall', [NoOfCallController::class, 'assistantIndex'])->name('assistant.no_of_call.list');
    Route::get('/assistant/User', [UserController::class, 'assistantIndex'])->name('assistant.user.list');
    Route::get('/assistant/DemoSend', [DemoSendController::class, 'assistantIndex'])->name('assistant.demo_send.list');
    Route::get('/assistant/AssignNumber', [AssignNumberController::class, 'assistantIndex'])->name('assistant.assign_number.list');
    Route::post('/assistant/phoneNumber/search-phone', [PhoneNumberController::class, 'searchPhoneNumber'])->name('assistant.phone_number.search');

    Route::get('/assistant/Complaint', [ComplaintController::class, 'assistantIndex'])->name('assistant.complaint.list');
    Route::get('/assistant/Followup', [FollowUpController::class, 'assistantIndex'])->name('assistant.follow_up.list');
    Route::get('/assistant/Reject', [RejectController::class, 'assistantIndex'])->name('assistant.reject.list');
    Route::get('/assistant/Rejoin', [RejoinController::class, 'assistantIndex'])->name('assistant.rejoin.list');
    Route::get('/assistant/ReferId', [ReferIdController::class, 'assistantIndex'])->name('assistant.refer_id.list');
    Route::get('/assistant/Walk', [WalkController::class, 'assistantIndex'])->name('assistant.walk.list');
    Route::get('/assistant/NewId', [NewIdController::class, 'assistantindex'])->name('assistant.new_id.list');
});

Route::group(['middleware' => ['exchange']], function () {
    Route::get('/user/Dashboard', [DashboardController::class, 'exchangeIndex'])->name('exchange.dashboard');
    
    Route::get('/user/search/DataEntry', [SearchController::class, 'exchangeIndex'])->name('exchange.search.list');
    Route::post('/user/searchDataEntry/post', [SearchController::class, 'searchDataEntry'])->name('exchange.searchDataEntry.post');
    
    Route::get('/user/NumberOfCall', [NoOfCallController::class, 'exchangeIndex'])->name('exchange.no_of_call.list');
    Route::get('/user/AssignNumber', [AssignNumberController::class, 'exchangeIndex'])->name('exchange.assign_number.list');

    Route::get('/user/DemoSend', [DemoSendController::class, 'exchangeIndex'])->name('exchange.demo_send.list');

    Route::get('/user/Complaint', [ComplaintController::class, 'exchangeIndex'])->name('exchange.complaint.list');

    Route::get('/user/Followup', [FollowUpController::class, 'exchangeIndex'])->name('exchange.follow_up.list');

    Route::get('/user/Reject', [RejectController::class, 'exchangeIndex'])->name('exchange.reject.list');

    Route::get('/user/ReferId', [ReferIdController::class, 'exchangeIndex'])->name('exchange.refer_id.list');

    Route::get('/user/Walk', [WalkController::class, 'exchangeIndex'])->name('exchange.walk.list');

    Route::get('/user/Customer', [NewIdController::class, 'exchangeIndex'])->name('exchange.new_id.list');

    Route::post('/user/dataEntry/post', [DataEntryController::class, 'store'])->name('exchange.data_entry.post');
    Route::post('/user/dataEntry/phoneNumber', [DataEntryController::class, 'getPhoneId'])->name('exchange.data_entry.phoneNumber');
});

Route::group(['middleware' => [ 'customercare']], function () {
    Route::get('/customercare/Dashboard', [DashboardController::class, 'customercareIndex'])->name('customer_care.dashboard');
    
    Route::get('/customer_care/search/DataEntry', [SearchController::class, 'customercareIndex'])->name('customer_care.search.list');
    Route::post('/customer_care/searchDataEntry/post', [SearchController::class, 'searchDataEntry'])->name('customer_care.searchDataEntry.post');
    
    Route::get('/customercare/NumberOfCall', [NoOfCallController::class, 'customercareIndex'])->name('customer_care.no_of_call.list');
    Route::get('/customercare/AssignNumber', [AssignNumberController::class, 'customercareIndex'])->name('customer_care.assign_number.list');

    Route::get('/customercare/DemoSend', [DemoSendController::class, 'customercareIndex'])->name('customer_care.demo_send.list');

    Route::get('/customercare/Complaint', [ComplaintController::class, 'customercareIndex'])->name('customer_care.complaint.list');

    Route::get('/customercare/Followup', [FollowUpController::class, 'customercareIndex'])->name('customer_care.follow_up.list');

    Route::get('/customercare/Reject', [RejectController::class, 'customercareIndex'])->name('customer_care.reject.list');

    Route::get('/customercare/ReferId', [ReferIdController::class, 'customercareIndex'])->name('customer_care.refer_id.list');

    Route::get('/customercare/Walk', [WalkController::class, 'customercareIndex'])->name('customer_care.walk.list');
    Route::post('/customercare/dataEntry/phoneNumber', [DataEntryController::class, 'getPhoneId'])->name('customercare.data_entry.phoneNumber');


    Route::get('/customercare/NewId', [NewIdController::class, 'customercareIndex'])->name('customer_care.new_id.list');

    Route::post('/customercare/dataEntry/post', [DataEntryController::class, 'store'])->name('customer_care.data_entry.post');

    Route::get('/customercare/Rejoin', [RejoinController::class, 'CustomercareIndex'])->name('customer_care.rejoin.list');
});




Route::get('/enter-otp', [IpAddressController::class, 'showOtpForm'])->name('otp.form');

Route::post('/verify-otp', [IpAddressController::class, 'verifyAndLogIp'])->name('otp.verify');