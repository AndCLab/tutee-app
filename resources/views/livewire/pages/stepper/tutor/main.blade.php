<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\Tutor;
use App\Models\Work;
use Livewire\WithFileUploads;
use App\Models\Certificate;
use App\Models\Resume;
use App\Models\Fields;

new #[Layout('layouts.app')] class extends Component {
    public $count = 2;

    // General
    public $user_type = 'tutor';
    public $dates = [''];
    public $inputs = [];
    public $i;

    // Tutor
    public $from = [];
    public $to = [];
    public $work = [];
    public $certificate = [];
    public $resume;
    public $specific = '';
    public $selected = [];

    public $confirmation = [
        'Platform Usage' => ['By accessing or using the TUTEE platform, you agree to comply with these terms and conditions and all applicable laws and regulations.', 'TUTEE reserves the right to modify or update these terms at any time without prior notice. It is your responsibility to review these terms periodically for any changes.'],
        'Account Registration' => ['To access certain features of the TUTEE platform, you may be required to create an account.', 'You agree to provide accurate, current, and complete information during the registration process and to update such information to keep it accurate, current, and complete.', 'You are solely responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.'],
        'Tutors and Tutees' => ['TUTEE provides a platform where individuals can connect for tutoring services. Tutors are independent contractors and are not employees or agents of TUTEE.', 'Tutees have access to a curated database of tutors and can select tutors based on their preferences and requirements.'],
        'Verification Process' => ['TUTEE offers a verification process for tutors to enhance their credibility. However, even tutors who are not verified can build trust through a rating system based on reviews from previous interactions with students.'],
        'Scheduling and Communication' => ['Tutees can schedule appointments with tutors directly through the TUTEE platform.', 'TUTEE facilitates seamless communication between tutors and tutees through integrated messaging features for ongoing support and clarification of doubts.'],
        'User Conduct' => ['You agree not to use the TUTEE platform for any unlawful or unauthorized purpose.', 'You agree not to interfere with or disrupt the integrity or performance of the TUTEE platform or its services.'],
        'Intellectual Property' => ['All content on the TUTEE platform, including but not limited to text, graphics, logos, images, and software, is the property of TUTEE or its licensors and is protected by intellectual property laws.'],
        'Limitation of Liability' => ['TUTEE shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or in connection with the use or inability to use the TUTEE platform.'],
        'Governing Law' => ['These terms and conditions shall be governed by and construed in accordance with the laws of the Republic of the Philippines, without regard to its conflict of law provisions.'],
        'Contact Information' => ['If you have any questions or concerns about these terms and conditions, please contact us at [tutee@email.com].'],
    ];

    public function mount()
    {
        $this->i = 0;
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
            $this->from[] = '';
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

    use WithFileUploads;
    public function upload_certificate($tutorId)
    {
        if ($this->certificate) {
            foreach ($this->certificates as $certificate) {
                $extension = $this->certificate->getClientOriginalExtension();
                $filename = uniqid() . '_' . time() . '.' . $extension;

                $filePath = $this->certificate->storeAs('certificates', $filename);

                Certificate::create([
                    'tutor_id' => $tutorId,
                    'file_path' => $filePath,
                ]);
            }
            $this->reset('certificate');
        }
    }

    public function upload_resume($tutorId)
    {
        if ($this->resume) {
            $extension = $this->resume->getClientOriginalExtension();
            $filename = uniqid() . '_' . time() . '.' . $extension;

            $filePath = $this->resume->storeAs('resumes', $filename);

            Resume::create([
                'tutor_id' => $tutorId,
                'file_path' => $filePath,
            ]);

            $this->reset('resume');
        }
    }

    public function next_step()
    {
        if ($this->count === 3) {
            if (count($this->selected) < 3) {
                session()->flash('error-field', 'List at least 3 fields');
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
                    'certificate.*' => 'required|file|mimes:png,jpg,jpeg|max:2048',
                    'resume' => 'required|file|mimes:pdf|max:2048',
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
                'work' => json_encode($this->work),
            ]);

            foreach ($this->inputs as $item) {
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

            $this->upload_certificate($tutor->id);
            $this->upload_resume($tutor->id);

            return redirect()->route('dashboard');
        }
    }
}; ?>

<div>
    @include('livewire.pages.stepper.body')
</div>
