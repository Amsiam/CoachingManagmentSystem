<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;
use Livewire\Attributes\{Layout, Title, Computed, Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\StudentReview;
use Livewire\WithFileUploads;

new
    #[Layout('layouts.app')]
    #[Title("Groups")]
    class extends Component
    {
        use Toast, WithPagination, WithFileUploads;

        #[Validate('required')]
        public $file = "";

        #[Validate([
            "student.name" => 'required',
            "student.exam_year" => 'required',
            "student.rank" => 'required',
            "student.desc" => 'required',
        ])]
        public $student;
        public bool $modal = false;

        #[Computed]
        public function sliders()
        {
            return StudentReview::paginate(20);
        }

        public function mount()
        {
            $this->student = new StudentReview();
        }

        public function modalClose()
        {
            $this->modal = false;
        }

        public function openModal()
        {
            $this->student = new StudentReview();
            $this->modal = true;
        }

        public function save()
        {
            $this->validate();
            $path = $this->file->store('public');
            $this->student->image = str_replace("public/", "", $path);
            $this->student->save();

            $this->success(title: "Added successfully");
            $this->file = "";
            $this->modalClose();
        }

        public function delete($id)
        {
            StudentReview::find($id)?->delete();
            $this->success(title: "Deleted successfully");
        }
    };
?>

