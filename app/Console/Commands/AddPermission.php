<?php

namespace App\Console\Commands;

use App\Models\Permission\Permission;
use Illuminate\Console\Command;

class AddPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:add {name} {--allowed-roles=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add permission to database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $permission = Permission::create([
            'name' => $name,
            'guard_name' => 'web',
        ]);
        $this->info('Permission created.');

        if ($allowedRoles = $this->option('allowed-roles')) {
            foreach (explode(',', $allowedRoles) as $role) {
                $permission->assignRole($role);
            }
        }
    }
}
