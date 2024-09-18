<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public $title = 'Login | Tutee';

    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        if (Auth::check()) {
            $user = Auth::user();

            if ($user->is_stepper == 1) {
                $route = 'stepper';
            } else {
                $route = $user->user_type == 'tutee' ? 'tutee.discover' : 'tutor.discover';
            }

            $this->redirectIntended(route($route), navigate: true);
        }


    }
}; ?>

<div class="max-w-sm mx-auto">
    @push('title')
        {{ $title }}
    @endpush

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <div class="flex flex-col gap-4">
            <!-- Email Address -->
            <div>
                <x-wui-input label="Email" placeholder="Enter your email"
                    wire:model="form.email" autocomplete='username' shadowless/>
            </div>

            <!-- Password -->
            <div>
                <x-wui-inputs.password placeholder='Enter your password' wire:model="form.password" label="Password"
                    autocomplete="current-password" shadowless/>
            </div>

            {{-- <x-wui-button type='submit' spinner='login' class="ring-[#0C3B2E] text-white bg-[#0C3B2E] hover:bg-[#0C3B2E] hover:ring-[#0C3B2E]" label='Login' /> --}}

            <x-primary-button wireTarget='login' onclick="openRegularLogin()">
                Login
            </x-primary-button>

            <!-- Remember Me -->
            <div class="block">
                <label for="remember" class="inline-flex items-center">
                    <input wire:model="form.remember" id="remember" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>
        </div>

        <hr class="my-2">

        <div class="flex flex-col sm:flex-row gap-4 sm:items-center justify-between">
            @if (Route::has('password.request'))
                <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}" wire:navigate onclick="openRegularLogin()">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
            @if (Route::has('register'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('register') }}" wire:navigate onclick="openRegularLogin()">
                    {{ __('Dont\' have an account?') }}
                </a>
            @endif
        </div>

        <div class="flex flex-col items-center gap-2">
            <!-- "OR CONTINUE WITH" Text -->
            <p class="text-sm font-bold text-gray-600 mb-4 mt-4">OR CONTINUE WITH</p>

            <!-- Google Login Button -->
            <a id="google-login-btn" href="javascript:void(0);" class="flex items-center justify-center w-full py-2 px-4 border border-gray-300 text-gray-700 text-base font-bold rounded hover:border-gray-400" onclick="openGoogleLoginPopup()">
                {{-- a id="google-login-btn" href="{{ route('google.login') }}" class="btn btn-google" --}}
                {{-- a id="google-login-btn" href="javascript:void(0);" class="btn btn-google" onclick="openGoogleLoginModal()" --}}
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 100 100">
                    <path fill="#78a0cf" d="M13 27A2 2 0 1 0 13 31A2 2 0 1 0 13 27Z"></path><path fill="#f1bc19" d="M77 12A1 1 0 1 0 77 14A1 1 0 1 0 77 12Z"></path><path fill="#cee1f4" d="M50 13A37 37 0 1 0 50 87A37 37 0 1 0 50 13Z"></path><path fill="#f1bc19" d="M83 11A4 4 0 1 0 83 19A4 4 0 1 0 83 11Z"></path><path fill="#78a0cf" d="M87 22A2 2 0 1 0 87 26A2 2 0 1 0 87 22Z"></path><path fill="#fbcd59" d="M81 74A2 2 0 1 0 81 78 2 2 0 1 0 81 74zM15 59A4 4 0 1 0 15 67 4 4 0 1 0 15 59z"></path><path fill="#78a0cf" d="M25 85A2 2 0 1 0 25 89A2 2 0 1 0 25 85Z"></path><path fill="#fff" d="M18.5 51A2.5 2.5 0 1 0 18.5 56A2.5 2.5 0 1 0 18.5 51Z"></path><path fill="#f1bc19" d="M21 66A1 1 0 1 0 21 68A1 1 0 1 0 21 66Z"></path><path fill="#fff" d="M80 33A1 1 0 1 0 80 35A1 1 0 1 0 80 33Z"></path><g><path fill="#ea5167" d="M35.233,47.447C36.447,40.381,42.588,35,50,35c3.367,0,6.464,1.123,8.968,2.996l6.393-6.885 C61.178,27.684,55.83,25.625,50,25.625c-11.942,0-21.861,8.635-23.871,20.001L35.233,47.447z"></path><path fill="#00a698" d="M58.905,62.068C56.414,63.909,53.335,65,50,65c-7.842,0-14.268-6.02-14.934-13.689l-8.909,2.97 C28.23,65.569,38.113,74.125,50,74.125c6.261,0,11.968-2.374,16.27-6.27L58.905,62.068z"></path><path fill="#48bed8" d="M68.5,45.5h-4.189H50.5v9h13.811c-1.073,3.414-3.333,6.301-6.296,8.179l7.245,6.038 c5.483-4.446,8.99-11.233,8.99-18.842c0-1.495-0.142-2.955-0.401-4.375H68.5z"></path><path fill="#fde751" d="M35,50c0-2.183,0.477-4.252,1.316-6.123l-7.818-5.212c-1.752,3.353-2.748,7.164-2.748,11.21 c0,3.784,0.868,7.365,2.413,10.556L36,55C35.634,53.702,35,51.415,35,50z"></path></g><g><path fill="#472b29" d="M50,74.825c-13.757,0-24.95-11.192-24.95-24.95S36.243,24.925,50,24.925 c5.75,0,11.362,2.005,15.804,5.646l0.576,0.472l-7.327,7.892l-0.504-0.377C56.051,36.688,53.095,35.7,50,35.7 c-7.885,0-14.3,6.415-14.3,14.3S42.115,64.3,50,64.3c5.956,0,11.195-3.618,13.324-9.1L50,55.208l-0.008-10.184l24.433-0.008 l0.104,0.574c0.274,1.503,0.421,2.801,0.421,4.285C74.95,63.633,63.758,74.825,50,74.825z M50,26.325 c-12.985,0-23.55,10.564-23.55,23.55S37.015,73.425,50,73.425s23.55-10.564,23.55-23.55c0-1.211-0.105-2.228-0.3-3.458H51.192 L51.2,53.8h14.065l-0.286,0.91C62.914,61.283,56.894,65.7,50,65.7c-8.657,0-15.7-7.043-15.7-15.7S41.343,34.3,50,34.3 c3.19,0,6.245,0.955,8.875,2.768l5.458-5.878C60.238,28.048,55.178,26.325,50,26.325z"></path></g>
                </svg>
                {{ __('Login with Google') }}
            </a>

        <!-- Modal showing instructions for Google Login -->
        {{-- <div id="googleLoginModal" class="modal hidden fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Google Login</h2>
                    <button class="text-gray-600" onclick="closeGoogleLoginModal()">×</button>
                </div>
                <p>Please complete the Google login in the new window.</p>
            </div>
        </div> --}}

            <!-- Facebook Login Button -->
            <a id="facebook-login-btn" href="" class="flex items-center justify-center w-full py-2 px-4 border border-gray-300 text-gray-700 text-base font-bold rounded hover:border-gray-400" onclick="openFacebookLoginPopup()">

                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="26" height="26" viewBox="0 0 100 100">
                    <path fill="#ee3e54" d="M13 27A2 2 0 1 0 13 31A2 2 0 1 0 13 27Z"></path><path fill="#f1bc19" d="M77 12A1 1 0 1 0 77 14A1 1 0 1 0 77 12Z"></path><path fill="#fce0a2" d="M50 13A37 37 0 1 0 50 87A37 37 0 1 0 50 13Z"></path><path fill="#f1bc19" d="M83 11A4 4 0 1 0 83 19A4 4 0 1 0 83 11Z"></path><path fill="#ee3e54" d="M87 22A2 2 0 1 0 87 26A2 2 0 1 0 87 22Z"></path><path fill="#fbcd59" d="M81 74A2 2 0 1 0 81 78 2 2 0 1 0 81 74zM15 59A4 4 0 1 0 15 67 4 4 0 1 0 15 59z"></path><path fill="#ee3e54" d="M25 85A2 2 0 1 0 25 89A2 2 0 1 0 25 85Z"></path><path fill="#fff" d="M18.5 51A2.5 2.5 0 1 0 18.5 56A2.5 2.5 0 1 0 18.5 51Z"></path><path fill="#f1bc19" d="M21 66A1 1 0 1 0 21 68A1 1 0 1 0 21 66Z"></path><path fill="#fff" d="M80 33A1 1 0 1 0 80 35A1 1 0 1 0 80 33Z"></path><g><path fill="#78a2d2" d="M50 25.625A24.25 24.25 0 1 0 50 74.125A24.25 24.25 0 1 0 50 25.625Z"></path></g><g><path fill="#472b29" d="M68.164,59.445c-0.073,0-0.148-0.017-0.219-0.051c-0.248-0.121-0.351-0.42-0.23-0.668 c0.132-0.271,0.256-0.543,0.375-0.818c0.46-1.068,0.826-2.186,1.087-3.318c0.062-0.27,0.333-0.437,0.6-0.375 c0.269,0.063,0.437,0.331,0.375,0.6c-0.275,1.191-0.66,2.366-1.144,3.49c-0.125,0.289-0.256,0.575-0.395,0.859 C68.527,59.342,68.349,59.445,68.164,59.445z"></path></g><g><path fill="#472b29" d="M70.264,52.336c-0.015,0-0.03-0.001-0.045-0.002c-0.275-0.024-0.478-0.268-0.453-0.543 c0.039-0.429,0.063-0.857,0.074-1.286c0.067-2.666-0.39-5.273-1.358-7.752c-0.101-0.257,0.027-0.547,0.284-0.647 c0.259-0.104,0.547,0.025,0.648,0.284c1.017,2.602,1.497,5.341,1.426,8.14c-0.011,0.451-0.037,0.901-0.078,1.352 C70.738,52.141,70.52,52.336,70.264,52.336z"></path></g><g><path fill="#472b29" d="M35.107,36.532c-0.123,0-0.245-0.045-0.341-0.135c-0.202-0.188-0.212-0.505-0.024-0.706 c3.399-3.642,7.999-5.94,12.95-6.475c0.277-0.023,0.521,0.17,0.551,0.443c0.03,0.274-0.169,0.521-0.443,0.551 c-4.713,0.509-9.091,2.697-12.327,6.162C35.375,36.479,35.241,36.532,35.107,36.532z"></path></g><g><path fill="#472b29" d="M36.138,65.284c-0.123,0-0.245-0.045-0.341-0.135c-7.104-6.632-8.721-17.138-3.934-25.548 c0.137-0.242,0.442-0.325,0.682-0.188c0.24,0.137,0.324,0.442,0.187,0.682c-4.557,8.006-3.016,18.008,3.748,24.323 c0.202,0.188,0.212,0.505,0.024,0.706C36.405,65.23,36.271,65.284,36.138,65.284z"></path></g><g><path fill="#472b29" d="M58.889,68.769c-0.186,0-0.365-0.104-0.451-0.283c-0.12-0.248-0.016-0.547,0.233-0.667 c2.202-1.062,4.172-2.515,5.856-4.316c0.679-0.729,1.307-1.511,1.866-2.325c0.156-0.227,0.469-0.285,0.695-0.129 c0.228,0.156,0.286,0.467,0.129,0.695c-0.587,0.855-1.246,1.677-1.959,2.44c-1.769,1.894-3.838,3.42-6.152,4.535 C59.036,68.753,58.962,68.769,58.889,68.769z"></path></g><g><path fill="#fff" d="M46.458,73.5v-17h-6.021v-5.978h6.021l0-6.216c-0.137-5.577,4.159-11.002,14.104-7.994l-0.021,5.271 l-3.508-0.022c-2.699,0-3.628,0.863-3.628,2.745v6.216h7.157L59.304,56.5h-5.899v17"></path><path fill="#472b29" d="M53.905,73.5h-1V56h5.993l1.048-4.978h-7.041v-6.716c0-2.244,1.273-3.245,4.128-3.245l3.01,0.019 l0.018-4.394c-4.274-1.22-7.779-0.913-10.154,0.896c-1.942,1.479-3.018,3.926-2.949,6.712v6.729h-6.021V56h6.021v17.5h-1V57h-6.021 v-6.978h6.021v-5.716c-0.076-3.099,1.142-5.845,3.343-7.521c1.888-1.438,5.398-2.768,11.406-0.952l0.357,0.107l-0.024,6.145 l-4.009-0.024c-2.614,0-3.125,0.823-3.125,2.245v5.716h7.273L59.71,57h-5.805V73.5z"></path></g><g><path fill="#472b29" d="M50,74.825c-13.757,0-24.95-11.192-24.95-24.95S36.243,24.925,50,24.925s24.95,11.192,24.95,24.95 S63.757,74.825,50,74.825z M50,26.325c-12.985,0-23.55,10.564-23.55,23.55S37.015,73.425,50,73.425s23.55-10.564,23.55-23.55 S62.985,26.325,50,26.325z"></path></g>
                </svg>
                {{ __('Login with Facebook') }}
            </a>
        </div>
        
    </form>



