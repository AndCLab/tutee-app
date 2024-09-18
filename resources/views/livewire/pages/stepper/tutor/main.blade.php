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

    public $title = 'Stepper | Tutor Role';

    public $count = 2;

    // General
    public $user_type = 'tutor';
    public $dates = [''];
    public $input_work = [];
    public $input_degree = [];
    public $input_certi = [];
    public $i;
    public $suggestions = [];

    // Tutor
    public $from = [];
    public $to = [];
    public $work = [];
    public $company = [];

    public $degree = [];

    public $certificates = [];
    public $title_certi = [];
    public $from_certi = [];

    public $selected = [];
    public $resume;
    public $specific = '';

    // Mount everything
    public function mount()
    {
        $this->i = 0;
        $this->input_work = [0];
        $this->input_degree = [0];
        $this->input_certi = [0];

        $this->degree = [''];
        $this->from = [''];
        $this->to = [''];
        $this->work = [''];
        $this->company = [''];

        $this->certificates = [''];
        $this->title_certi = [''];
        $this->from_certi = [''];
    }

    // Work experience
    public function add_work()
    {
        $this->input_work[] = count($this->input_work);
        $this->from[] = '';
        $this->to[] = '';
        $this->work[] = '';
        $this->company[] = '';
    }

    public function remove_work($index)
    {
        unset($this->input_work[$index]);
        unset($this->from[$index]);
        unset($this->to[$index]);
        unset($this->work[$index]);
        unset($this->company[$index]);

        $this->input_work = array_values($this->input_work);
        $this->from = array_values($this->from);
        $this->to = array_values($this->to);
        $this->work = array_values($this->work);
        $this->company = array_values($this->company);
    }

    // Degree
    public function add_degree()
    {
        $this->input_degree[] = count($this->input_degree);
        $this->degree[] = '';
    }

    public function remove_degree($index)
    {
        unset($this->input_degree[$index]);
        unset($this->degree[$index]);

        $this->input_degree = array_values($this->input_degree);
        $this->degree = array_values($this->degree);
    }

    // Certificates
    public function add_cert()
    {
        $this->input_certi[] = count($this->input_certi);
        $this->certificates[] = '';
        $this->title_certi[] = '';
        $this->from_certi[] = '';
    }

    public function remove_cert($index)
    {
        unset($this->input_certi[$index]);
        unset($this->certificates[$index]);
        unset($this->title_certi[$index]);
        unset($this->from_certi[$index]);

        $this->input_certi = array_values($this->input_certi);
        $this->certificates = array_values($this->certificates);
        $this->title_certi = array_values($this->title_certi);
        $this->from_certi = array_values($this->from_certi);
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

    // certificates
    public function upload_certificates($tutorId)
    {
        if ($this->certificates) {
            foreach ($this->certificates as $index => $certificate) {
                $extension = $certificate->getClientOriginalExtension();
                $filename = uniqid() . '_' . time() . '.' . $extension;

                $filePath = $certificate->storeAs('certificates', $filename, 'public');

                Certificate::create([
                    'tutor_id' => $tutorId,
                    'file_path' => $filePath,
                    'title' => $this->title_certi[$index],
                    'from' => $this->from_certi[$index],
                ]);
            }
        }
    }

    // resume
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
                    'company.*' => 'nullable|max:255',

                    'certificates.*' => 'required|file|mimes:pdf,png,jpg,jpeg|max:2048',
                    'resume' => 'required|file|mimes:pdf|max:2048',

                    'title_certi.*' => 'required|string|max:255',
                    'from_certi.*' => 'required|date',

                    'degree.*' => 'required|string|max:255',
                ],
                [
                    'from.*.required' => 'The from is required',
                    'to.*.required' => 'The to is required',
                    'work.*.required' => 'The work is required',
                    'company.*.required' => 'The company is required',

                    'to.*.after' => 'The "to" date must be after the "from" date.',
                    'resume.required' => 'The resume is required',

                    'title_certi.*.required' => 'The title is required',
                    'from_certi.*.required' => 'The certificate year is required',

                    'degree.*.required' => 'The degree is required',
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
            $degree_json = is_array($this->degree) ? json_encode($this->degree) : $this->degree;
            $work_json = is_array($this->work) ? json_encode($this->work) : $this->work;

            $tutor = Tutor::create([
                'user_id' => $user->id,
                'work' => $work_json,
                'degree' => $degree_json
            ]);

            foreach ($this->input_work as $item) {
                Work::create([
                    'tutor_id' => $tutor->id,
                    'from' => $this->from[$item],
                    'to' => $this->to[$item],
                    'work' => $this->work[$item],
                    'company' => $this->company[$item],
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

@push('title')
    {{ $title }}
@endpush

<div>
    @include('livewire.pages.stepper.body')
</div>
