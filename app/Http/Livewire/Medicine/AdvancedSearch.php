<?php

namespace App\Http\Livewire\Medicine;

use Livewire\Component;
use App\Models\Medicine;
use Illuminate\Support\Str;

class AdvancedSearch extends Component
{
    public $searchQuery = '';
    public $showSuggestions = false;
    public $searchSuggestions = [];
    public $selectedMedicine = null;
    public $totalResults = 0;

    protected $listeners = ['medicineSelected' => 'setSelectedMedicine'];

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) >= 2) {
            $this->showSuggestions = true;
            $this->searchSuggestions = $this->getSearchSuggestions();
            $this->totalResults = $this->getTotalResults();
        } else {
            $this->showSuggestions = false;
            $this->searchSuggestions = [];
        }
    }

    public function getSearchSuggestions()
    {
        return Medicine::with(['category', 'brand', 'unit', 'manufacturer'])
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->searchQuery . '%')
                      ->orWhere('code', 'like', '%' . $this->searchQuery . '%')
                      ->orWhere('description', 'like', '%' . $this->searchQuery . '%');
            })
            ->orderBy('name')
            ->limit(10)
            ->get();
    }

    public function getTotalResults()
    {
        return Medicine::where(function($query) {
            $query->where('name', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('code', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('description', 'like', '%' . $this->searchQuery . '%');
        })->count();
    }

    public function selectSuggestion($medicineId)
    {
        $this->selectedMedicine = Medicine::with(['category', 'brand', 'unit', 'manufacturer'])->find($medicineId);
        $this->showSuggestions = false;
        $this->searchQuery = $this->selectedMedicine->name;

        // Emit event to parent component
        $this->emit('medicineSelected', $this->selectedMedicine);
    }

    public function clearSearch()
    {
        $this->searchQuery = '';
        $this->showSuggestions = false;
        $this->searchSuggestions = [];
        $this->selectedMedicine = null;
    }

    public function clearSelection()
    {
        $this->selectedMedicine = null;
        $this->searchQuery = '';
        $this->emit('medicineDeselected');
    }

    public function viewAllResults()
    {
        $this->emit('viewAllSearchResults', $this->searchQuery);
    }

    public function render()
    {
        return view('livewire.medicine.advanced-search');
    }
}
