<?php


namespace App\Repositories\Eloquent;


use App\Models\User;
use App\Repositories\Contracts\IUser;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;

class UserRepository extends BaseRepository implements IUser
{
    /**
     * @return string
     */
    public function model(): string
    {
        return User::class;
    }

    /**
     * @param string $email
     * @return User
     */
    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function search(Request $request)
    {
        $query = (new $this->model)->newQuery();

        // only designers who have designs
        if ($request->has('has_designs')) {
            $query->has('designs');
        }

        // check for available_to_hire
        if ($request->has('available_to_hire')) {
            $query->where('available_to_hire', true);
        }

        // Geographic Search
        $lat = $request->latitude;
        $lng = $request->longitude;
        $dist = $request->distance;
        $unit = $request->unit;

        if ($lat && $lng) {
            $point = new Point($lat, $lng);
            (string)$unit === 'km' ? $dist *= 1000 : $dist *= 1609.343;
            $query->distanceSphereExcludingSelf('location', $point, $dist);
        }

        if ($request->has('orderByLatest')) {
            $query->latest();
        } else {
            $query->oldest();
        }

        return $query->get();
    }
}
