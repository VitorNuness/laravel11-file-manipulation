<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::post('/', function (Request $request) {
    $file = $request->file('file');
    $file->store(
        $file->hashName(),
        'files',
    );
});

Route::put('/', function (Request $request) {
    $file = $request->file('file');
    Storage::disk('files')->put(
        $file->hashName(),
        $request->content
    );
});

Route::delete('/', function (Request $request) {
    $file = $request->file('file');
    Storage::disk('files')->delete($file->hashName());
});

Route::get('/{fileName}', function (string $fileName) {
    $file = Storage::disk('files')->get($fileName);
    return $file;
});

Route::get('/{fileName}/url', function (string $fileName) {
    $file = Storage::disk('files')->url($fileName);
    return $file;
});
