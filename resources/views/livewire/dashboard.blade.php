<?php

use function Livewire\Volt\{state,layout};
use App\Models\Student;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use App\Models\Expense;
use App\Models\BookSell;


layout('layouts.app');





state([
    'total_student' => Student::count(),
    'total_academics' => Student::where('package_id', 1)->count(),
    'total_admission' => Student::where('package_id',"!=", 1)->count(),
    'total_amount_payable' => Payment::whereHas("student")
    ->when(!auth()->user()->can('reports.all'), function ($query) {
        return $query->where('recieved_by', auth()->user()->email);
    })
    ->sum(DB::raw('total-discount')),
    'total_collection' => Payment::whereHas("student")
    ->when(!auth()->user()->can('reports.all'), function ($query) {
        return $query->where('recieved_by', auth()->user()->email);
    })->sum('paid'),
    'total_book_collection' => BookSell::when(!auth()->user()->can('reports.all'), function ($query) {
        return $query->where('added_by', auth()->user()->email);
    })->sum('price'),
    'total_due' => Payment::whereHas("student")
    ->when(!auth()->user()->can('reports.all'), function ($query) {
        return $query->where('recieved_by', auth()->user()->email);
    })->sum("due"),
    'total_cost' => Expense::sum('amount'),
]);

?>

<div>
     <x-card title="Hi, {{auth()->user()->name}}">
    </x-card>
<hr>
<x-card title="" separator progress-indicator>


    @can('reports')

    <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(165px, 1fr)); gap: 10px; margin-bottom: 20px;">

            <div
                style="background: linear-gradient(135deg, #fb4141, #ece852);border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="font-size: 40px; margin-bottom: 10px;">{{number_format($total_student)}}</div>
                <p style="font-size: 14px;">Total Student</p>
            </div>

            <div
                style="background: linear-gradient(135deg, #173b45, #fcde70); border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="font-size: 40px; margin-bottom: 10px;">{{number_format($total_academics)}}</div>
                <p style="font-size: 14px;">Acadmics</p>
            </div>
            <div
                style="background: linear-gradient(135deg, #f72c5b, #a7d477); border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="font-size: 40px; margin-bottom: 10px;">{{number_format($total_admission)}}</div>
                <p style="font-size: 14px;">Admission</p>
            </div>
            <div
                style="background: linear-gradient(135deg, #640D5F, #FFB200); border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="font-size: 30px; margin-bottom: 10px;">{{number_format($total_amount_payable)}}</div>
                <p style="font-size: 14px;">Total Amount Payable</p>
            </div>
            <div
                style="background: linear-gradient(135deg, #9694ff, #ff8000); border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="font-size: 30px; margin-bottom: 10px;">{{number_format($total_collection)}}</div>
                <p style="font-size: 14px;">Total Collection</p>
            </div>
            <div
                style="background: linear-gradient(135deg, #7ed4ad, #ffa24c); border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="font-size: 30px; margin-bottom: 10px;">{{number_format($total_due)}}</div>
                <p style="font-size: 14px;">Total DUE</p>
            </div>
            <div
                style="background: linear-gradient(135deg, #6ec207, #ff6600); border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="font-size: 40px; margin-bottom: 10px;">{{number_format($total_book_collection)}}</div>
                <p style="font-size: 14px;">Total Book Collection</p>
            </div>
            @can('reports.all')
                <div
                style="background: linear-gradient(135deg, #4535c1, #ff6600); border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="font-size: 40px; margin-bottom: 10px;">{{number_format($total_cost)}}</div>
                <p style="font-size: 14px;">Total COST</p>
            </div>
            @endcan
    </div>
    <br>
    <br>

    @endcan
    <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; ">
        <a href="/admission/academics">
            <div
                style="background: linear-gradient(135deg, #123524, #ff6600); border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="font-size: 40px; margin-bottom: 10px;">👩🏽‍🏫</div>
                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">Academic</p>
                <p style="font-size: 14px;">Admission.</p>
            </div>
        </a>

        <a href="/admission/admission">
            <div
                style="background: linear-gradient(135deg, #cb9df0, #fa812f); border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="font-size: 40px; margin-bottom: 10px;"> 👩🏻‍🎓️</div>
                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">University </h3>
                <p style="font-size: 14px;">Admission.</p>
            </div>
        </a>

        <a href="/admission/dmc">
            <div
                style="background: linear-gradient(135deg, #ab4459, #3d3bf3); border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="font-size: 40px; margin-bottom: 10px;">👩‍⚕️</div>
                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">Medical</h3>
                <p style="font-size: 14px;">Admission.</p>
            </div>
        </a>

        <a href="/student/list">
            <div
                style="background: linear-gradient(135deg, #b1d690, #4c4b16); border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="font-size: 40px; margin-bottom: 10px;">🔯</div>
                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">Admitted</h3>
                <p style="font-size: 14px;">Student List.</p>
            </div>
        </a>

        <a href="/student/list">

            <div
                style="background: linear-gradient(135deg, #0d92f4, #ff6600); border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="font-size: 40px; margin-bottom: 10px;">💸</div>
                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">Fees </h3>
                <p style="font-size: 14px;">Collection.</p>
            </div>
        </a>
        <a href="/report/income">
            <div
                style="background: linear-gradient(135deg, #ff6600, #640d5f); border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="font-size: 40px; margin-bottom: 10px;">💰</div>
                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">Income </h3>
                <p style="font-size: 14px;">Report Print.</p>
            </div>
        </a>

        <a href="/book/sell">

            <div
                style="background: linear-gradient(135deg, #185519, #fcde70); border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="font-size: 40px; margin-bottom: 10px;">📖</div>
                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">TC Book</h3>
                <p style="font-size: 14px;">Add & Sells.</p>
            </div>
        </a>


        <a href="/expense/list">

            <div
                style="background: linear-gradient(135deg, #0d7c66, #3a1078); border-radius: 12px; color: #fff; text-align: center; padding: 20px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="font-size: 40px; margin-bottom: 10px;">🚀</div>
                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">Expense</h3>
                <p style="font-size: 14px;">Expense List.</p>
            </div>
        </a>



    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <div>

        <a href="https://wa.me/+8801893800001? text=Hello%20there!" target="_blank"
            style="
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
            onmouseover="this.style.backgroundColor='#20b358'" onmouseout="this.style.backgroundColor='#25D366'">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp Icon"
                style="
            width: 24px;
            height: 24px;
            margin-right: 10px;
        ">
            Chat With Admin
        </a>

    </div>

</x-card>
</div>
