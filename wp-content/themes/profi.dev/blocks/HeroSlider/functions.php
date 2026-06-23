<?php

// add_filter( 'wp_preload_resources', function ($image_preload_arr){

//     if (!is_array($image_preload_arr)) {
//         $image_preload_arr = [];
//     }

//     $post = get_post();

//     if (has_blocks($post->post_content)) {
//         $blocks = array_values(array_filter(parse_blocks($post->post_content), function ($block) {
//             return !($block['blockName'] === null && empty(trim($block['innerHTML'])));
//         }));
    
//         if ($blocks[0]['blockName'] === 'theme/hero') {
            
//             if (array_key_exists('right_image', $blocks[0]['attrs']['data']) && !empty($blocks[0]['attrs']['data']['right_image'])) {

//                 $image_id = $blocks[0]['attrs']['data']['right_image'];

//                 $image_preload_arr[] = [
//                     'href' => wp_get_attachment_url($image_id),
//                     'as' => 'image',
//                     'imagesizes' => wp_get_attachment_image_sizes($image_id, [550, 'auto']),
//                     'imagesrcset' => wp_get_attachment_image_srcset($image_id, [550, 'auto']),
//                 ]; 
//             } 
//         }
//     }

//     return $image_preload_arr;
// });