<x-card title="কোর্স ইনফরমেশন">
    
    <style>
        .body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            margin: 0;
            padding: 20px;
        }

        .tabs-container {
            width: 100%;
            max-width: 900px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            padding: 25px;
            backdrop-filter: blur(10px);
            text-align: center;
        }

        .tab-buttons {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 10px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
        }

        .tab-buttons button {
            flex: 1;
            padding: 15px;
            border: none;
            cursor: pointer;
            background: #ffB800;
            font-size: 18px;
            font-weight: bold;
            
            border-radius: 12px;
            transition: all 0.3s ease;
            min-width: 120px;
        }

        .tab-buttons button i {
            display: block;
            font-size: 22px;
            margin-bottom: 5px;
        }

        .tab-buttons button:hover, .tab-buttons button.active {
            background: #ff9800;
            transform: scale(1.1);
        }

        .tab-content {
            margin-top: 20px;
            display: none;
            padding: 25px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            
            text-align: left;
            animation: fadeIn 0.5s ease-in-out;
        }

        .tab-content.active {
            display: flex;
        }

        .tab-section {
            margin-bottom: 15px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.2);
            border-left: 5px solid #ff9800;
            border-radius: 8px;
        }

        .tab-section h4 {
            margin: 0 0 5px;
            color: #ffcc00;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
            <div class="tabs-container">
                <div class="tab-buttons">
                    <button class="tab-link active" onclick="openTab(event, 'tab1')">
                        <i class="fa fa-home"></i> জুনিয়র সেকশন
                    </button> &nbsp
                    <button class="tab-link" onclick="openTab(event, 'tab2')">
                        <i class="fa fa-user"></i> নবম-দশম
                    </button> &nbsp
                    <button class="tab-link" onclick="openTab(event, 'tab3')">
                        <i class="fa fa-cogs"></i> একাদশ-দ্বাদশ
                    </button>&nbsp
                    <button class="tab-link" onclick="openTab(event, 'tab4')">
                        <i class="fa fa-chart-bar"></i> ভার্সিটি এডমিশন
                    </button>&nbsp
                    <button class="tab-link" onclick="openTab(event, 'tab5')">
                        <i class="fa fa-envelope"></i>Contact
                    </button>
                </div>
                
                <div id="tab1" class="tab-content active">
                    <br>
     
         <div style="width: 300px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center;">
        <h3 style="margin: 0; font-size: 22px; color: #333;">  ৫ম শ্রেণী   </h3>
        <p style="color: #666; font-size: 14px; margin: 10px 0;">
            
            
        সপ্তাহে ৫দিন নিয়মিত ক্লাস ।<br> ১দিন পরীক্ষা <br> শিফট-১: সকল ৭টা <br> শিফট-২: সকল ৯টা <br> শিফট-৩: দুপুর ২টা<br> শিফট-৪: বিকাল ৫টা 
            
            
            </p>
         
        <a href="#" style="display: inline-block; padding: 10px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; transition: 0.3s;"
           onmouseover="this.style.background='#0056b3'" onmouseout="this.style.background='#007bff'">
           মাসিক বেতন ১৮০০/-
        </a>
    </div>
       &nbsp   &nbsp
    <br>
       <div style="width: 300px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center;">
        <h3 style="margin: 0; font-size: 22px; color: #333;">  ৬ষ্ঠ থেকে ৮ম শ্রেণী   </h3>
        <p style="color: #666; font-size: 14px; margin: 10px 0;">
            
            
        সপ্তাহে ৫দিন নিয়মিত ক্লাস ।<br> ১দিন পরীক্ষা <br> শিফট-১: সকল ৭টা <br> শিফট-২: সকল ৯টা <br> শিফট-৩: দুপুর ২টা<br> শিফট-৪: বিকাল ৫টা 
            
            
            </p>
        <a href="#" style="display: inline-block; padding: 10px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; transition: 0.3s;"
           onmouseover="this.style.background='#0056b3'" onmouseout="this.style.background='#007bff'">
           মাসিক বেতন ২২০০/-
        </a>
    </div>
                    .</p>
                </div>
                <div id="tab2" class="tab-content">
                    
                         <div style="width: 300px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center;">
        <h3 style="margin: 0; font-size: 22px; color: #333;">   নবম- দশম  </h3>
        <p style="color: #666; font-size: 14px; margin: 10px 0;">
              সপ্তাহে ৫দিন নিয়মিত ক্লাস ।<br> ১দিন পরীক্ষা <br> শিফট-১: সকল ৭টা <br> শিফট-২: সকল ৯টা <br> শিফট-৩: দুপুর ২টা<br> শিফট-৪: বিকাল ৫ট
                          </p>
        <a href="#" style="display: inline-block; padding: 10px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; transition: 0.3s;"
           onmouseover="this.style.background='#0056b3'" onmouseout="this.style.background='#007bff'">
           মাসিক বেতন ২৫০০/-
        </a>
    </div>
                 
                    
                    

                </div>
                
                
                
                
                
                
                <div id="tab3" class="tab-content">
                    
                    
                    
                         <div style="width: 300px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center;">
        <h3 style="margin: 0; font-size: 22px; color: #333;">  একাদশ-দ্বাদশ <br> বিজ্ঞান বিভাগ </h3>
        <p style="color: #666; font-size: 14px; margin: 10px 0;">
            
           <h2> ভর্তি- ৪০০০/-</h2><br>
           সপ্তাহে ৬দিন ক্লাস<br>
           মাসিক মূল্যায়ণ পরীক্ষা ১টি<br>
           প্রতিদিন ২/৩ টি বিষয়ের ক্লাস<br>
           ১০ তারিখের মধ্যে বেতন পরিশোধ<br>
           টি-শার্ট, বই, ব্যাগ ও শিক্ষা উপকরণ
<br><br> 
  <a href="#" style="display: inline-block; padding: 10px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; transition: 0.3s;"
           onmouseover="this.style.background='#0056b3'" onmouseout="this.style.background='#007bff'">
           মাসিক বেতন 3700/-
        </a>
            
    
    </div>    &nbsp
                         <div style="width: 300px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center;">
        <h3 style="margin: 0; font-size: 22px; color: #333;">  একাদশ-দ্বাদশ <br> মানবিক বিভাগ </h3>
        <p style="color: #666; font-size: 14px; margin: 10px 0;">
            
           <h2> ভর্তি- ৪০০০/-</h2><br>
           সপ্তাহে ৬দিন ক্লাস<br>
           মাসিক মূল্যায়ণ পরীক্ষা ১টি<br>
           প্রতিদিন ২/৩ টি বিষয়ের ক্লাস<br>
           ১০ তারিখের মধ্যে বেতন পরিশোধ<br>
           টি-শার্ট, বই, ব্যাগ ও শিক্ষা উপকরণ
<br><br> 
  <a href="#" style="display: inline-block; padding: 10px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; transition: 0.3s;"
           onmouseover="this.style.background='#0056b3'" onmouseout="this.style.background='#007bff'">
           মাসিক বেতন 3200/-
        </a>
            
      
    </div>
                &nbsp
                         <div style="width: 300px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center;">
        <h3 style="margin: 0; font-size: 22px; color: #333;">  একাদশ-দ্বাদশ <br> ব্যবসায় শিক্ষা বিভাগ </h3>
        <p style="color: #666; font-size: 14px; margin: 10px 0;">
            
           <h2> ভর্তি- ৪০০০/-</h2><br>
           সপ্তাহে ৬দিন ক্লাস<br>
           মাসিক মূল্যায়ণ পরীক্ষা ১টি<br>
           প্রতিদিন ২/৩ টি বিষয়ের ক্লাস<br>
           ১০ তারিখের মধ্যে বেতন পরিশোধ<br>
           টি-শার্ট, বই, ব্যাগ ও শিক্ষা উপকরণ
<br><br> 
  <a href="#" style="display: inline-block; padding: 10px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; transition: 0.3s;"
           onmouseover="this.style.background='#0056b3'" onmouseout="this.style.background='#007bff'">
           মাসিক বেতন 3200/-
        </a>
            
      
    </div>  
                 
                <br>    
                   
                </div>
                <div id="tab4" class="tab-content">
                    <h3>Analytics</h3>
                    <p>View performance metrics.</p>
                </div>
                <div id="tab5" class="tab-content">
                    <h3>Contact</h3>
                    <p>Contact us for more information.</p>
                </div>
            </div>

            <script>
                function openTab(evt, tabName) {
                    document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = "none");
                    document.querySelectorAll('.tab-link').forEach(link => link.classList.remove("active"));
                    document.getElementById(tabName).style.display = "flex";
                    evt.currentTarget.classList.add("active");
                }
            </script>
    
</x-card>
