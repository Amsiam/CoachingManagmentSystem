<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\AdmissionRequest;

new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination;

     #[Validate('required')]
    public $name="";
    public bool $modal = false;

     #[Computed]
    public function requests()
    {
        return  AdmissionRequest::latest()->paginate(20);
    }

    public function done($id){
        AdmissionRequest::find($id)->update(["active"=>0]);

        $this->success(title:"Action taken successfully");
    }



};

?>



<x-card title="Admission Request" separator progress-indicator>

    <x-table :headers='[
        ["key"=>"id","label"=>"#"],
        ["key"=>"name","label"=>"Name"],
        ["key"=>"smobile","label"=>"Student Mobile",],
        ["key"=>"gmobile","label"=>"Guardian Mobile",],
        ["key"=>"course.name","label"=>"Course Mobile",],
        ["key"=>"package.name","label"=>"Package Mobile",],
    ]' :rows="$this->requests" with-pagination >

    @scope("cell_id",$request)
    {{$this->loop->index+1}}
    @endscope

    @scope('actions', $request)
    @if ($request->active==1)
    <x-button wire:confirm="Are you sure?" icon="o-check" wire:click="done({{ $request->id }})" spinner class="btn-sm btn-success text-white" />

    @endif
       @endscope
    </x-table>
</x-card>
