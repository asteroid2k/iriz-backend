<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/info',function (Request $request){
    return ["Monitor Student Attendance","Grade Attendance in one click","Easy and appealing UI"];
});
Route::post('signuptutor', 'SignupController@registerTutor');
Route::post('signupstudent', 'SignupController@registerStudent');
Route::post('login', 'SessionController@login');

Route::post('/resetforgot', 'PasswordResetController@resetForgot');
Route::post('forgotpassword', 'PasswordResetController@forgotMailer');
Route::get('verify/{email}','EmailController@verifyMail');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('studentinfo/{id}','AccountController@getStudentInfo');
    Route::post('changepassword', 'PasswordResetController@changePassword');
    Route::post('editdetails', 'AccountController@editDetails');

    //ATTENDANCE DATA
    Route::get('attendancedata/{code}','AttendanceDataController@getData');
    Route::get('attendancedata','AttendanceDataController@showAllData');
    Route::get('attendancedata/download/{code}','AttendanceDataController@downloadData');
    Route::get('attendancedata/get/overview','AttendanceDataController@getOverview');
    Route::get('attendancelogs/live','AttendanceDataController@viewLive');
    Route::get('attendancelogs/live/count','AttendanceDataController@countLive');
    Route::get('attendancelogs/recent','AttendanceDataController@recentLog');
    Route::get('attendancelogs/{id}','AttendanceDataController@viewLiveInfo');
    //COURSE
    Route::get('courses', 'CourseController@showCourses');
    Route::get('courses/list/{code}', 'CourseController@getStudentList');
});
Route::get('/getcoursename{code}', 'CourseController@name');
