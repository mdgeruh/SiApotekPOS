<?php

namespace App\Http\Livewire\Medicine;

use Livewire\Component;
use App\Models\Medicine;

class QuickSearch extends Component
{
    public $searchQuery = '';
    public $showResults = false;
    public $searchResults = [];

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) >= 2) {
            $this->showResults = true;
            $this->searchResults = $this->getSearchResults();
        } else {
            $this->showResults = false;
            $this->searchResults = [];
        }
    }

    public function getSearchResults()
    {
        return Medicine::with(['category', 'brand'])
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->searchQuery . '%')
                      ->orWhere('code', 'like', '%' . $this->searchQuery . '%')
                      ->orWhere('description', 'like', '%' . $this->searchQuery . '%');
            })
            ->orderBy('name')
            ->limit(8)
            ->get();
    }

    public function performSearch()
    {
        if (strlen($this->searchQuery) >= 2) {
            $this->showResults = true;
            $this->searchResults = $this->getSearchResults();
        }
    }

    public function selectMedicine($medicineId)
    {
        $medicine = Medicine::find($medicineId);
        if ($medicine) {
            // Redirect to medicine detail or emit event
            $this->emit('medicineSelected', $medicine);
            $this->clearSearch();
        }
    }

    public function clearSearch()
    {
        $this->searchQuery = '';
        $this->showResults = false;
        $this->searchResults = [];
    }

    public function render()
    {
        return view('livewire.medicine.quick-search');
    }
}
