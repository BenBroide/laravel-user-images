<?php
function store_random_image(){
    $unsplash_api_url = 'https://source.unsplash.com/random';
    $contents = file_get_contents($unsplash_api_url);
    $image_name = uniqid().'.jpeg';
    Storage::disk('local')->put('public/'.$image_name, $contents);

    return $image_name;
}
