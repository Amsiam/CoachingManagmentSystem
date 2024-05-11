


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>tusharcare</title>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@500;700&display=swap" rel="stylesheet">

    <style>


*{
  margin: 0px;
  padding: 0px;
  box-sizing: border-box;
  font-family: 'Hind Siliguri', sans-serif;
  text-decoration: none;
 --textwhite:white;
 --textback: #000000;
 --border:#333131;
}

.container{
  background: rgba(246, 247, 248, 0.8);
  width:5.1in;
  height: 7.3in;
}
.form{
    border: 2px solid var(--border);
}
.contain{
    padding: 20px;
}
.top-header, .left{
    display: flex;
    justify-content: space-between;
    column-gap: .7rem;
}

/* logopart start  */
.logopart{
    display: flex;
    column-gap: .7rem;
}
.logopart .logo{
    padding: 5px;
    border-radius: 10px;
    border: 2px solid var(--border);
}
.logopart .logo h4{
    font-size: .5rem;
    font-weight: 600;
    text-align: center;
}
.logopart .logo img{
height: 50px;
width: 65px;
}

.logopart .text h3{
    font-family:'Hind Siliguri', sans-serif;
    font-size: .9rem;
    color: var(--textback);
}
.logopart .text h1{
    font-family:'Hind Siliguri', sans-serif;
    font-size: 1.4rem;
    color:var(--bg-color);
    margin: -5px 0;
    font-weight: 800;
}


/* logopart end */
h1{
    font-size: 1.3rem;
    font-weight: 800;
}
.text{
    text-align: left;
}

.text h1{
 font-family: 'Hind Siliguri', sans-serif;
}
.text h3{
    font-size: .8rem;
}
.text h4{
    font-size: .47rem;
}
.horizontal{
    display: flex;
}

.border{
    background-color: var(--border);
    height: 3px;
    margin: 2px 0;
    border: 0 none;
    width:160px;
}
.money{
    width: 170px;
    padding: 2px 0;
    border-radius: 10px;
    border: 1px solid var(--textback);
    text-align: center;
margin-top: -10px;
}
.money h1{
    font-size: 13px;
font-family: 'Hind Siliguri', sans-serif;
text-transform: uppercase;
}

.details-upper{
    margin-top: -10px;
    display: flex;
    justify-content: space-between;
}

.detail-right img{
    height: 45px;
    width: 160px;
}
.details-right h3{
width: 160px;
}
span{
    padding: 0 10px;
}
.details-lower{

    display: flex;
    justify-content: space-between;
}
.details-upper h3 , .details-lower h3{
    color: var(--textback);
    font-size:.8rem;
    font-family:'Hind Siliguri', sans-serif;
    font-weight: 600;
}
.table{
    font-size: .8rem;
    height: 165px;
    margin-top: -30px;
    width: 100%;
    border: 2px solid var(--textback);
    border-collapse: collapse;
    text-align: center;
}
.table th{
    padding: 2px;
    height: 5px;
}
.table td,th{
    border: 1px solid var(--textback);
}

.table2{
    font-size: .8rem;
    margin-top: -30px;
    color: #000000;
    font-weight: 600;
    text-align: center;
    border-collapse: collapse;
}
.table2 td{
    border: 1px solid var(--textback);
    width: 90px;
    padding: 2px 0px;
}
.detail{
    margin-top: -20px;
}

/* signature start */

.sign{
    font-size: .8rem;
    margin-top: 30px;
    display: flex;
    justify-content: space-between;
}

.signature{

    text-align: center;

}

.footer{
    text-align: center;
    padding: 9px;
    background-color: var(--border);
}
.footer h3{
    color: var(--textwhite);
    font-size: .9rem;
}
    </style>
</head>
<body>

<div class="container">

<div class="form">

    <div class="contain">
        <div class="top-header">



            <div class="logopart">
                @if ($payment->student->package_id==3)
                <img src="{{asset('assets/pdf/form/images/d.png')}}" width="225" alt="" >
                @else
                <img src="{{asset('assets/pdf/form/images/l.png')}}" width="225" alt="" >
                @endif
            </div>

            <div class="right">
                <h1>01714-160997</h1>
                <h1>01734-500238</h1>

            </div>

            </div>
    </div>

    <!-- header end -->

    <div style="margin-top: -15px;" class="horizontal">
        <hr class="border">
        <div class="money">
            <h1>Money receipt</h1>
        </div>
        <hr class="border">
    </div>