<!-- Styles for modal -->
<style>
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    .modal.show {
        display: flex;
    }

    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        text-align: center;
    }
</style>

<!-- JavaScript for opening Google and Facebook login in a new popup -->
{{-- <script>
    // Center the popup window
    function openCenteredPopup(url, width, height) {
        const left = (screen.width / 2) - (width / 2);
        const top = (screen.height / 2) - (height / 2);
        return window.open(url, '', `width=${width},height=${height},top=${top},left=${left}`);
    }

    //GOOGLE

    function openGoogleLoginModal() {
        var modal = document.getElementById("googleLoginModal");

        // Open the modal
        modal.classList.add("show");

        // Open the Google login popup in the center
        const googleWindow = openCenteredPopup("{{ route('google.login') }}", 500, 600);

        // Set up an interval to detect if the Google window was closed
        const checkGoogleWindow = setInterval(() => {
            if (googleWindow.closed) {
                clearInterval(checkGoogleWindow);
                closeGoogleLoginModal(); // Close the modal if the Google login window is closed
            }
        }, 500);
    }

    function closeGoogleLoginModal() {
        var modal = document.getElementById("googleLoginModal");
        modal.classList.remove("show");
    }
   
</script> --}}
    
<script>
    let googleWindow = null;  // Store the reference to the Google popup window
    let facebookWindow = null;  // Store reference for Facebook popup window

    // Function to close the Facebook popup window if it's open
    function closeFacebookLoginPopup() {
        if (facebookWindow && !facebookWindow.closed) {
            try {
                facebookWindow.close();  // Try to close the popup window
                facebookWindow = null;   // Reset the reference
                console.log('Facebook popup closed successfully.');
            } catch (error) {
                console.log('Error closing Facebook popup:', error);
            }
        }
    }

    // Function to close the Google popup window if it's open
    function closeGoogleLoginPopup() {
        if (googleWindow && !googleWindow.closed) {
            try {
                googleWindow.close();  // Try to close the popup window
                googleWindow = null;   // Reset the reference
                console.log('Google popup closed successfully.');
            } catch (error) {
                console.log('Error closing Google popup:', error);
            }
        }
    }
    
    // GOOGLE
    // Function to open the Google login popup
    function openGoogleLoginPopup() {
        // Close Facebook login popup if it's open
        closeFacebookLoginPopup();  

        const googleLoginUrl = "{{ route('google.login') }}";

        // Check if the popup window is already open
        if (googleWindow && !googleWindow.closed) {
            // If the window is already open, focus on it
            googleWindow.focus();
            console.log('Google popup focused.');
        } else {
            // Otherwise, open a new popup window for Google login
            googleWindow = window.open(googleLoginUrl, "googleLoginWindow", "width=500,height=600");
            console.log('Google popup opened.');
        }

        // Set up an interval to detect when the Google window is closed
        const checkGoogleWindow = setInterval(() => {
            if (googleWindow && googleWindow.closed) {
                clearInterval(checkGoogleWindow);  // Stop checking if the window is closed
                googleWindow = null;               // Reset the reference when closed
                console.log('Google popup closed.');
            }
        }, 500);
    }

    // FACEBOOK
    function openFacebookLoginPopup() {
    // Close Google login popup if it's open
    closeGoogleLoginPopup(); 

    const facebookLoginUrl = "{{ route('facebook.login') }}";

    // Check if the Facebook popup window is already open
    if (facebookWindow && !facebookWindow.closed) {
        // If the window is already open, focus on it
        facebookWindow.focus();
        console.log('Facebook popup focused.');
    } else {
        // Otherwise, open a new popup window for Facebook login
        facebookWindow = window.open(facebookLoginUrl, "facebookLoginWindow", "width=500,height=600");

        // Detect when the user navigates away from your domain
        facebookWindow.onbeforeunload = function() {
            console.log('Facebook window is navigating or closing');
            // Optionally, close the window here if needed
            closeFacebookLoginPopup();  
        };

        console.log('Facebook popup opened.');
    }

    // Set up an interval to detect when the Facebook window is closed
    const checkFacebookWindow = setInterval(() => {
        if (facebookWindow && facebookWindow.closed) {
            clearInterval(checkFacebookWindow);  // Stop checking if the window is closed
            facebookWindow = null;               // Reset the reference when closed
            console.log('Facebook popup closed.');
        }
    }, 500);
}


    // Function to trigger regular login and close Google & Facebook popups
    function openRegularLogin() {
        closeGoogleLoginPopup();   // Close Google popup if it's open
        closeFacebookLoginPopup(); // Close Facebook popup if it's open
        console.log('Regular login triggered.');
        // Handle the regular login process (this could be a form submission or some other logic)
    }
</script>




</div>

