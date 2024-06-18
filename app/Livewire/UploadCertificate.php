<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Certificate;

class UploadCertificate extends Component
{
    use WithFileUploads;

    public $certificate;

    public function store()
    {
        $this->validate([
            'certificate' => 'required|file|mimes:pdf,png,jpg,jpeg|max:2048',
        ]);

        $filePath = $this->certificate->store('certificates', 'public');

        Certificate::create([
            'file_path' => $filePath,
        ]);

        session()->flash('message', 'Certificate uploaded successfully!');

        $this->reset('certificate');
    }

    public function render()
    {
        return view('livewire.upload-certificate');
    }

}
