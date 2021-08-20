<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AzureController extends Controller
{

    public function imageUpload()
    {
        return view("azure.image_upload");
    }

    public function images()
    {
        return view("azure.images");
    }

    public function ajaxImageGet()
    {

        $storageImages = Storage::allFiles();
        return array_map(function ($image) {

            return [$image, Storage::url(AzureStorage::getBlobAccessFile($image))];
        }, $storageImages);

    }

    public function imageDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "url" => "required"
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $val) {
                return $val;
            }
        }
        $imageDeleteStorage = Storage::delete($request->url);

        if ($imageDeleteStorage) {
            return response()->json(["success"], 204);
        }
    }

    public function imageUploadStorage(Request $request): \Illuminate\Http\RedirectResponse
    {
        $img = $request->file("fileUpload");
        $path = $img->getRealPath();
        if ($request->hasFile("fileUpload")) {
            $imageExtension = $request->file("fileUpload")->getClientOriginalExtension();
            $imageName = "images/users/" . Str::random(16) . "." . $imageExtension;
            Storage::disk('azure')->put($imageName, file_get_contents($path));
            return redirect()->route("get");
        }
    }

    private function imageController($url)
    {
        preg_match("/[A-Za-z0-9]+.jpg|[A-Za-z0-9]+.png|[A-Za-z0-9]+.jpeg/i", $url, $result);
    }

    public function imageUploadUpdateStorage(Request $request)
    {

        $image = $request->file("imageUpload");
        $decryptVideoName = $request->videoName;
        $path = $image->getRealPath();
        if ($request->hasFile("imageUpload")) {
            $imageName = "images/users/" . Str::random(16) . "." . $image->getClientOriginalExtension();
            Storage::put($decryptVideoName, file_get_contents($path));
            $image->move(".", $imageName);
        }
    }
}