<!-- horizontal end -->

<div class="contain">
   <div class="details-upper">



    <div class="detail-left">

        <h3>Bill NO <span style="padding-left: 33px;">:</span> {{$payment->id}}</h3>
        <h3>Bill Date <span style="padding-left: 24px;">:</span> {{$payment->created_at}}</h3>

    </div>
    <div class="detail-right">
       {!! $barCode !!}
    </div>
   </div>



   <div class="details-lower">
    <div class="detail-left">
        <h3>Name <span style="padding-left: 39px;">:</span> {{$payment->student->name}}</h3>
        <h3>Address <span  style="padding-left: 22px;">:</span> {{$payment->student->personalDetails->paddess}}</h3>
        <h3>Mobile No  <span style="padding-left: 12px;">:</span> {{$payment->student->personalDetails->smobile}}</h3>
        <h3>Batch No<span style="padding-left: 20px;">:</span>
            @foreach ($payment->student->batches as $batch)
            @if($loop->iteration!=1),@endif
                {{$batch->name}}
            @endforeach
         </h3>
    </div>
    <div class="details-right">
        <h3>Roll No <span>:</span> {{$payment->student->roll}}</h3>
    </div>

   </div>
</div>

<!-- table star -->

<div class="contain">
    <table class="table">
        <tr>
            <th width="10%">SL No</th>
            <th  width="70%">Description</th>
            <th width="20%">Price(TK.)</th>
        </tr>
        @php
            $total = 0;
        @endphp
        @if($payment->paymentType==2)

        @foreach ($payment->student->courses as $course)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$course->name}}</td>
            <td>
                {{$course->price}}
                @php
                    $total += $course->price;
                @endphp
            </td>
        </tr>
        @endforeach

        @elseif ($payment->paymentType==0)
        <tr>
            <td>1</td>
            <td>মাসিক বেতন ({{date("F",strtotime($payment->month))}})</td>
            <td>
                {{$payment->total}}
                @php
                    $total += $payment->total;
                @endphp
            </td>
        </tr>
        @else
        <tr>
            <td>1</td>
            <td>আগের বকেয়া</td>
            <td>{{-1*$payment->due}}
                @php
                $total += -1*$payment->due;
            @endphp
            </td>
        </tr>

        @endif
    </table>
</div>


<div class="contain">
    <div class="details-upper">
     <div class="detail">
        <h3>Remark: <span>:</span> {{$payment->remarks}}</h3>
        @if ($payment->due_date)
            <h3>Due Date: <span>:</span> {{$payment->due_date}}</h3>
        @endif

        <br>
            <h1><strong>
                @if ($payment->due>0)
                    Partially Paid
                    @else
                    Paid
                @endif

            </strong></h1>

     </div>
     <div class="detail-right">

        <table class="table2" >
            <tr>
                <td>Sub Total Tk.</td>
                <td class="auto">{{$total}}</td>
            </tr>
            <tr>
                <td>Discount Tk.</td>
                <td class="auto">{{$payment->discount}}</td>
            </tr>
          <tr>
            <td>Payment Tk.</td>
            <td class="auto">{{$payment->paid}}</td>
          </tr>
          <tr>
            <td>Due. Tk.</td>
            <td class="auto">

                @if ($payment->due>0)
                {{$payment->due}}
                @endif
            </td>

          </tr>
     </table>
     </div>
    </div>
</div>

<!-- alltable end -->

<div style="padding-bottom: 0;" class="contain">
<div class="sign">
    <div class="name">
        <h3>Prepared By <span>:</span> {{$payment->recieved_by}}</h3>
    </div>
    <div class="signature">
        <hr class="border">
     <h3>Signature of acceptor</h3>
    </div>
</div>
</div>



<div class="footer">
    <h3>Transaction related to admission are non-refundable.</h3>
</div>





</div>


</body>
</html>
