@php
    $steps = [
        [
            'title' => 'Step 1',
            'description' => 'Specify your role',
            'icon' => 'user',
            'text' => 'Role',
            'svg' => '
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M12 2a5 5 0 1 1 -5 5l.005 -.217a5 5 0 0 1 4.995 -4.783z" />
                <path d="M14 14a5 5 0 0 1 5 5v1a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-1a5 5 0 0 1 5 -5h4z" />
            '
        ],
        [
            'title' => 'Step 2',
            'description' => 'Information about you',
            'icon' => 'list-details',
            'text' => 'Form',
            'svg' => '
                <path d="M13 5h8" />
                <path d="M13 9h5" />
                <path d="M13 15h8" />
                <path d="M13 19h5" />
                <path d="M3 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                <path d="M3 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
            '
        ],
        [
            'title' => 'Step 3',
            'description' => 'What are your interests?',
            'icon' => 'heart',
            'text' => 'Fields',
            'svg' => '
                <path
                    d="M6.979 3.074a6 6 0 0 1 4.988 1.425l.037 .033l.034 -.03a6 6 0 0 1 4.733 -1.44l.246 .036a6 6 0 0 1 3.364 10.008l-.18 .185l-.048 .041l-7.45 7.379a1 1 0 0 1 -1.313 .082l-.094 -.082l-7.493 -7.422a6 6 0 0 1 3.176 -10.215z" />
            '
        ],
        [
            'title' => 'Step 4',
            'description' => 'Get your Tutee account',
            'icon' => 'bounce-right',
            'text' => 'Confirmation',
            'svg' => '
                <path
                    d="M14.143 11.486a1 1 0 0 1 1.714 1.028c-1.502 2.505 -2.41 4.89 -2.87 7.65c-.16 .956 -1.448 1.15 -1.881 .283c-2.06 -4.12 -3.858 -4.976 -6.79 -3.998a1 1 0 1 1 -.632 -1.898c3.2 -1.067 5.656 -.373 7.803 2.623l.091 .13l.011 -.04c.522 -1.828 1.267 -3.55 2.273 -5.3l.28 -.478z" />
                <path d="M18 4a3 3 0 1 0 0 6a3 3 0 0 0 0 -6z" />
            '
        ],
    ];
@endphp

<div>
    <div class="flex md:flex-col gap-8 justify-center">
        @foreach($steps as $index => $step)
            @php
                $stepNumber = $index + 1;
                $isActive = $count >= $stepNumber;
                $isExactActive = $count === $stepNumber;
            @endphp
            <div class="flex flex-col gap-4">
                <p @class([
                    'font-semibold text-xl text-nowrap',
                    'text-[#1589C3]' => $isActive,
                    'text-[#292D32]' => !$isActive,
                ])>{{ $step['title'] }}</p>
                <div class="flex h-11 items-center gap-2">
                    <div class="h-full w-1 rounded-md @if ($isExactActive) bg-[#1589C3] @endif"></div>
                    <svg @class([
                        'text-[#1589C3]' => $isActive,
                        'text-[#292D32]' => !$isActive,
                    ]) xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                        viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icons-tabler-{{ $step['icon'] }}">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        {!! $step['svg'] !!}
                    </svg>
                    <div class="md:block hidden">
                        <p @class([
                            'text-xl font-semibold',
                            'text-[#1589C3]' => $isActive,
                            'text-[#292D32]' => !$isActive,
                        ])>
                            {{ $step['text'] }}
                        </p>
                        <p class="text-[#64748B]">{{ $step['description'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
