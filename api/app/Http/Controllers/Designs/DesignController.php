<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DesignController extends Controller
{
    public function update(Request $request, $id)
    {
        $design = Design::findOrFail($id);

        $this->authorize('update', $design);

        $this->validate($request, [
            'title' => ['required', 'unique:designs,title,' . $id],
            'description' => ['required', 'string', 'min:20', 'max:140']
        ]);

        $design->update([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'is_live' => ! $design->upload_successful ? false : $request->is_live
        ]);

        return new DesignResource($design);
    }

    public function destroy($id)
    {
        $design = Design::findOrFail($id);

        $this->authorize('delete', $design);

        // delete the files associated to the record
        $sizes = ['thumbnail', 'large', 'original'];

        foreach ($sizes as $size) {
            $disk = Storage::disk($design->disk);
            $image_path = 'uploads/designs/' . $size . '/' . $design->image;

            // check if the file exists in the db
            if ($disk->exists($image_path)) {
                $disk->delete($image_path);
            }
        }

        $design->delete();

        return response()->json([
            'status' => true,
            'message' => 'Image was deleted successful'
        ]);
    }
}
