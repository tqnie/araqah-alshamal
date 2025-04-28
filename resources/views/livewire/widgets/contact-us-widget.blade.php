<?php 
use App\Models\Contact; 
use Livewire\Volt\Component;
use App\Livewire\Forms\ContactUsForm;
use Masmerise\Toaster\Toaster; 


new class extends Component
{
    public ContactUsForm $contactUsForm;
    public function mount()
    {   
        Toaster::success('تم إرسال الرسالة بنجاح!');
       
        
    }
    public function submitContactForm()
    {
     
        $this->contactUsForm->validate();
        Contact::create($this->contactUsForm->toArray());
        session()->flash('message', 'تم إرسال الرسالة بنجاح!');
        Toaster::success('تم إرسال الرسالة بنجاح!');
       
        $this->reset('contactUsForm');
    }

}; ?>
 
<div class="contact-form">
    <p class="subtitle">لديك سؤال؟</p>
    <h4>اكتب لنا رسالتك</h4>
    <form class="row" wire:submit.prevent="submitContactForm">
        <div class="col-12">
            <input type="text" wire:model="contactUsForm.subject" class="form-control" id="inputsubject" placeholder="الموضوع">
            @error('contactUsForm.subject') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="col-12">
            <input type="text" wire:model="contactUsForm.name" class="form-control" id="inputName" placeholder="الاسم">
            @error('contactUsForm.name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="col-sm-6">
            <input type="text" wire:model="contactUsForm.mobile" class="form-control" id="inputMobile" placeholder="رقم الجوال">
            @error('contactUsForm.mobile') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="col-sm-6">
            <input type="email"  wire:model="contactUsForm.email" class="form-control" id="inputEmail" placeholder="الايميل">
            @error('contactUsForm.email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="col-12">
            <textarea class="form-control"  wire:model="contactUsForm.body" id="textareaMessage" placeholder="تفاصيل الرسالة" rows="3"></textarea>
            @error('contactUsForm.body') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">تواصل معنا</button>
        </div>							
    </form>
</div>  
 