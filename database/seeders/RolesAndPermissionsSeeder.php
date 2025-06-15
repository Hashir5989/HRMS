<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'employee.view', 'employee.create', 'employee.edit', 'employee.delete',
            'task.view', 'task.create', 'task.edit', 'task.delete', 'task.assign', 'task.change_status',
            'project.view', 'project.create', 'project.edit', 'project.delete',
            'file.view', 'file.upload', 'file.download', 'file.delete',
            'chat.view', 'chat.send',
            'attendance.view', 'attendance.manage',
            'leave.view', 'leave.apply', 'leave.approve',
            'department.manage',
            'role.manage'
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Create Roles and Assign Permissions
        $superAdmin = Role::findOrCreate('Super Admin');
        // Super Admin gets all permissions
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::findOrCreate('Admin');
        $admin->givePermissionTo([
            'employee.view', 'employee.create', 'employee.edit', 'employee.delete',
            'task.view', 'task.create', 'task.edit', 'task.delete', 'task.assign', 'task.change_status',
            'project.view', 'project.create', 'project.edit', 'project.delete',
            'file.view', 'file.upload', 'file.download', 'file.delete',
            'chat.view', 'chat.send',
            'attendance.view', 'attendance.manage',
            'leave.view', 'leave.apply', 'leave.approve',
            'department.manage'
        ]);

        $hr = Role::findOrCreate('HR');
        $hr->givePermissionTo([
            'employee.view', 'employee.create', 'employee.edit',
            'file.view', 'file.upload', 'file.download',
            'chat.view', 'chat.send',
            'attendance.view', 'attendance.manage',
            'leave.view', 'leave.approve',
            'department.manage'
        ]);

        $manager = Role::findOrCreate('Manager');
        $manager->givePermissionTo([
            'employee.view',
            'task.view', 'task.create', 'task.edit', 'task.assign', 'task.change_status',
            'project.view', 'project.create', 'project.edit',
            'file.view', 'file.upload', 'file.download',
            'chat.view', 'chat.send',
            'attendance.view',
            'leave.view', 'leave.approve'
        ]);

        $teamLead = Role::findOrCreate('Team Lead');
        $teamLead->givePermissionTo([
            'employee.view',
            'task.view', 'task.create', 'task.edit', 'task.assign', 'task.change_status',
            'project.view',
            'file.view', 'file.upload', 'file.download',
            'chat.view', 'chat.send',
            'attendance.view',
            'leave.view'
        ]);

        $employee = Role::findOrCreate('Employee');
        $employee->givePermissionTo([
            'employee.view',
            'task.view', 'task.edit', 'task.change_status',
            'project.view',
            'file.view', 'file.upload', 'file.download',
            'chat.view', 'chat.send',
            'attendance.view',
            'leave.view', 'leave.apply'
        ]);
        
        // Create demo users
        $superAdminUser = User::firstOrCreate([
            'email' => 'superadmin@example.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password')
        ]);
        $superAdminUser->assignRole('Super Admin');
        
        $adminUser = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('password')
        ]);
        $adminUser->assignRole('Admin');
        
        $hrUser = User::firstOrCreate([
            'email' => 'hr@example.com',
        ], [
            'name' => 'HR Manager',
            'password' => Hash::make('password')
        ]);
        $hrUser->assignRole('HR');
        
        $employeeUser = User::firstOrCreate([
            'email' => 'employee@example.com',
        ], [
            'name' => 'John Employee',
            'password' => Hash::make('password')
        ]);
        $employeeUser->assignRole('Employee');
    }
}
