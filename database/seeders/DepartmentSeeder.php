<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Team;
use App\Models\LeaveType;
use App\Models\Holiday;
use App\Models\User;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        // ─── DEPARTMENTS ─────────────────────────────────────────────
        $departments = [
            ['name' => 'Engineering',       'description' => 'Software development and engineering team responsible for building products.'],
            ['name' => 'Human Resources',   'description' => 'Manages recruitment, employee relations, benefits, and compliance.'],
            ['name' => 'Marketing',         'description' => 'Handles brand strategy, digital marketing, content, and campaigns.'],
            ['name' => 'Sales',             'description' => 'Drives revenue through client acquisition and relationship management.'],
            ['name' => 'Finance',           'description' => 'Oversees budgeting, accounting, financial reporting, and payroll.'],
            ['name' => 'Design',            'description' => 'UI/UX design, branding, and visual communication.'],
            ['name' => 'Quality Assurance', 'description' => 'Ensures product quality through testing and process improvement.'],
            ['name' => 'DevOps',            'description' => 'Manages infrastructure, CI/CD pipelines, and cloud operations.'],
            ['name' => 'Customer Support',  'description' => 'Provides client-facing support and issue resolution.'],
            ['name' => 'Operations',        'description' => 'Handles day-to-day business operations and logistics.'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['name' => $dept['name']], $dept);
        }

        // ─── DESIGNATIONS ────────────────────────────────────────────
        $engineering = Department::where('name', 'Engineering')->first();
        $hr = Department::where('name', 'Human Resources')->first();
        $marketing = Department::where('name', 'Marketing')->first();
        $sales = Department::where('name', 'Sales')->first();
        $finance = Department::where('name', 'Finance')->first();
        $design = Department::where('name', 'Design')->first();
        $qa = Department::where('name', 'Quality Assurance')->first();
        $devops = Department::where('name', 'DevOps')->first();
        $support = Department::where('name', 'Customer Support')->first();

        $designations = [
            // Engineering
            ['name' => 'Junior Software Engineer',  'department_id' => $engineering->id],
            ['name' => 'Software Engineer',          'department_id' => $engineering->id],
            ['name' => 'Senior Software Engineer',   'department_id' => $engineering->id],
            ['name' => 'Lead Software Engineer',     'department_id' => $engineering->id],
            ['name' => 'Engineering Manager',        'department_id' => $engineering->id],
            ['name' => 'Principal Engineer',         'department_id' => $engineering->id],
            // HR
            ['name' => 'HR Coordinator',             'department_id' => $hr->id],
            ['name' => 'HR Specialist',              'department_id' => $hr->id],
            ['name' => 'HR Manager',                 'department_id' => $hr->id],
            ['name' => 'Talent Acquisition Lead',    'department_id' => $hr->id],
            // Marketing
            ['name' => 'Marketing Coordinator',      'department_id' => $marketing->id],
            ['name' => 'Content Strategist',         'department_id' => $marketing->id],
            ['name' => 'Digital Marketing Manager',  'department_id' => $marketing->id],
            ['name' => 'SEO Specialist',             'department_id' => $marketing->id],
            // Sales
            ['name' => 'Sales Executive',            'department_id' => $sales->id],
            ['name' => 'Account Manager',            'department_id' => $sales->id],
            ['name' => 'Sales Manager',              'department_id' => $sales->id],
            ['name' => 'Business Development Lead',  'department_id' => $sales->id],
            // Finance
            ['name' => 'Accountant',                 'department_id' => $finance->id],
            ['name' => 'Financial Analyst',          'department_id' => $finance->id],
            ['name' => 'Finance Manager',            'department_id' => $finance->id],
            // Design
            ['name' => 'UI Designer',                'department_id' => $design->id],
            ['name' => 'UX Designer',                'department_id' => $design->id],
            ['name' => 'Senior UI/UX Designer',      'department_id' => $design->id],
            ['name' => 'Design Lead',                'department_id' => $design->id],
            // QA
            ['name' => 'QA Engineer',                'department_id' => $qa->id],
            ['name' => 'Senior QA Engineer',         'department_id' => $qa->id],
            ['name' => 'QA Lead',                    'department_id' => $qa->id],
            // DevOps
            ['name' => 'DevOps Engineer',            'department_id' => $devops->id],
            ['name' => 'Senior DevOps Engineer',     'department_id' => $devops->id],
            ['name' => 'Cloud Architect',            'department_id' => $devops->id],
            // Support
            ['name' => 'Support Agent',              'department_id' => $support->id],
            ['name' => 'Support Lead',               'department_id' => $support->id],
        ];

        foreach ($designations as $desg) {
            Designation::firstOrCreate(['name' => $desg['name'], 'department_id' => $desg['department_id']], $desg);
        }

        // ─── TEAMS ───────────────────────────────────────────────────
        $teams = [
            ['name' => 'Backend Team',      'department_id' => $engineering->id, 'description' => 'Handles server-side development, APIs, and database architecture.'],
            ['name' => 'Frontend Team',     'department_id' => $engineering->id, 'description' => 'Builds user interfaces and client-side applications.'],
            ['name' => 'Mobile Team',       'department_id' => $engineering->id, 'description' => 'Develops iOS and Android applications.'],
            ['name' => 'Growth Team',       'department_id' => $marketing->id,   'description' => 'Focuses on user acquisition, retention, and growth metrics.'],
            ['name' => 'Design Studio',     'department_id' => $design->id,      'description' => 'Creates visual designs, prototypes, and brand assets.'],
            ['name' => 'QA Automation',     'department_id' => $qa->id,          'description' => 'Builds and maintains automated test suites.'],
            ['name' => 'Infrastructure',    'department_id' => $devops->id,      'description' => 'Manages cloud infrastructure and deployment pipelines.'],
            ['name' => 'Enterprise Sales',  'department_id' => $sales->id,       'description' => 'Handles large enterprise accounts and partnerships.'],
        ];

        foreach ($teams as $team) {
            Team::firstOrCreate(['name' => $team['name']], $team);
        }

        // ─── LEAVE TYPES ─────────────────────────────────────────────
        $leaveTypes = [
            ['name' => 'Annual Leave',      'days_allowed' => 20, 'color' => '#4f46e5', 'is_paid' => true,  'is_active' => true, 'description' => 'Yearly paid vacation leave.'],
            ['name' => 'Sick Leave',        'days_allowed' => 12, 'color' => '#ef4444', 'is_paid' => true,  'is_active' => true, 'description' => 'Leave for medical reasons.'],
            ['name' => 'Casual Leave',      'days_allowed' => 10, 'color' => '#f59e0b', 'is_paid' => true,  'is_active' => true, 'description' => 'Short-notice personal leave.'],
            ['name' => 'Maternity Leave',   'days_allowed' => 90, 'color' => '#ec4899', 'is_paid' => true,  'is_active' => true, 'description' => 'Leave for new mothers.'],
            ['name' => 'Paternity Leave',   'days_allowed' => 15, 'color' => '#0ea5e9', 'is_paid' => true,  'is_active' => true, 'description' => 'Leave for new fathers.'],
            ['name' => 'Bereavement Leave', 'days_allowed' => 5,  'color' => '#6b7280', 'is_paid' => true,  'is_active' => true, 'description' => 'Leave due to death in the family.'],
            ['name' => 'Unpaid Leave',      'days_allowed' => 30, 'color' => '#9ca3af', 'is_paid' => false, 'is_active' => true, 'description' => 'Extended leave without pay.'],
            ['name' => 'Work From Home',    'days_allowed' => 24, 'color' => '#10b981', 'is_paid' => true,  'is_active' => true, 'description' => 'Remote work day allowance.'],
        ];

        foreach ($leaveTypes as $lt) {
            LeaveType::firstOrCreate(['name' => $lt['name']], $lt);
        }

        // ─── HOLIDAYS (2025) ─────────────────────────────────────────
        $holidays = [
            ['name' => "New Year's Day",         'date' => '2025-01-01', 'type' => 'public',   'is_recurring' => true],
            ['name' => 'Martin Luther King Day', 'date' => '2025-01-20', 'type' => 'public',   'is_recurring' => true],
            ['name' => "Presidents' Day",        'date' => '2025-02-17', 'type' => 'public',   'is_recurring' => true],
            ['name' => 'Memorial Day',           'date' => '2025-05-26', 'type' => 'public',   'is_recurring' => true],
            ['name' => 'Independence Day',       'date' => '2025-07-04', 'type' => 'public',   'is_recurring' => true],
            ['name' => 'Labor Day',              'date' => '2025-09-01', 'type' => 'public',   'is_recurring' => true],
            ['name' => 'Thanksgiving Day',       'date' => '2025-11-27', 'type' => 'public',   'is_recurring' => true],
            ['name' => 'Day After Thanksgiving', 'date' => '2025-11-28', 'type' => 'company',  'is_recurring' => true],
            ['name' => 'Christmas Eve',          'date' => '2025-12-24', 'type' => 'company',  'is_recurring' => true],
            ['name' => 'Christmas Day',          'date' => '2025-12-25', 'type' => 'public',   'is_recurring' => true],
            ['name' => "New Year's Eve",         'date' => '2025-12-31', 'type' => 'company',  'is_recurring' => true],
            ['name' => 'Company Foundation Day', 'date' => '2025-03-15', 'type' => 'company',  'is_recurring' => true],
        ];

        foreach ($holidays as $holiday) {
            Holiday::firstOrCreate(['name' => $holiday['name'], 'date' => $holiday['date']], $holiday);
        }

        // ─── SAMPLE EMPLOYEES ────────────────────────────────────────
        $seUsers = [
            ['name' => 'Sarah Johnson',    'email' => 'sarah.johnson@company.com',    'role' => 'Manager',   'dept' => 'Engineering',       'desg' => 'Engineering Manager',        'team' => 'Backend Team',     'gender' => 'female', 'salary' => 95000],
            ['name' => 'Michael Chen',     'email' => 'michael.chen@company.com',     'role' => 'Team Lead', 'dept' => 'Engineering',       'desg' => 'Lead Software Engineer',     'team' => 'Frontend Team',    'gender' => 'male',   'salary' => 85000],
            ['name' => 'Emily Davis',      'email' => 'emily.davis@company.com',      'role' => 'Employee',  'dept' => 'Engineering',       'desg' => 'Senior Software Engineer',   'team' => 'Backend Team',     'gender' => 'female', 'salary' => 78000],
            ['name' => 'James Wilson',     'email' => 'james.wilson@company.com',     'role' => 'Employee',  'dept' => 'Engineering',       'desg' => 'Software Engineer',          'team' => 'Frontend Team',    'gender' => 'male',   'salary' => 65000],
            ['name' => 'Olivia Martinez',  'email' => 'olivia.martinez@company.com',  'role' => 'Employee',  'dept' => 'Engineering',       'desg' => 'Junior Software Engineer',   'team' => 'Backend Team',     'gender' => 'female', 'salary' => 52000],
            ['name' => 'David Kim',        'email' => 'david.kim@company.com',        'role' => 'Employee',  'dept' => 'Design',            'desg' => 'Senior UI/UX Designer',      'team' => 'Design Studio',    'gender' => 'male',   'salary' => 72000],
            ['name' => 'Jessica Brown',    'email' => 'jessica.brown@company.com',    'role' => 'Employee',  'dept' => 'Marketing',         'desg' => 'Digital Marketing Manager',  'team' => 'Growth Team',      'gender' => 'female', 'salary' => 68000],
            ['name' => 'Robert Taylor',    'email' => 'robert.taylor@company.com',    'role' => 'Employee',  'dept' => 'Sales',             'desg' => 'Account Manager',            'team' => 'Enterprise Sales', 'gender' => 'male',   'salary' => 62000],
            ['name' => 'Amanda Garcia',    'email' => 'amanda.garcia@company.com',    'role' => 'Employee',  'dept' => 'Quality Assurance', 'desg' => 'Senior QA Engineer',         'team' => 'QA Automation',    'gender' => 'female', 'salary' => 70000],
            ['name' => 'Chris Patel',      'email' => 'chris.patel@company.com',      'role' => 'Employee',  'dept' => 'DevOps',            'desg' => 'Senior DevOps Engineer',     'team' => 'Infrastructure',   'gender' => 'male',   'salary' => 82000],
            ['name' => 'Laura Thompson',   'email' => 'laura.thompson@company.com',   'role' => 'Employee',  'dept' => 'Human Resources',   'desg' => 'Talent Acquisition Lead',    'team' => null,               'gender' => 'female', 'salary' => 60000],
            ['name' => 'Daniel Lee',       'email' => 'daniel.lee@company.com',       'role' => 'Employee',  'dept' => 'Finance',           'desg' => 'Financial Analyst',          'team' => null,               'gender' => 'male',   'salary' => 58000],
        ];

        $adminUser = User::where('email', 'admin@example.com')->first();

        foreach ($seUsers as $i => $data) {
            $names = explode(' ', $data['name']);
            $dept = Department::where('name', $data['dept'])->first();
            $desg = Designation::where('name', $data['desg'])->first();
            $team = $data['team'] ? Team::where('name', $data['team'])->first() : null;

            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => Hash::make('password')]
            );
            $user->assignRole($data['role']);

            $empId = 'EMP-' . str_pad($i + 5, 5, '0', STR_PAD_LEFT);

            Employee::firstOrCreate(['user_id' => $user->id], [
                'user_id' => $user->id,
                'employee_id' => $empId,
                'first_name' => $names[0],
                'last_name' => $names[1],
                'phone' => '+1-555-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'gender' => $data['gender'],
                'date_of_birth' => now()->subYears(rand(25, 40))->subDays(rand(1, 365)),
                'department_id' => $dept->id,
                'designation_id' => $desg?->id,
                'team_id' => $team?->id,
                'manager_id' => $adminUser?->id,
                'joining_date' => now()->subMonths(rand(3, 36)),
                'employment_type' => 'full_time',
                'status' => 'active',
                'salary' => $data['salary'],
                'address' => fake()->address(),
            ]);
        }

        // ─── SAMPLE PROJECTS ─────────────────────────────────────────
        $sarahUser = User::where('email', 'sarah.johnson@company.com')->first();

        $projects = [
            ['name' => 'HRMS Platform',         'project_code' => 'HRM-001', 'status' => 'in_progress', 'priority' => 'high',   'progress' => 45, 'client' => 'Internal',          'description' => 'Internal HR management system with employee, leave, and attendance tracking.'],
            ['name' => 'E-Commerce Redesign',    'project_code' => 'ECR-001', 'status' => 'in_progress', 'priority' => 'high',   'progress' => 30, 'client' => 'Acme Corp',          'description' => 'Complete redesign of the client e-commerce platform.'],
            ['name' => 'Mobile Banking App',     'project_code' => 'MBA-001', 'status' => 'planning',    'priority' => 'urgent', 'progress' => 10, 'client' => 'FirstBank',           'description' => 'Cross-platform mobile banking application with biometric auth.'],
            ['name' => 'Analytics Dashboard',    'project_code' => 'AND-001', 'status' => 'completed',   'priority' => 'medium', 'progress' => 100,'client' => 'DataFlow Inc',        'description' => 'Real-time analytics dashboard with interactive charts.'],
            ['name' => 'CRM Integration',        'project_code' => 'CRM-001', 'status' => 'on_hold',     'priority' => 'low',    'progress' => 20, 'client' => 'Global Solutions',    'description' => 'Integrate third-party CRM with existing ERP system.'],
            ['name' => 'API Gateway v2',         'project_code' => 'API-002', 'status' => 'in_progress', 'priority' => 'high',   'progress' => 60, 'client' => 'Internal',            'description' => 'Next-generation API gateway with rate limiting and caching.'],
        ];

        $backendTeam = Team::where('name', 'Backend Team')->first();

        foreach ($projects as $proj) {
            Project::firstOrCreate(['project_code' => $proj['project_code']], array_merge($proj, [
                'project_manager_id' => $sarahUser?->id,
                'team_id' => $backendTeam?->id,
                'start_date' => now()->subMonths(rand(1, 6)),
                'end_date' => now()->addMonths(rand(1, 6)),
            ]));
        }

        // ─── SAMPLE TASKS ───────────────────────────────────────────
        $hrmProject = Project::where('project_code', 'HRM-001')->first();
        $ecrProject = Project::where('project_code', 'ECR-001')->first();
        $apiProject = Project::where('project_code', 'API-002')->first();

        $emilyUser = User::where('email', 'emily.davis@company.com')->first();
        $jamesUser = User::where('email', 'james.wilson@company.com')->first();
        $oliviaUser = User::where('email', 'olivia.martinez@company.com')->first();
        $amandaUser = User::where('email', 'amanda.garcia@company.com')->first();
        $davidUser = User::where('email', 'david.kim@company.com')->first();
        $chrisUser = User::where('email', 'chris.patel@company.com')->first();

        if ($hrmProject) {
            $hrmTasks = [
                ['task_id' => 'HRM-001', 'title' => 'Implement user authentication',     'status' => 'Completed',     'priority' => 'high',   'assigned_user_id' => $emilyUser?->id],
                ['task_id' => 'HRM-002', 'title' => 'Build employee CRUD module',         'status' => 'Completed',     'priority' => 'high',   'assigned_user_id' => $emilyUser?->id],
                ['task_id' => 'HRM-003', 'title' => 'Create leave management system',     'status' => 'QA',            'priority' => 'high',   'assigned_user_id' => $jamesUser?->id],
                ['task_id' => 'HRM-004', 'title' => 'Design attendance tracking UI',      'status' => 'QA Ready',      'priority' => 'medium', 'assigned_user_id' => $davidUser?->id],
                ['task_id' => 'HRM-005', 'title' => 'Implement role-based permissions',   'status' => 'Completed',     'priority' => 'urgent', 'assigned_user_id' => $emilyUser?->id],
                ['task_id' => 'HRM-006', 'title' => 'Build dashboard analytics widgets',  'status' => 'Backlog',       'priority' => 'medium', 'assigned_user_id' => $jamesUser?->id],
                ['task_id' => 'HRM-007', 'title' => 'File management module',             'status' => 'Backlog',       'priority' => 'low',    'assigned_user_id' => $oliviaUser?->id],
                ['task_id' => 'HRM-008', 'title' => 'Setup CI/CD pipeline',               'status' => 'Ready to Live', 'priority' => 'high',   'assigned_user_id' => $chrisUser?->id],
                ['task_id' => 'HRM-009', 'title' => 'Write API documentation',            'status' => 'Rework',        'priority' => 'medium', 'assigned_user_id' => $oliviaUser?->id],
                ['task_id' => 'HRM-010', 'title' => 'Integration testing suite',          'status' => 'QA',            'priority' => 'high',   'assigned_user_id' => $amandaUser?->id],
            ];

            foreach ($hrmTasks as $task) {
                Task::firstOrCreate(['task_id' => $task['task_id']], array_merge($task, [
                    'project_id' => $hrmProject->id,
                    'created_by' => $sarahUser?->id,
                    'description' => 'Task for the HRMS Platform project.',
                    'start_date' => now()->subDays(rand(5, 30)),
                    'due_date' => now()->addDays(rand(5, 30)),
                ]));
            }
        }

        if ($ecrProject) {
            $ecrTasks = [
                ['task_id' => 'ECR-001', 'title' => 'Redesign product listing page',     'status' => 'QA Ready',  'priority' => 'high',   'assigned_user_id' => $davidUser?->id],
                ['task_id' => 'ECR-002', 'title' => 'Implement cart & checkout flow',     'status' => 'Backlog',   'priority' => 'urgent', 'assigned_user_id' => $jamesUser?->id],
                ['task_id' => 'ECR-003', 'title' => 'Payment gateway integration',       'status' => 'Backlog',   'priority' => 'high',   'assigned_user_id' => $emilyUser?->id],
                ['task_id' => 'ECR-004', 'title' => 'Mobile responsive overhaul',        'status' => 'Backlog',   'priority' => 'medium', 'assigned_user_id' => $davidUser?->id],
            ];

            foreach ($ecrTasks as $task) {
                Task::firstOrCreate(['task_id' => $task['task_id']], array_merge($task, [
                    'project_id' => $ecrProject->id,
                    'created_by' => $sarahUser?->id,
                    'description' => 'Task for the E-Commerce Redesign project.',
                    'start_date' => now()->subDays(rand(1, 15)),
                    'due_date' => now()->addDays(rand(10, 45)),
                ]));
            }
        }

        if ($apiProject) {
            $apiTasks = [
                ['task_id' => 'API-001', 'title' => 'Design rate limiting middleware',    'status' => 'Live',      'priority' => 'high',   'assigned_user_id' => $emilyUser?->id],
                ['task_id' => 'API-002', 'title' => 'Implement Redis caching layer',     'status' => 'QA',        'priority' => 'high',   'assigned_user_id' => $chrisUser?->id],
                ['task_id' => 'API-003', 'title' => 'Build request validation layer',    'status' => 'Completed', 'priority' => 'medium', 'assigned_user_id' => $oliviaUser?->id],
            ];

            foreach ($apiTasks as $task) {
                Task::firstOrCreate(['task_id' => $task['task_id']], array_merge($task, [
                    'project_id' => $apiProject->id,
                    'created_by' => $sarahUser?->id,
                    'description' => 'Task for the API Gateway v2 project.',
                    'start_date' => now()->subDays(rand(5, 20)),
                    'due_date' => now()->addDays(rand(5, 20)),
                ]));
            }
        }
    }
}
