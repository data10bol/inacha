<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWara2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('months', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 32)->unique();

            $table->timestamps();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 128)->nullable(false);
            $table->string('initial', 32)->nullable(true)->default('OFEP');
            $table->string('color', 32)->nullable(true)->default('info');
            $table->string('icon', 32)->nullable(true)->default('ion-ios-briefcase');
            $table->integer('dependency')->default(1);
            $table->integer('user_id')->default(0);

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('positions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('department_id')->unsigned()->default('1');
            $table->string('name', 128)->nullable(false);
            $table->boolean('status')->default(1);

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employee', 32)->default('0');
            $table->string('username', 64)->unique();
            $table->string('password', 128)->nullable(false);
            $table->string('name', 128)->nullable(false);
            $table->integer('position_id')->unsigned()->default('1');
            $table->integer('dependency')->default(1)->nullable(true);
            $table->boolean('status')->default(1);
            $table->boolean('sidebar')->default(0);
            $table->string('ip', 32)->nullable(true);
            $table->dateTime('login_at')->nullable(true);
            $table->string('remember_token', 100)->nullable(true);

            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('position_id')->unsigned()->default('1');
            $table->string('name', 512)->nullable(false);
            $table->boolean('status')->default(1);

            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('periods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('start')->nulleable(false);
            $table->integer('finish')->nulleable(false);
            $table->boolean('current')->default(0);
            $table->boolean('status')->default(0);

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('years', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('name')->nulleable(false);
            $table->integer('period_id')->unsigned();
            $table->boolean('current')->default(0);
            $table->boolean('status')->default(0);

            $table->foreign('period_id')->references('id')->on('periods')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('limits', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->nulleable(false);
            $table->integer('year_id')->unsigned();
            $table->integer('month')->default(1);
            $table->boolean('status')->default(0);

            $table->foreign('year_id')->references('id')->on('years')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('policys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code');
            $table->integer('period_id')->unsigned();

            $table->foreign('period_id')->references('id')->on('periods')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('targets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code');
            $table->integer('policy_id')->unsigned();

            $table->foreign('policy_id')->references('id')->on('policys')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code');
            $table->integer('target_id')->unsigned();

            $table->foreign('target_id')->references('id')->on('targets')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('doings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code');
            $table->integer('result_id')->unsigned();

            $table->foreign('result_id')->references('id')->on('results')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('descriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description', 512)->nullable(false);
            $table->integer('description_id')->unsigned();
            $table->string('description_type');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('configurations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('period_id')->unsigned();
            $table->string('mission', 512)->nullable(false);
            $table->string('vision', 512)->nullable(false);
            $table->boolean('status')->default(0);
            $table->boolean('edit')->default(1);
            $table->boolean('reconfigure')->default(0);

            $table->foreign('period_id')->references('id')->on('periods');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('goals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code');
            $table->integer('doing_id')->unsigned();
            $table->integer('configuration_id')->unsigned();
            $table->string('description', 512)->nullable(false);

            $table->foreign('doing_id')->references('id')->on('doings');
            $table->foreign('configuration_id')->references('id')->on('configurations')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('actions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code');
            $table->integer('goal_id')->unsigned();
            $table->integer('year');
            $table->integer('department_id')->unsigned()->default(1);

            $table->boolean('status')->default(1); // 0 - Reprogramed

            $table->foreign('goal_id')->references('id')->on('goals');
            $table->foreign('department_id')->references('id')->on('departments');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('operations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code');
            $table->integer('action_id')->unsigned();

            $table->boolean('status')->default(1); // 0 - Reprogramed

            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code');
//            $table->integer('user_id')->unsigned();
            $table->integer('plan_id')->unsigned();
            $table->integer('operation_id')->unsigned();
            $table->string('reason', 256)->nullable(true);

            $table->boolean('status')->default(1); // 0 - Reprogramed

//            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('operation_id')->references('id')->on('operations')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('task_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('task_id')->unsigned();
            $table->foreign('task_id')->references('id')->on('tasks');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('subtasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code');
            $table->integer('task_id')->unsigned();
            $table->string('description', 256)->nullable(false);
            $table->string('validation', 256)->nullable(true);
            $table->string('reason', 256)->nullable(true);
            $table->date('start')->nulleable(false);
            $table->date('finish')->nulleable(false);

            $table->boolean('status')->default(0); // 0 - Programed , 1 - Executed

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('definitions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description', 512)->nullable(false);
            $table->string('measure', 128)->nullable(true);
            $table->integer('ponderation')->unsigned()->default(0);
            $table->integer('base')->unsigned()->default(0);
            $table->integer('aim')->unsigned()->default(0);
            $table->string('pointer', 256)->nullable(false);
            $table->string('describe', 256)->nullable(false);
            $table->string('validation', 512)->nullable(false);
            $table->integer('start')->nullable(false)->default(1);
            $table->integer('finish')->nullable(false)->default(12);
            $table->integer('definition_id')->unsigned();
            $table->string('definition_type');
            // Se agrega dos campos para la REPROGRAMACIÓN
            $table->integer('in')->unsigned()->default(0);
            $table->integer('out')->unsigned()->default(12);

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('poas', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('state')->default(0); //0 - Program; 1 - Execute
            $table->integer('value')->unsigned()->default(0);
            $table->integer('month')->unsigned()->default(0);
            $table->decimal('m1', 6, 2)->default(0);
            $table->decimal('m2', 6, 2)->default(0);
            $table->decimal('m3', 6, 2)->default(0);
            $table->decimal('m4', 6, 2)->default(0);
            $table->decimal('m5', 6, 2)->default(0);
            $table->decimal('m6', 6, 2)->default(0);
            $table->decimal('m7', 6, 2)->default(0);
            $table->decimal('m8', 6, 2)->default(0);
            $table->decimal('m9', 6, 2)->default(0);
            $table->decimal('m10', 6, 2)->default(0);
            $table->decimal('m11', 6, 2)->default(0);
            $table->decimal('m12', 6, 2)->default(0);
            $table->string('success', 512)->nullable(true);
            $table->string('failure', 512)->nullable(true);
            $table->string('solution', 512)->nullable(true);
            $table->boolean('status')->default(0); //0 - Program; 1 - Reprogram

            $table->integer('poa_id')->unsigned();
            $table->string('poa_type');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('structures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 64)->nullable(false);
            $table->string('name', 256)->nullable(false);
            $table->integer('current')->decimal('amount', 4, 2)->default(0);
            $table->integer('inversion')->decimal('amount', 4, 2)->default(0);
            $table->boolean('status')->default(0); //0 - Program; 1 - Reprogram

            $table->integer('structure_id')->unsigned();
            $table->string('structure_type');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('reasons', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('user_id')->unsigned();
          $table->string('description', 512)->nullable(false);
          $table->integer('reason_id')->unsigned();
          $table->string('reason_type');

          $table->softDeletes();
          $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');
        Schema::dropIfExists('users');
        Schema::dropIfExists('pointers');
        Schema::dropIfExists('actions');
        Schema::dropIfExists('operations');
        Schema::dropIfExists('tasks');
    }
}
