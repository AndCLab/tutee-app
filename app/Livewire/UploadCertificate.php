<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Certificate;
use Livewire\Attributes\Rule;

class UploadCertificate extends Component
{
    use WithFileUploads;

    #[Rule('required|file|mimes:pdf,png,jpg,jpeg|max:2048')]
    public $certificate;

    public function upload()
    {
        $validated = $this->validate();

        if ($this->certificate){
            $filePath = $this->certificate->store('certificates', 'public');
            $validated['file_path'] = $filePath;
        }
        
        Certificate::create($validated);

        $this->reset('certificate');
        session()->flash('message', 'Certificate uploaded successfully.');
    }

    public function render()
    {
        return view('livewire.upload-certificate');
    }
}
