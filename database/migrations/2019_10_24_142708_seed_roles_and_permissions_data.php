<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SeedRolesAndPermissionsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //清理缓存 否则会报错
        app(Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        //先创建权限
        Permission::create(['name'=> 'manage_contents']);
        Permission::create(['name'=> 'manage_users']);
        Permission::create(['name' => 'eait_settings']);

        //创建站长角色 并赋予权限
        $founder = Role::create(['name' => 'Founder']);
        $founder->givePermissionTo('manage_contents');
        $founder->givePermissionTo('manage_users');
        $founder->givePermissionTO('eait_settings');

        $maintainer = Role::create(['name'=>'Maintainer']);
        $maintainer->givePermissionTo('manage_contents');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        app(Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $tableNames = config('permission.table_names');

        Model::unguard();
        DB::table($tableNames['role_has_permissions'])->delete();
        DB::table($tableNames['model_has_roles'])->delete();
        DB::table($tableNames['model_has_permissions'])->delete();
        DB::table($tableNames['roles'])->delete();
        DB::table($tableNames['permissions'])->delete();
        Model::reguard();
    }
}
