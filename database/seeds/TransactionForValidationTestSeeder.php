<?php

use App\Laravel\Models\AccountCode;
use App\Laravel\Models\Application;
use App\Laravel\Models\Customer;
use App\Laravel\Models\Department;
use App\Laravel\Models\Transaction;
use App\Laravel\Models\User;
use App\Laravel\Models\ZoneLocation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class TransactionForValidationTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        Department::truncate();
        Application::truncate();
        AccountCode::truncate();
        Transaction::truncate();
        Customer::truncate();
        ZoneLocation::truncate();

        $folder = 'public/uploads';

        if (File::exists($folder)) {
            File::deleteDirectories($folder);
            File::makeDirectory($folder, 0777, true, true);
        }

        $this->call(AdminAccountSeeder::class);

        $officeHead = factory(User::class)->states('office-head', 'active')->create([
            'fname' => 'Alice',
            'lname' => 'Alpha',
        ]);

        factory(Transaction::class, 25)->states('for-validation')->create([
            'department_id' => $officeHead->department_id,
        ]);

        $processor = factory(User::class)->states('processor', 'active')->create([
            'fname' => 'Bob',
            'lname' => 'Bravo',
        ]);

        $applicationIds = explode(',', $processor->application_id);

        $statuses = ['for-validation', 'approved', 'declined', 'resent'];

        foreach ($applicationIds as $applicationId) {
            foreach ($statuses as $status) {
                factory(Transaction::class, 10 - $applicationId)->states($status)->create([
                    'department_id' => $processor->department_id,
                    'application_id' => $applicationId,
                ]);
            }
        }

        Customer::first()->update([
            'fname' => 'charlie',
            'lname' => 'charlie',
            'email' => 'charlie@mail.com',
        ]);
    }
}
