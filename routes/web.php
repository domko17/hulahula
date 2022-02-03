<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'Dashboard\DashboardController@index');
Route::get('/thank-you', 'Dashboard\DashboardController@thankYou');

Route::post("/ajax", "Others\AjaxController@handle")->name('ajax_int');
Route::get('/no_conn', 'Helpers\HelperController@noConn');

Route::get('/dashboard', 'Dashboard\DashboardController@index')->name('dashboard');
Route::get('/dashboard/test', 'Dashboard\DashboardController@test')->name('test');
Route::get('/dashboard/test/fireEvent','Dashboard\DashboardController@testEventFire');
Route::post('/dashboard/test/push','PushController@store');
Route::get('/dashboard/test/push','PushController@push')->name('push');
Route::get('/dashboard/translations','Dashboard\DashboardController@translations')->name('dashboard.translations');
Route::get('/dashboard/translations/{file}/{lang}','Dashboard\DashboardController@translationsFile')->name('dashboard.translationsFile');
Route::post('/dashboard/translations/{file}/{lang}','Dashboard\DashboardController@translationsFileSave')->name('dashboard.translationsFileSave');

Route::get('/dashboard/select_first_language/{lid}', 'Dashboard\DashboardController@selectFirstLanguage')->name('dashboard.select_first_lang');
Route::post('/dashboard/confirm_study_day', 'Dashboard\DashboardController@signStudentStudyDay')->name('dashboard.confirmStudentStudyDay');

Route::get('/dashboard/change_theme', 'Dashboard\DashboardController@themeChange')->name('dashboard.themeChange');

Route::get('/set_locale/{locale}', 'Helpers\LocaleController@setLocale')->name('set_locale');

Route::get('/f3vqnvum6f45tki128e074ww6itb9avwlt7dye4u', 'Helpers\CronController@cronMail');
Route::get('/egsr3b916gn7zun3p31okncjb9afhn1tlp3rnc18', 'Helpers\CronController@cronDaily');


Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['auth']], function () {
    Route::get("/profile/{id}", "User\ProfileController@show")->name("profile");
    Route::post('/profile/teacher/{id}/addHours', "User\ProfileController@saveTeacherHours")->name("profile.teacher.saveHours");
    Route::post('/profile/teacher/{id}/addOneTimeHour', "User\ProfileController@saveTeacherOneTimeHour")->name("profile.teacher.saveOneTimeHour");
    Route::delete('/profile/teacher/{id}/deleteHours', "User\ProfileController@deleteTeacherHours")->name("profile.teacher.deleteHour");
    Route::delete('/profile/teacher/{id}/deleteVacation', "User\ProfileController@deleteVacation")->name("profile.teacher.deleteVacation");
    Route::post('/profile/teacher/{id}/addVacation', "User\ProfileController@addVacation")->name("profile.teacher.addVacation");
    Route::post('/profile/student/{id}/addNote', "User\ProfileController@addTeachersNote")->name("profile.student.add_note");
    Route::get("/profile/{id}/edit", "User\ProfileController@edit")->name("profile.edit");
    Route::put("/profile/{id}/edit", "User\ProfileController@update")->name("profile.update");
    Route::post("/profile/{id}/set_package_for_student", "User\ProfileController@setPackageForStudent")->name("profile.set_package_for_student");
    Route::post("/profile/{id}/set_study_languages", "User\ProfileController@setStudentStudyLanguages")->name("profile.changeStudyLanguages");
    Route::post("/profile/{id}/evaluate-language", "User\ProfileController@evaluateLanguage")->name("profile.evaluateLanguage");
});

