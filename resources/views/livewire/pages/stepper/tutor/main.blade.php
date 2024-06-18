<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\Tutor;
use App\Models\Work;
use Livewire\WithFileUploads;
use App\Models\Certificate;

new #[Layout('layouts.app')] class extends Component {
    public $count = 2;

    // General
    public $user_type = 'tutor';
    public $dates = [''];
    public $inputs = [];

    // Tutor
    public $from = [];
    public $to = [];
    public $work = [];
    public $certificate;

    public function mount()
    {
        $this->dates = range(1990, 2024);
        $this->inputs = [0];
        $this->from = [''];
        $this->to = [''];
        $this->work = [''];
    }

    public function add_work()
    {
        if (count($this->inputs) < 3) {
            $this->inputs[] = count($this->inputs);
            $this->from[] = ''; // Ensure from, to, and work arrays are synchronized
            $this->to[] = '';
            $this->work[] = '';
        } else {
            session()->flash('error-work', 'You cannot add more than 3');
        }
    }

    public function remove_work($index)
    {
        unset($this->inputs[$index]);
        unset($this->from[$index]);
        unset($this->to[$index]);
        unset($this->work[$index]);

        $this->inputs = array_values($this->inputs);
        $this->from = array_values($this->from);
        $this->to = array_values($this->to);
        $this->work = array_values($this->work);
    }

    use WithFileUploads;
    public function upload_certificate()
    {
        $validated = $this->validate();

        if ($this->certificate){
            $filePath = $this->certificate->store('certificates', 'public');
            $validated['file_path'] = $filePath;
        }

        Certificate::create([
            'file_path' => $filePath,
        ]);

        session()->flash('message', 'Certificate uploaded successfully!');

        $this->reset('certificate');
    }

    public function next_step()
    {
        $this->validate_status();
        $this->count++;
    }

    public function prev_step()
    {
        if ($this->count === 2) {
            return redirect()->route('stepper');
        } else {
            $this->count--;
        }
    }

    public function validate_status()
    {
        if ($this->count === 2) {
            $this->validate(
                [
                    'from.*' => 'required|date',
                    'to.*' => 'required|date|after:from.*',
                    'work.*' => 'required|max:200',
                    'certificate.*' => 'required|file|mimes:pdf,png,jpg,jpeg|max:2048',
                ],
                [
                    'to.*.after' => 'The "to" date must be after the "from" date.',
                ],
            );
        }
    }

    public function submit()
    {
        $user = User::find(Auth::id());
        $user->user_type = $this->user_type;
        $user->is_stepper = 0;
        $user->save();

        if ($user && $this->user_type === 'tutor') {
            $tutor = Tutor::create([
                'user_id' => $user->id,
                'work' =>json_encode($this->work),
            ]);

            foreach ($this->inputs as $item) {
                Work::create([
                    'tutor_id' => $tutor->id,
                    'from' => $this->from[$item],
                    'to' => $this->to[$item],
                    'work' => $this->work[$item],
                ]);
            }

            return redirect()->route('dashboard');
        }
    }
}; ?>

<div>
    @include('livewire.pages.stepper.body')
</div>
