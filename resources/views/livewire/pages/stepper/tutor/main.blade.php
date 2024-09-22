<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Livewire\Actions\Logout;
use App\Models\User;
use App\Models\Tutor;
use App\Models\Work;
use Livewire\WithFileUploads;
use App\Models\Certificate;
use App\Models\Resume;
use App\Models\Fields;
use WireUi\Traits\Actions;

new #[Layout('layouts.app')] class extends Component {
    use Actions, WithFileUploads;

    public $count = 2;

    // General
    public $user_type = 'tutor';
    public $dates = [''];
    public $input_work = [];
    public $i;

    // Tutor
    public $from = [];
    public $to = [];
    public $work = [];
    public $certificates = [];
    public $selected = [];
    public $resume;
    public $specific = '';

    // Mount everything
    public function mount()
    {
        $this->i = 0;
        $this->input_work = [0];
        $this->from = [''];
        $this->to = [''];
        $this->work = [''];
        $this->certificates = [''];
    }

    // Work experience
    public function add_work()
    {
        $this->input_work[] = count($this->input_work);
        $this->from[] = '';
        $this->to[] = '';
        $this->work[] = '';
    }

    public function remove_work($index)
    {
        unset($this->input_work[$index]);
        unset($this->from[$index]);
        unset($this->to[$index]);
        unset($this->work[$index]);

        $this->input_work = array_values($this->input_work);
        $this->from = array_values($this->from);
        $this->to = array_values($this->to);
        $this->work = array_values($this->work);
    }

    // Certificate
    public function add_cert()
    {
        $this->certificates[] = '';
    }

    public function remove_cert($index)
    {
        unset($this->certificates[$index]);
        $this->certificates = array_values($this->certificates);
    }

    // Fields
    public function get_specific_field()
    {
        $this->validate([
            'specific' => 'required',
        ]);

        if (!in_array($this->specific, $this->selected)) {
            $this->selected[$this->i++] = $this->specific;
        }

        $this->reset('specific');
    }

    public function remove_field($index)
    {
        unset($this->selected[$index]);
        $this->selected = array_values($this->selected);
    }

    public function upload_certificates($tutorId)
    {
        if ($this->certificates) {
            foreach ($this->certificates as $certificate) {
                $extension = $certificate->getClientOriginalExtension();
                $filename = uniqid() . '_' . time() . '.' . $extension;

                $filePath = $certificate->storeAs('certificates', $filename, 'public');

                Certificate::create([
                    'tutor_id' => $tutorId,
                    'file_path' => $filePath,
                ]);
            }
        }
    }

    public function upload_resume($tutorId)
    {
        if ($this->resume) {
            $extension = $this->resume->getClientOriginalExtension();
            $filename = uniqid() . '_' . time() . '.' . $extension;

            $filePath = $this->resume->storeAs('resume', $filename, 'public');

            Resume::create([
                'tutor_id' => $tutorId,
                'file_path' => $filePath,
            ]);
        }
    }

    public function next_step()
    {
        if ($this->count === 3) {
            if (count($this->selected) < 3) {
                $this->notification([
                    'title'       => 'Error',
                    'description' => 'Must have at least 3 fields',
                    'icon'        => 'error',
                    'timeout'     => 2500,
                ]);
            } else {
                $this->count++;
            }
        } else {
            $this->validate_status();
            $this->count++;
        }
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
                    'certificates.*' => 'required|file|mimes:pdf,png,jpg,jpeg|max:2048',
                    'resume' => 'required|file|mimes:pdf|max:2048',
                ],
                [
                    'from.*.required' => 'The from is required',
                    'to.*.required' => 'The to is required',
                    'work.*.required' => 'The work is required',
                    'certificates.*.required' => 'The certificate is required',
                    'to.*.after' => 'The "to" date must be after the "from" date.',
                    'resume.required' => 'The resume is required',
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

        if ($user->apply_status == 'pending'){
            $user->apply_status = 'applied';
            $user->apply_status->save();
        }

        if ($user && $this->user_type === 'tutor') {
            $tutor = Tutor::create([
                'user_id' => $user->id,
                'work' => json_encode($this->work),
            ]);

            foreach ($this->input_work as $item) {
                Work::create([
                    'tutor_id' => $tutor->id,
                    'from' => $this->from[$item],
                    'to' => $this->to[$item],
                    'work' => $this->work[$item],
                ]);
            }

            foreach ($this->selected as $item) {
                Fields::create([
                    'user_id' => $user->id,
                    'field_name' => $item,
                ]);
            }

            $this->upload_certificates($tutor->id);
            $this->upload_resume($tutor->id);

            return redirect()->route('tutor.discover');
        }
    }

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/login', navigate: true);
    }
}; ?>

<div>
    @include('livewire.pages.stepper.body')
</div>
