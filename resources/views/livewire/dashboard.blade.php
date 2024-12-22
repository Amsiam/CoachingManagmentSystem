<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\User;

use App\Models\Payment;




use App\Exports\IncomeExport;


new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {

};

?>



<x-card title="Dashboard" separator progress-indicator>

    <x-card title="Hi, {{auth()->user()->name}}">
    </x-card>


       <font face="hind siliguri" size="6px" color="#f5eef8"> <b>
        </b>
          </font> 
       
        
 <a href="https://tcnew.tusherscarebd.com/admission/academics">
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; width: 90%; max-width: 800px;">
    <div style="background: #ff6f61; border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
      <div style="font-size: 40px; margin-bottom: 10px;">👩🏽‍🏫</div>
      <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">Academic</h3>
      <p style="font-size: 14px;">Admission.</p>
    </div>
    </a>
   
      <a href="https://tcnew.tusherscarebd.com/admission/admission">
    <div style="background: #42a5f5; border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
      <div style="font-size: 40px; margin-bottom: 10px;"> 👩🏻‍🎓️</div>
      <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">University </h3>
      <p style="font-size: 14px;">Admission.</p>
    </div>
    </a>
    
      <a href="https://tcnew.tusherscarebd.com/admission/dmc">
    <div style="background: #66bb6a; border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
      <div style="font-size: 40px; margin-bottom: 10px;">👩‍⚕️</div>
      <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">Medical</h3>
      <p style="font-size: 14px;">Admission.</p>
    </div>
    </a>
    
    <a href="https://tcnew.tusherscarebd.com/student/list">
    <div style="background: #ffa726; border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
      <div style="font-size: 40px; margin-bottom: 10px;">🔯</div>
      <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">Admitted</h3>
      <p style="font-size: 14px;">Student List.</p>
    </div>
    </a>
    
    <a href="https://tcnew.tusherscarebd.com/student/list">
  
    <div style="background: #ab47bc; border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
      <div style="font-size: 40px; margin-bottom: 10px;">💸</div>
      <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">Fees </h3>
      <p style="font-size: 14px;">Collection.</p>
    </div>
    </a>
  <a href="https://tcnew.tusherscarebd.com/report/income">
    <div style="background: #ef5350; border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
      <div style="font-size: 40px; margin-bottom: 10px;">💰</div>
      <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">Income </h3>
      <p style="font-size: 14px;">Report Print.</p>
    </div>
    </a>
    
     <a href="https://tcnew.tusherscarebd.com/book/sell">
  
    <div style="background: #770BC1; border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
      <div style="font-size: 40px; margin-bottom: 10px;">📖</div>
      <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">TC Book</h3>
      <p style="font-size: 14px;">Add & Sells.</p>
    </div>
    </a>
    
    
      <a href="https://tcnew.tusherscarebd.com/expense/list">
  
    <div style="background: #F407EC; border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
      <div style="font-size: 40px; margin-bottom: 10px;">🚀</div>
      <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">Expense</h3>
      <p style="font-size: 14px;">Expense List.</p>
    </div>
    </a>
    
   
    
  </div>
<br>
<br>
<br>
<font face="Hind Siliguri" size="5.5px">“যে নিজের প্রতি সৎ, তার কাছে মিথ্যা আর ফাঁকি কোনোদিন জায়গা পায় না।”

 </font>
 
    
    <div class="grid grid-cols-4 gap-2">
      

<br>
<div>






</a>


</div>


{{--
    <x-card class="shadow-lg" title="Total Student, {{date('Y')}} " shadow>
        <div class="text-center font-bold">1000</div>
    </x-card> --}}

    </div>
    
  
              
                <br>
                  <br>
                    <br>
                      <br>
<div >
    
        <a href="https://wa.me/+8801893800001? text=Hello%20there!" target="_blank" style="
        background-color: #25D366; /* WhatsApp green */
        color: white;
        padding: 10px 20px;
        text-align: center;
        float: right;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        font-size: 20px;
        font-family: Arial, sans-serif;
        border-radius: 30px;
        transition: all 0.3s ease; /* Smooth transition */
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    " 
    onmouseover="this.style.backgroundColor='#20b358'"
    onmouseout="this.style.backgroundColor='#25D366'">
       <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp Icon" style="
            width: 24px;
            height: 24px;
            margin-right: 10px;
        ">
        Chat With Admin
    </a>
    
</div>

</x-card>
