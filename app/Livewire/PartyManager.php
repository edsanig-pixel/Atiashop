<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Party;
use Livewire\WithPagination;

class PartyManager extends Component
{
	
    use WithPagination;

    // فیلدهای متناظر با دیتابیس و فرم
    public $partyId;
    public $real = false; // برای انتخاب "حقیقی"
    public $legal = false; // برای انتخاب "حقوقی"
	public $type = 'real'; // حقیقی یا حقوقی
    public $first_name;
    public $last_name;
    public $name; // نام نمایشی
    public $mobile;
    public $national_code;
    public $address;
    public $is_customer = true;
    public $is_supplier = false;
    public $is_employee = false;
    public $showModal = false;
    public $viewMode = false;
	public $showDeleteModal = false; // ✅ اضافه شده
	public $deleteId = null; // ✅ اضافه شده

    // قوانین اعتبارسنجی با پیام‌های فارسی
    protected function rules()
    {
        return [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'name' => 'required|min:3',
            'mobile' => 'nullable|numeric|digits:11',
            'national_code' => 'nullable|numeric|digits:10',
            'type' => 'required|in:real,legal',
        ];
    }

    protected $messages = [
        'first_name.required' => 'وارد کردن نام الزامی است.',
        'first_name.min' => 'نام نباید کمتر از 3 حرف باشد',
        'last_name.required' => 'وارد کردن نام خانوادگی الزامی است.',
		'last_name.min' => 'نام خانوادگی نباید کمتر 3 حرف باشد.',
        'name.required' => 'نام نمایشی نمی‌تواند خالی باشد.',
        'name.min' => 'نام نمایشی نباید کم تر از 3 حرف باشد.',
        'mobile.numeric' => 'شماره موبایل باید عدد باشد.',
        'mobile.digits' => 'شماره موبایل باید ۱۱ رقم باشد.',
        'national_code.numeric' => 'شماره ملی باید عدد باشد.',
        'national_code.digits' => 'شماره ملی باید 10 رقم باشد.',
    ];

    // متد باز کردن فرم برای ایجاد یا ویرایش
    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->resetFields();
        $this->viewMode = false;

        if ($id) {
            $this->partyId = $id;
            $party = Party::find($id);
            $this->type = $party->type;
            $this->first_name = $party->first_name;
            $this->last_name = $party->last_name;
            $this->name = $party->name;
            $this->mobile = $party->mobile;
            $this->national_code = $party->national_code;
            $this->address = $party->address;
            $this->is_customer = (bool)$party->is_customer;
            $this->is_supplier = (bool)$party->is_supplier;
            $this->is_employee = (bool)$party->is_employee;
        }

        $this->showModal = true;
    }

    // متد مشاهده جزئیات (بدون قابلیت ویرایش)
    public function openViewModal($id)
    {
        $this->openModal($id);
        $this->viewMode = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetFields();
    }

    private function resetFields()
    {
        $this->partyId = null;
        $this->type = '';
        $this->first_name = '';
        $this->last_name = '';
        $this->name = '';
        $this->mobile = '';
        $this->national_code = '';
        $this->address = '';
        $this->is_customer = true;
        $this->is_supplier = false;
        $this->is_employee = false;
    }

    // عملیات ذخیره نهایی (دقیقاً مشابه منطق حسابداری)
    public function store()
    {
        $this->validate();
$this->type = $this->legal ? 'legal' : 'real';
        Party::updateOrCreate(['id' => $this->partyId], [
            'type' => $this->type,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' => $this->name,
            'code' => $this->partyId ? Party::find($this->partyId)->code : Party::generateNextCode(),
            'mobile' => $this->mobile,
            'national_code' => $this->national_code,
            'address' => $this->address,
            'is_customer' => $this->is_customer,
            'is_supplier' => $this->is_supplier,
            'is_employee' => $this->is_employee,
            'status' => true,
        ]);

        $this->closeModal();
        
        // ارسال پیام موفقیت به فارسی
        session()->flash('message', 'اطلاعات شخص با موفقیت ذخیره شد.');
    }

    public function delete($id)
    {
        Party::find($id)->delete();
    }
	
// این متد را اضافه کنید
public function openDeleteModal($id)
{
	
    $this->deleteId = $id;
    $this->showDeleteModal = true;
	//dd($this->showDeleteModal);
}

// این متد را اضافه کنید
public function confirmDelete()
{
    $this->delete($this->deleteId);
    $this->showDeleteModal = false;
}
    public function render()
    {
        return view('livewire.party-manager', [
            'parties' => Party::orderBy('id', 'desc')->paginate(10)
        ]);
    }
}