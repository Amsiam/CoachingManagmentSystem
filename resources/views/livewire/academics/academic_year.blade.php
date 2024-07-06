<?php

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\AcademicYear;

new
#[Layout('layouts.app')]
#[Title("Groups")]
class extends Component {
    use Toast,WithPagination;

    public $academic_year = [];
    public bool $modal = false;

    public function rules()
    {
        return [
            'academic_year.year' => 'required',
            'academic_year.active' => '',
        ];
    }

     #[Computed]
    public function academic_years()
    {
        return  AcademicYear::latest()->paginate(20);
    }

    public function modalClose(){
        $this->modal=false;
    }

    public function modalOpen($id=null){

        if(isset($id)){
            $this->academic_year = AcademicYear::find($id);
        }else{
            $this->academic_year = new AcademicYear();
            $this->academic_year->active = true;
        }

        $this->modal=true;
    }

    public function save(){

        $this->validate();

        $this->academic_year->save();

        $this->success(title:"Added successfully");

        $this->modalClose();


    }

    public function delete($id){
        AcademicYear::find($id)->delete();

        $this->success(title:"Deleted successfully");
    }



};

?>



<x-card title="Academic Year" separator progress-indicator>
    <div class="flex justify-end">
        <x-modal wire:model="modal" title="Add Academic Year" class="backdrop-blur">

            <x-form wire:submit.prevent="save">

            <x-input label="Year" wire:model="academic_year.year" />
            <x-checkbox label="Status" wire:model="academic_year.active" />



            <x-slot:actions>
                {{-- Notice `onclick` is HTML --}}
                <x-button label="Cancel" @click="$wire.modal = false" />
                <x-button type="submit" label="Save" class="btn-primary" />
            </x-slot:actions>
        </x-form>
        </x-modal>

        {{-- Notice `onclick` is HTML --}}
        <x-button label="Add Academic Year" class="btn-primary btn-sm" wire:click="modalOpen()" />
    </div>
    <x-table :headers='[
        ["key"=>"id","label"=>"#"],
        ["key"=>"year","label"=>"Year"],
        ["key"=>"active","label"=>"Status"],
    ]' :rows="$this->academic_years" with-pagination >

    @scope("cell_id",$academic_year)
    {{$this->loop->index+1}}
    @endscope

    @scope("cell_active",$academic_year)
    @if ($academic_year->active)
        <div class="btn btn-xs btn-success">Active</div>
    @else
    <div class="btn btn-xs btn-error">Disabled</div>
    @endif
    @endscope

    @scope('actions', $academic_year)
    <div class="flex gap-1">
        <x-button icon="o-pencil-square" class="btn-primary btn-xs" wire:click="modalOpen({{$academic_year->id}})" />
            <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $academic_year->id }})" spinner class="btn-xs btn-error text-white" />

    </div>
    @endscope
    </x-table>
</x-card>
