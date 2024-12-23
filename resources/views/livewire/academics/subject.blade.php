<?php

use Livewire\WithPagination;

use Livewire\Attributes\{Layout, Title,Computed,Validate};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\{Group,Subject};

new
#[Layout('layouts.app')]
#[Title("Subjects")]
class extends Component {
    use Toast,WithPagination;


    public $subject ;
    public bool $modal = false;
    public string $search = '';

    public function rules()
    {
        return [
            'subject.name' => 'required',
            'subject.group_id' => 'required',
            'subject.auto_selected' => '',
        ];
    }

    public function mount(){

        $this->subject = new Subject();
    }

  #[Computed]
  public function groups()
    {
        return Group::all();
    }

    #[Computed]
    public function subjects()
    {
        return Subject::with('group')
        ->when($this->search,function($query){
            $query->where('name','like','%'.$this->search.'%')
            ->orWhereHas('group',function($q){
                $q->where('name','like','%'.$this->search.'%');
            });
        })
        ->paginate(20);
    }

    public function modalClose()
    {
        $this->modal = false;
    }

    public function modalOpen($id=null)
    {

        if($id){
            $this->subject = Subject::find($id);
        }else{
            $this->subject = new Subject();
        }

        $this->modal = true;
    }

    public function save()
    {
        // dd($this->subject);
        $this->validate();


        $this->subject->save();


        $this->success(title: 'Added successfully');

        $this->modalClose();
    }

    public function delete($id)
    {
        Subject::find($id)->delete();

        $this->success(title: 'Deleted successfully');
    }

}


?>

<x-card title="Subject" separator progress-indicator>
    <div class="flex justify-end">
        <x-modal wire:model="modal" title="Add Subject" class="backdrop-blur">

            <x-form wire:submit.prevent="save">

                <x-input label="Name" wire:model="subject.name" />

                <x-choices-offline searchable label="Groups" wire:model="subject.group_id" :options="$this->groups" single />

                <x-checkbox label="Auto Selected?" wire:model="subject.auto_selected"  />
                <x-slot:actions>
                    {{-- Notice `onclick` is HTML --}}
                    <x-button label="Cancel" @click="$wire.modal = false" />
                    <x-button type="submit" label="Save" class="btn-primary" />
                </x-slot:actions>
            </x-form>
        </x-modal>

        {{-- Notice `onclick` is HTML --}}
        <x-button label="Add Subject" class="btn-primary btn-sm" wire:click="modalOpen" />
    </div>


    <x-input class="input-sm mt-1" wire:model.live.debounce.500ms="search" placeholder="Search..." />
    <x-table :headers="[
        ['key' => 'id', 'label' => '#'],
        ['key' => 'name', 'label' => 'Subject Name',],
        ['key' => 'group.name', 'label' => 'Group', ],
        ['key' => 'auto_selected', 'label' => 'Auto Selected', ],
    ]" :rows="$this->subjects" with-pagination>

        @scope('cell_id', $subject)
            {{ $this->loop->index + 1 }}
        @endscope

        @scope('cell_auto_selected', $subject)
            {{ $subject->auto_selected ? 'Yes' : 'No' }}
        @endscope

        @scope('actions', $subject)<div class="flex gap-1">
            <x-button icon="o-pencil-square" class="btn-primary btn-xs" wire:click="modalOpen({{$subject->id}})" />

            <x-button wire:confirm="Are you sure?" icon="o-trash" wire:click="delete({{ $subject->id }})" spinner
                class="btn-xs btn-error text-white" />
                </div>
        @endscope
    </x-table>

</x-card>
