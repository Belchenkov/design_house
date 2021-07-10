<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Jobs\UploadImage;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $this->validate($request, [
            'image' => ['required', 'mimes:jpeg,gif,bmp,png', 'max:2048']
        ]);

        //  Upload image
        $image = $request->file('image');
        $image_path = $image->getPathName();
        $filename = time() . '_' . preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));
        $tmp = $image->storeAs('uploads/original', $filename, 'tmp');

        // Save record
        $design = auth()->user()
            ->designs()
            ->create([
                'image' => $filename,
                'disk' => config('site.upload_disk')
            ]);

        // Job for image manipulation
        $this->dispatch(new UploadImage($design));

        return response()->json($design, 200);
    }
}