Route::group(['prefix' => 'teacher', 'as' => 'teacher.', 'middleware' => ['auth']], function () {
    Route::get('/profile', 'Teacher\TeacherController@index')->name('index');
    Route::get('/myStudents', 'Admin\UsersController@teacherStudentListing')->name('my_students');
    Route::get('/meeting_detail/{meeting_id}', 'Admin\MeetingController@teacherNearestMeeting')->name('nearest_meeting');
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {

    Route::resource('/users', 'Admin\UsersController');
    Route::get("/guests",'Admin\UsersController@guestsListing')->name('users.guests.index');
    Route::get("/students",'Admin\UsersController@studentsListing')->name('users.students.index');;
    Route::get('/teachers', 'Admin\TeachersController@index')->name('teachers.index');
    Route::post('/teachers/make_payment', 'Admin\TeachersController@makePayment')->name('teachers.make_payment');
    Route::get('/teachers/teachers_students/{teacher_id}', 'Admin\UsersController@teacherStudentListing_admin')->name('teachers.teachers_students');
    Route::get('/teachers/teachers_hours/{teacher_id}', 'Admin\TeachersController@teacherFinishedClasses')->name('teachers.teachers_hours');
    Route::resource('/email-queue', 'Admin\EmailQueueController');
    Route::get('/email-queue/send_one/{id}', 'Admin\EmailQueueController@sendOne')->name('email-queue.send_one');
    Route::get('/email-queue/preview/{id}', 'Admin\EmailQueueController@renderMail')->name('email-queue.preview');
    Route::post('/languages', 'Admin\LanguagesController@store')->name('languages.store');
    Route::get('/languages/{id}/edit', 'Admin\LanguagesController@edit')->name('languages.edit');
    Route::get('/languages/create', 'Admin\LanguagesController@create')->name('languages.create');
    Route::put('/languages/{id}', 'Admin\LanguagesController@update')->name('languages.update');
    Route::delete('/languages/{id}', 'Admin\LanguagesController@destroy')->name('languages.destroy');
    Route::resource('/package-orders', 'Admin\PackageOrderController');
    Route::post('/package-orders/package-as-paid/{id}', 'Admin\PackageOrderController@signAsPaid')->name('package-orders.sign_as_paid');
    Route::resource('/star-orders', 'Admin\StarOrderController');
    //Route::post('/star-orders/sign-as-paid/{id}', 'Admin\StarOrderController@signAsPaid')->name('star-orders.sign_as_paid');
    Route::resource('/word_cards', "Admin\WordCardsController");
    Route::get('/word_cards/language/{id}', "Admin\WordCardsController@indexLanguage")->name("word_cards.index_language");
    Route::get('/word_cards/language/{id}/create', "Admin\WordCardsController@create")->name("word_cards.create");
    Route::resource("/gift_codes", "Admin\GiftCodeController");
    Route::resource('/meetings', 'Admin\MeetingController');
    Route::resource('/banners', 'Admin\BannerPostController');
    Route::get('/banners/toggleActive/{banner_id}', 'Admin\BannerPostController@toggleActive')->name('banners.toggle_active');
    Route::get('/birthday', 'Dashboard\DashboardController@birthdays')->name('birthdays');
});

Route::group(['middleware' => ['auth']], function () {

    Route::get('/languages', 'Admin\LanguagesController@index')->name('admin.languages.index');
    Route::get('/languages/{id}', 'Admin\LanguagesController@show')->name('admin.languages.show');
    Route::get('/languages/{id}/teachers', 'Admin\LanguagesController@languageTeachers')->name('admin.languages.teachers');

    Route::get('/lectures', 'Dashboard\LecturesController@index')->name("lectures.index");
    Route::get('/lectures/collective_courses', 'Dashboard\LecturesController@collectiveCoursesListing')->name("lectures.collective_courses.index");
    Route::delete('/lectures/collective_courses/{id}', 'Dashboard\LecturesController@collectiveCourseDestroy')->name("lectures.collective_courses.destroy");
    Route::post('/lectures/collective_courses/prolong', 'Dashboard\LecturesController@collectiveCourseProlong')->name("lectures.collective_courses.prolong");
    Route::get('/lectures/{id}', 'Dashboard\LecturesController@show')->name("lectures.show");
    Route::get('/lectures/{th_id}/preview/{date}', 'Dashboard\LecturesController@show_preview')->name("lectures.preview");
    Route::post('/lectures/sign_student/{id}', 'Dashboard\LecturesController@signStudentForClass')->name('lectures.sign_student');
    Route::post('/lectures/reschedule_lecture/{lecture_id}', 'Dashboard\LecturesController@rescheduleClass')->name('lectures.reschedule_class');
    Route::post('/lectures/edit_info/{id}', 'Dashboard\LecturesController@editInfo')->name('lectures.edit_info');
    Route::post('/lectures/edit_material/{id}', 'Dashboard\LecturesController@editMaterial')->name('lectures.edit_class_material');
    Route::post('/lectures/add_collective', 'Dashboard\LecturesController@addCollective')->name('lectures.add_collective');
    Route::get('/lectures/assign_as_teacher/{id}', 'Dashboard\LecturesController@assignAsTeacher')->name('lectures.assign_as_teacher');
    Route::get('/lectures/assign_as_sub_teacher/{id}', 'Dashboard\LecturesController@assignAsSubTeacher')->name('lectures.assign_as_sub_teacher');
    Route::get('/lectures/un_assign_as_teacher/{id}', 'Dashboard\LecturesController@unassignAsTeacher')->name('lectures.unassign_as_teacher');
    Route::get('/lectures/un_assign_as_sub_teacher/{id}', 'Dashboard\LecturesController@unassignAsSubTeacher')->name('lectures.unassign_as_sub_teacher');
    Route::put('/lectures/change_class_limit/{id}', 'Dashboard\LecturesController@changeClassLimit')->name('lectures.edit_class_limit');
    Route::get('/lectures/un_assign_student/{cid}/{sid}', 'Dashboard\LecturesController@unAssignStudent')->name('lectures.un_assign_student');
    Route::post('/lectures/cancel/{cid}', 'Dashboard\LecturesController@cancelClass')->name('lectures.cancel_class');
    Route::post('/lectures/save_recording/{cid}', 'Dashboard\LecturesController@saveRecording')->name('lectures.save_recording_link');
    Route::post('/lectures/add_students_admin/{cid}', 'Dashboard\LecturesController@addStudentsAdmin')->name('lectures.add_students_admin');
    Route::post('/lectures/enroll_from_preview', 'Dashboard\LecturesController@enrollPromPreview')->name('lectures.enroll_from_preview');
    Route::post('/lectures/create_lecture_from_preview', 'Dashboard\LecturesController@createLectureFromPreview')->name('lectures.create_lecture_from_preview');
    Route::post('/lectures/cancel_lecture_from_preview', 'Dashboard\LecturesController@cancelLectureFromPreview')->name('lectures.cancel_lecture_from_preview');

    Route::get("/word_cards/teacher", "Admin\WordCardsController@teacherIndex")->name('word_cards.teacher.index');

    Route::resource('messages', 'Others\MessageController');
    Route::resource('materials', "Dashboard\MaterialController");
    Route::get('materials/student/{student_id}', "Dashboard\MaterialController@studentsMaterial")->name('materials.students_material');
    Route::get('materials/download/{id}', "Dashboard\MaterialController@download")->name('materials.download');

    Route::get('/buy_package', "Dashboard\BuyStarsController@index")->name('buy_stars.index');
    Route::delete('/buy_stars/{id}', "Dashboard\BuyStarsController@destroy")->name('buy_stars.destroy');
    Route::get('/contact', "Dashboard\DashboardController@contactPage")->name('dashboard.contact');

    Route::resource('/survey', 'Admin\SurveyController');

    //Route::resource('/feedback', 'Dashboard\FeedbackController');
    Route::get('/feedback/create/{teacher_id}', 'Dashboard\FeedbackController@createFeedback')->name('feedback.createFeedback');
    Route::resource('/feedback', 'Dashboard\FeedbackController');
    Route::get('/feedback/student/{id}', 'Dashboard\FeedbackController@indexStudent')->name('feedback.indexStudent');

    Route::post('/lecture/request', 'Dashboard\DashboardController@saveClassRequest')->name('makeLectureRequest');
    Route::get('/lecture/request/take/{id}', 'Dashboard\DashboardController@takeClassRequest')->name('takeLectureRequest');
});


Route::get('/zoom_meeting', 'Zoom\ZoomController@meeting')->name('zoom_meeting');
Route::get('/zoom_index', 'Zoom\ZoomController@index')->name('zoom_index');
