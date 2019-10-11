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

// LOGIN TO THE SYSTEM

Route::get('/', 'Auth\LoginController@showLoginForm')->name('logs');

Route::get('/ldap/{user}/{pass}', 'Auth\LoginController@ldap')->name('ldap');

Route::post('login', 'Auth\LoginController@login')->name('login');

Route::get('login', 'Auth\LoginController@logon')->name('logon');

Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => 'auth'], function () {

  Route::get('dashboard','DashboardController@index')->name('dashboard');
  Route::get('home','DashboardController@index')->name('dashboard');
  Route::get('sidebar','DashboardController@sidebar')->name('sidebar');

  // User

    // ACTION EXECUTION

    Route::resource('execution/action', 'User\\ExecutionActionController')
          ->except(['edit','update','create','store','destroy'])
          ->names([
            'index' => 'executionaction.index',
            ]);

    // OPERATION EXECUTION

    Route::resource('execution/operation', 'User\\ExecutionOperationController')
          ->except(['create','store','destroy'])
          ->names([
            'index' => 'executionoperation.index',
            'update' => 'executionoperation.update',
            ]);

    // TASK EXECUTION

    Route::resource('execution/task', 'User\\ExecutionTaskController')
          ->except(['create','store','destroy'])
          ->names([
            'index' => 'executiontask.index',
            'update' => 'executiontask.update',
            ]);
  // Chief

    // OPERATION

    	Route::resource('institution/operation', 'Chief\\OperationController');

    // TASK

        Route::resource('institution/task', 'Chief\\TaskController');
        Route::get('/institution/task/{id}/delete', 'Chief\\TaskController@delete')->name('deletetask');

    // SUBTASK

        Route::resource('institution/subtask', 'Chief\\SubtaskController');
        Route::get('/institution/subtask/{id}/delete', 'Chief\\SubtaskController@delete')->name('deletesubtask');

    // REPORT

        Route::get('reportpoa','Report\PoaController@index')->name('reportpoa');
        Route::get('showreportpoa','Report\PoaController@show')->name('showreportpoa');
        Route::get('excelpoa','Report\ExcelController@excel')->name('excelpoa');
        Route::get('pdfpoa','Report\ExcelController@pdf')->name('pdfpoa');

    // CHARTS

        Route::get('chartpoa','Report\ChartController@index')->name('chartpoa');

    // GANTT

        Route::get('ganttpoa','Report\GanttController@index')->name('ganttpoa');

    // FORMS

        Route::get('formpoa','Report\FormController@index')->name('formpoa');
        Route::get('/formpoa/form1','Report\FormController@form1')->name('formpoa.form1');
        Route::get('/formpoa/form2','Report\FormController@form2')->name('formpoa.form2');
        Route::get('/formpoa/form3','Report\FormController@form3')->name('formpoa.form3');
        Route::get('/formpoa/presupuesto','Report\FormController@presupuesto')->name('formpoa.presupuesto');
        Route::get('/formpoa/consultorias','Report\FormController@consultorias')->name('formpoa.consultorias');
        Route::get('/formpoa/eventuales','Report\FormController@eventuales')->name('formpoa.eventuales');
        Route::get('/pdf/form1','Report\PdfController@form1')->name('pdf.form1');
        Route::get('/excel/form1','Report\ExcelController@form1')->name('excel.form1');

  // SUPERVISOR

    // GOVERNMENT

        // PERIOD

        Route::resource('government/period', 'Supervisor\\PeriodController');

        // YEAR

        Route::resource('government/year', 'Supervisor\\YearController');

        // POLICY

        Route::resource('government/policy', 'Supervisor\\PolicyController');

        // TARGET

        Route::resource('government/target', 'Supervisor\\TargetController');

        // RESULT

        Route::resource('government/result', 'Supervisor\\ResultController');

        // DOING

        Route::resource('government/doing', 'Supervisor\\DoingController');

    // INSTITUTION

      // CONFIGURATION

      Route::resource('institution/configuration', 'Supervisor\\ConfigurationController');

      // REPROGRAMATION
      
      Route::post('addReformulation', 'Supervisor\ConfigurationController@addReformulation');
      Route::post('statusReprogramation', 'Supervisor\ConfigurationController@statusReprog');

      // GOAL

      Route::resource('institution/goal', 'Supervisor\\GoalController');

      // ACTION

    	Route::resource('institution/action', 'Supervisor\\ActionController');

  // ADMINISTRATOR

    // PERMISSIONS

    	Route::resource('administrator/permission', 'Administrator\\PermissionController');

    // ROLES

    	Route::resource('administrator/role', 'Administrator\\RoleController');

    // PLANS

    Route::resource('administrator/plan', 'Administrator\\PlanController');

    // USERS

    	Route::resource('administrator/user', 'Administrator\\UserController');

    // POSITIONS

        Route::resource('administrator/position', 'Administrator\\PositionController');

    // DEPARTMENTS

    	Route::resource('administrator/department', 'Administrator\\DepartmentController');

    // DEPARTMENTS

    Route::resource('administrator/limit', 'Administrator\\LimitController');

    // LOGS
    Route::group(['middleware' => ['role:Administrador']], function () {
      Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    });

});

