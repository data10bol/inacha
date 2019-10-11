<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        app()['cache']->forget('spatie.permission.cache');

        // Setup permissions
        // create permissions

            Permission::create(['name' => 'create.subtask']);
            Permission::create(['name' => 'read.subtask']);
            Permission::create(['name' => 'edit.subtask']);
            Permission::create(['name' => 'delete.subtask']);
            Permission::create(['name' => 'create.task']);
            Permission::create(['name' => 'read.task']);
            Permission::create(['name' => 'edit.task']);
            Permission::create(['name' => 'delete.task']);
            Permission::create(['name' => 'create.operation']);
            Permission::create(['name' => 'read.operation']);
            Permission::create(['name' => 'edit.operation']);
            Permission::create(['name' => 'delete.operation']);
            Permission::create(['name' => 'create.action']);
            Permission::create(['name' => 'read.action']);
            Permission::create(['name' => 'edit.action']);
            Permission::create(['name' => 'delete.action']);
            Permission::create(['name' => 'create.goal']);
            Permission::create(['name' => 'read.goal']);
            Permission::create(['name' => 'edit.goal']);
            Permission::create(['name' => 'delete.goal']);
            Permission::create(['name' => 'create.configuration']);
            Permission::create(['name' => 'read.configuration']);
            Permission::create(['name' => 'edit.configuration']);
            Permission::create(['name' => 'delete.configuration']);
            Permission::create(['name' => 'create.doing']);
            Permission::create(['name' => 'read.doing']);
            Permission::create(['name' => 'edit.doing']);
            Permission::create(['name' => 'delete.doing']);
            Permission::create(['name' => 'create.result']);
            Permission::create(['name' => 'read.result']);
            Permission::create(['name' => 'edit.result']);
            Permission::create(['name' => 'delete.result']);
            Permission::create(['name' => 'create.target']);
            Permission::create(['name' => 'read.target']);
            Permission::create(['name' => 'edit.target']);
            Permission::create(['name' => 'delete.target']);
            Permission::create(['name' => 'create.policy']);
            Permission::create(['name' => 'read.policy']);
            Permission::create(['name' => 'edit.policy']);
            Permission::create(['name' => 'delete.policy']);
            Permission::create(['name' => 'create.year']);
            Permission::create(['name' => 'read.year']);
            Permission::create(['name' => 'edit.year']);
            Permission::create(['name' => 'delete.year']);
            Permission::create(['name' => 'create.limit']);
            Permission::create(['name' => 'read.limit']);
            Permission::create(['name' => 'edit.limit']);
            Permission::create(['name' => 'delete.limit']);
            Permission::create(['name' => 'create.period']);
            Permission::create(['name' => 'read.period']);
            Permission::create(['name' => 'edit.period']);
            Permission::create(['name' => 'delete.period']);
            Permission::create(['name' => 'create.department']);
            Permission::create(['name' => 'read.department']);
            Permission::create(['name' => 'edit.department']);
            Permission::create(['name' => 'delete.department']);
            Permission::create(['name' => 'create.position']);
            Permission::create(['name' => 'read.position']);
            Permission::create(['name' => 'edit.position']);
            Permission::create(['name' => 'delete.position']);
            Permission::create(['name' => 'create.plan']);
            Permission::create(['name' => 'read.plan']);
            Permission::create(['name' => 'edit.plan']);
            Permission::create(['name' => 'delete.plan']);
            Permission::create(['name' => 'create.user']);
            Permission::create(['name' => 'read.user']);
            Permission::create(['name' => 'edit.user']);
            Permission::create(['name' => 'delete.user']);
            Permission::create(['name' => 'create.role']);
            Permission::create(['name' => 'read.role']);
            Permission::create(['name' => 'edit.role']);
            Permission::create(['name' => 'delete.role']);
            Permission::create(['name' => 'create.permission']);
            Permission::create(['name' => 'read.permission']);
            Permission::create(['name' => 'edit.permission']);
            Permission::create(['name' => 'delete.permission']);
            Permission::create(['name' => 'execution.task']);
            Permission::create(['name' => 'read.listpoa']);
            Permission::create(['name' => 'edit.listpoa']);
            Permission::create(['name' => 'delete.listpoa']);
            Permission::create(['name' => 'report.poa']);
            Permission::create(['name' => 'list.poa']);
            Permission::create(['name' => 'read.list']);
            Permission::create(['name' => 'show.list']);

        // Create root role
        $role = Role::create(['name' => 'Administrador']);
        // Assign permissions to root
        $role->givePermissionTo(Permission::all());
        // Create Supervisor role
        $role = Role::create(['name' => 'Supervisor']);
        // Assign permissions to supervisor
        $role->givePermissionTo([
                            'read.operation',
                            'edit.operation',
                            'delete.operation',
                            'create.action',
                            'read.action',
                            'edit.action',
                            'delete.action',
                            'create.goal',
                            'read.goal',
                            'edit.goal',
                            'delete.goal',
                            'create.configuration',
                            'read.configuration',
                            'edit.configuration',
                            'delete.configuration',
                            'report.poa'
                            ]);
        // Create Responsable role
        $role = Role::create(['name' => 'Responsable']);
        // Assign permissions to responsable
        $role->givePermissionTo([
                            'create.subtask',
                            'read.subtask',
                            'edit.subtask',
                            'delete.subtask',
                            'create.task',
                            'read.task',
                            'edit.task',
                            'delete.task',
                            'create.operation',
                            'read.operation',
                            'edit.operation',
                            'delete.operation',
                            'execution.task',
                            'report.poa'
                            ]);
        // Create user role
        $role = Role::create(['name' => 'Usuario']);
        // Assign permissions to user
        $role->givePermissionTo([
                            'create.subtask',
                            'read.subtask',
                            'edit.subtask',
                            'delete.subtask',
                            'read.operation',
                            'create.task',
                            'read.task',
                            'edit.task',
                            'execution.task'
                            ]);

        DB::table('model_has_roles')->insert([
            [
                'role_id' => '1',
                'model_type' => 'App\User',
                'model_id' => '1',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '2',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '3',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '4',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '5',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '6',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '7',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '8',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '9',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '10',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '11',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '12',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '13',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '14',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '15',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '16',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '17',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '18',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '19',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '20',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '21',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '22',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '23',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '24',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '25',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '26',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '27',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '28',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '29',
            ],
            [
                'role_id' => '4',
                'model_type' => 'App\User',
                'model_id' => '30',
            ],
        ]);

    }
}
