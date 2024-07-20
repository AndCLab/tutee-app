<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Models\User;

class ApiSelect extends Controller
{
    public function country_details(Request $request)
    {
        // Read and decode JSON file
        $json = file_get_contents(public_path('country_code_and_details.json'));
        $data = collect(json_decode($json, true));

        // Filter and transform data
        $filteredData = $data
            ->when(
                $request->search,
                fn (Collection $collection) => $collection->filter(
                    fn ($item) => str_contains(strtolower($item['country_name']), strtolower($request->search))
                )
            )
            ->when(
                $request->exists('selected'),
                fn (Collection $collection) => $collection->whereIn('country_code', $request->input('selected', [])),
                // fn (Collection $collection) => $collection->take(10)
            )
            ->sortBy('country_name')
            ->map(function ($item) {
                $item['phone_code'] = "+{$item['phone_code']}";
                $item['country_name'] = "{$item['country_name']}";
                $item['country_image'] = "https://flagcdn.com/" . strtolower($item['country_code']) . ".svg";
                return $item;
            });

        // Return as JSON response
        return response()->json($filteredData->values());
    }
}

// DON'T MIND THIS. FUTURE REFERENCE LANG :)
// <?php

// namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;
// use App\Models\User;
// use Illuminate\Contracts\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\Collection;
// use Illuminate\Http\Request;

// class ApiSelect extends Controller
// {
//     public function __invoke(Request $request): Collection
//     {
//         return User::query()
//             ->select('id', 'fname', 'email')
//             ->when(
//                 $request->search,
//                 fn (Builder $query) => $query
//                     ->where('fname', 'like', "%{$request->search}%")
//                     ->orWhere('email', 'like', "%{$request->search}%")
//             )
//             ->when(
//                 $request->exists('selected'),
//                 fn (Builder $query) => $query->whereIn('id', $request->input('selected', [])),
//                 fn (Builder $query) => $query->limit(10)
//             )
//             ->orderBy('fname')
//             ->get()
//             ->map(function (User $user) {
//                 $user->profile_image = "https://picsum.photos/300?id={$user->id}";
//                 return $user;
//             });
//     }
// }
