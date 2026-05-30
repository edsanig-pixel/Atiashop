<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChartOfAccountsSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Account::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $chart = [
            // ۱. دارایی های جاری
            ['code' => '1', 'name' => 'دارایی های جاری', 'level' => 1, 'children' => [
                ['code' => '101', 'name' => 'موجودی نقد و بانک', 'level' => 2, 'children' => [
                    ['code' => '10101', 'name' => 'بانک های ریالی', 'level' => 3],
                    ['code' => '10102', 'name' => 'صندوق ها', 'level' => 3],
                    ['code' => '10103', 'name' => 'تنخواه گردان ها', 'level' => 3],
                ]],
                ['code' => '103', 'name' => 'حسابها و اسناد دریافتنی تجاری', 'level' => 2, 'children' => [
                    ['code' => '10301', 'name' => 'حساب های دریافتنی (مشتریان)', 'level' => 3],
                    ['code' => '10302', 'name' => 'اسناد دریافتنی (چک ها)', 'level' => 3],
                    ['code' => '10303', 'name' => 'اسناد درجریان وصول', 'level' => 3],
                ]],
                ['code' => '105', 'name' => 'موجودی مواد و کالا', 'level' => 2, 'children' => [
                    ['code' => '10501', 'name' => 'موجودی کالا در انبار', 'level' => 3],
                ]],
            ]],

            // ۳. بدهی های جاری
            ['code' => '3', 'name' => 'بدهی های جاری', 'level' => 1, 'children' => [
                ['code' => '301', 'name' => 'حسابها و اسناد پرداختنی تجاری', 'level' => 2, 'children' => [
                    ['code' => '30101', 'name' => 'حساب های پرداختنی (تامین کنندگان)', 'level' => 3],
                    ['code' => '30102', 'name' => 'اسناد پرداختنی (چک های صادره)', 'level' => 3],
                ]],
                ['code' => '302', 'name' => 'سایر حسابها و اسناد پرداختنی', 'level' => 2, 'children' => [
                    ['code' => '30201', 'name' => 'جاری شرکاء', 'level' => 3],
                    ['code' => '30202', 'name' => 'وام های دریافتی کوتاه مدت', 'level' => 3],
                ]],
            ]],

            // ۵. حقوق صاحبان سهام
            ['code' => '5', 'name' => 'حقوق صاحبان سهام', 'level' => 1, 'children' => [
                ['code' => '501', 'name' => 'سرمایه', 'level' => 2, 'children' => [
                    ['code' => '50101', 'name' => 'سرمایه ثبتی', 'level' => 3],
                ]],
                ['code' => '505', 'name' => 'سود و زیان انباشته', 'level' => 2, 'children' => [
                    ['code' => '50501', 'name' => 'سود (زیان) سنواتی', 'level' => 3],
                ]],
            ]],

            // ۶. درآمدها
            ['code' => '6', 'name' => 'درآمدها', 'level' => 1, 'children' => [
                ['code' => '601', 'name' => 'فروش', 'level' => 2, 'children' => [
                    ['code' => '60101', 'name' => 'فروش محصولات', 'level' => 3],
                    ['code' => '60102', 'name' => 'فروش خدمات', 'level' => 3],
                ]],
            ]],

            // ۸. هزینه ها
            ['code' => '8', 'name' => 'هزینه ها', 'level' => 1, 'children' => [
                ['code' => '801', 'name' => 'هزینه حقوق و دستمزد', 'level' => 2, 'children' => [
                    ['code' => '80101', 'name' => 'حقوق پایه', 'level' => 3],
                    ['code' => '80102', 'name' => 'حق بیمه سهم کارفرما', 'level' => 3],
                ]],
                ['code' => '802', 'name' => 'هزینه های عملیاتی', 'level' => 2, 'children' => [
                    ['code' => '80201', 'name' => 'هزینه اجاره', 'level' => 3],
                    ['code' => '80202', 'name' => 'هزینه قبوض (آب، برق، گاز)', 'level' => 3],
                    ['code' => '80203', 'name' => 'هزینه تعمیرات و نگهداری', 'level' => 3],
                ]],
            ]],

            // ۹. سایر حساب ها
            ['code' => '9', 'name' => 'سایر حساب ها', 'level' => 1, 'children' => [
                ['code' => '903', 'name' => 'تراز افتتاحیه', 'level' => 2],
                ['code' => '904', 'name' => 'تراز اختتامیه', 'level' => 2],
            ]],
        ];

        $this->saveAccounts($chart);
    }

    private function saveAccounts(array $accounts, $parentId = null) {
        foreach ($accounts as $item) {
            $createdAccount = Account::create([
                'code' => $item['code'],
                'name' => $item['name'],
                'level' => $item['level'],
                'parent_id' => $parentId
            ]);

            if (isset($item['children'])) {
                $this->saveAccounts($item['children'], $createdAccount->id);
            }
        }
    }
}