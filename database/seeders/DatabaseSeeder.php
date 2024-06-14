<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use App\Models\Group;
use App\Models\Package;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
        ]);

        Group::create(["name"=>"Science"]);
        Group::create(["name"=>"Arts"]);
        Group::create(["name"=>"Commerce"]);


        Package::create(["name"=>"Academics"]);
        Package::create(["name"=>"Admission"]);
        Package::create(["name"=>"DMC Scholar"]);

        $expenseCategories=[
            "স্টাফদের বেতন",
        "TC  শিক্ষকদের বেতন",
        "DMC শিক্ষকদের বেতন",
        "TC মার্কেটিং",
        "DMC মার্কেটিং",
        "স্টাফ ও শিক্ষকদের নাস্তা","স্টেশনারি","মেইন্টেন্যান্স","বিল ও অফিস ভাড়া"];

        foreach ($expenseCategories as $ec) {
            ExpenseCategory::create(["name"=>$ec]);
        }

        $permissions = array(
            array('name' => 'academics.batch','guard_name' => 'web'),
  array('name' => 'academics.class','guard_name' => 'web'),
  array('name' => 'academics.course','guard_name' => 'web'),
  array('name' => 'academics.group','guard_name' => 'web'),
  array('name' => 'admin.create','guard_name' => 'web'),
  array('name' => 'admin.list','guard_name' => 'web'),
  array('name' => 'book.list','guard_name' => 'web'),
  array('name' => 'book.sell','guard_name' => 'web'),
  array('name' => 'expense.category.create','guard_name' => 'web'),
  array('name' => 'expense.category.list','guard_name' => 'web'),
  array('name' => 'expense.create','guard_name' => 'web'),
  array('name' => 'expense.list','guard_name' => 'web'),
  array('name' => 'expense.salary.list','guard_name' => 'web'),
  array('name' => 'pay','guard_name' => 'web'),
  array('name' => 'payment.delete','guard_name' => 'web'),
  array('name' => 'payment.edit','guard_name' => 'web'),
  array('name' => 'permission.list','guard_name' => 'web'),
  array('name' => 'report.admission','guard_name' => 'web'),
  array('name' => 'report.excel','guard_name' => 'web'),
  array('name' => 'report.income','guard_name' => 'web'),
  array('name' => 'report.monthly','guard_name' => 'web'),
  array('name' => 'role.list','guard_name' => 'web'),
  array('name' => 'student.academics','guard_name' => 'web'),
  array('name' => 'student.admission','guard_name' => 'web'),
  array('name' => 'student.admissionRequest','guard_name' => 'web'),
  array('name' => 'student.delete','guard_name' => 'web'),
  array('name' => 'student.dmc','guard_name' => 'web'),
  array('name' => 'student.edit','guard_name' => 'web'),
  array('name' => 'student.list','guard_name' => 'web')
          );

        foreach ($permissions as $pp) {
            Permission::create(["name"=>$pp["name"],"guard_name"=>$pp["guard_name"]]);
        }

        $allPermissions = Permission::all(["name"])->pluck("name");

        $role = Role::create(["name"=>"Admin","guard_name"=>"web"]);

        $role->syncPermissions($allPermissions);
        $admin->syncRoles([$role->name]);

    }
}
