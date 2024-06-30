<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use App\Models\User;
use App\Models\Tutee;
use App\Models\Institute;
use App\Models\Fields;

new #[Layout('layouts.app')] class extends Component {
    public $count = 2;

    // General
    public $user_type = 'tutee';
    public $dates = [''];
    public $inputs = [];
    public $i;
    public $specific = '';

    public $fields = [
        'English' => ['Grammar', 'Literature', 'Poetry', 'Writing'],
        'Mathematics' => ['Algebra', 'Geometry', 'Calculus', 'Statistics', 'Trigonometry'],
        'Science' => ['Biology', 'Chemistry', 'Physics', 'Astronomy', 'Geology'],
        'History' => ['Ancient', 'Medieval', 'Modern', 'World Wars', 'American'],
        'Geography' => ['Maps', 'Climate', 'Continents', 'Oceans', 'Countries'],
        'Computer Science' => ['Programming', 'Algorithms', 'Data Structures', 'Databases', 'Networks'],
        'Art' => ['Painting', 'Sculpture', 'Drawing', 'Photography', 'Film'],
        'Music' => ['Theory', 'Composition', 'Performance', 'Genres', 'History'],
        'Physical Education' => ['Sports', 'Exercise', 'Health', 'Fitness', 'Nutrition'],
    ];

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

    // Tutee
    public $grade_level = '';
    public $from = [];
    public $to = [];
    public $institute = [];
    public $selected = [];

    public function mount()
    {
        $this->i = 0;
        $this->inputs = [0];
        $this->from = [''];
        $this->to = [''];
        $this->institute = [''];
    }

    public function add_institute()
    {
        if (count($this->inputs) < 3) {
            $this->inputs[] = count($this->inputs);
            $this->from[] = ''; // Ensure from, to, and institute arrays are synchronized
            $this->to[] = '';
            $this->institute[] = '';
        } else {
            session()->flash('error-institute', 'You cannot add more than 3');
        }
    }

    public function remove_institute($index)
    {
        unset($this->inputs[$index]);
        unset($this->from[$index]);
        unset($this->to[$index]);
        unset($this->institute[$index]);

        $this->inputs = array_values($this->inputs);
        $this->from = array_values($this->from);
        $this->to = array_values($this->to);
        $this->institute = array_values($this->institute);
    }

    // Fields

    public function get_field($name)
    {
        if (!in_array($name, $this->selected)) {
            $this->selected[$this->i++] = $name;
        }
    }

    public function get_specific_field()
    {
        $this->validate([
            'specific' => 'required'
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

    public function next_step()
    {
        if ($this->count === 3) {
            if (count($this->selected) < 3) {
                session()->flash('error-field', 'Choose at least 3 fields');
            } else {
                $this->count++;
            }
        } else{
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
                    'institute.*' => 'required|max:200',
                    'grade_level' => 'required',
                ],
                [
                    'to.*.after' => 'The "to" date must be after the "from" date.',
                ],
            );
        }
    }

    public function submit()
    {
        // Update stepper to 0
        $user = User::find(Auth::id());
        $user->user_type = $this->user_type;
        $user->is_stepper = 0;
        $user->save();

        if ($user && $this->user_type === 'tutee') {
            $tutee = Tutee::create([
                'user_id' => $user->id,
                'grade_level' => $this->grade_level,
            ]);

            foreach ($this->inputs as $item) {
                Institute::create([
                    'tutee_id' => $tutee->id,
                    'from' => $this->from[$item],
                    'to' => $this->to[$item],
                    'institute' => $this->institute[$item],
                ]);
            }

            foreach ($this->selected as $item) {
                Fields::create([
                    'user_id' => $user->id,
                    'field_name' => $item,
                ]);
            }

            return redirect()->route('dashboard');
        }
    }
}; ?>

<div>
    @include('livewire.pages.stepper.body')
</div>
