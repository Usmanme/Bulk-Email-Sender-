<?php

use App\Models\{
    AdditionalCost,
    SiteConfigration,
    Type,
    UserBatch,
};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\{Collection};
use Illuminate\Support\Facades\{Crypt, File};

// if (!function_exists('filter_strip_tags')) {

//     function filter_strip_tags($field): string
//     {
//         return trim(strip_tags($field));
//     }
// }

// if (!function_exists('encode_html_entities')) {

//     function encode_html_entities($field): string
//     {
//         return trim(htmlentities($field));
//     }
// }

// if (!function_exists('decode_html_entities')) {

//     function decode_html_entities($field): string
//     {
//         return trim(html_entity_decode($field));
//     }
// }

// if (!function_exists('encryptParams')) {
//     function encryptParams($params): array|string
//     {
//         if (is_array($params)) {
//             $data = [];
//             foreach ($params as $item) {
//                 $data[] = Crypt::encryptString($item);
//             }
//             return $data;
//         }
//         return Crypt::encryptString($params);
//     }
// }

// if (!function_exists('decryptParams')) {
//     function decryptParams($params): array|string
//     {
//         if (is_array($params)) {
//             $data = [];
//             foreach ($params as $item) {
//                 $data[] = Crypt::decryptString($item);
//             }
//             return $data;
//         }
//         return Crypt::decryptString($params);
//     }
// }

// if (!function_exists('getSiteConfiguration')) {
//     function getSiteConfiguration($site_id)
//     {

//         $site_id = decryptParams($site_id);

//         $siteConfiguration = (new SiteConfigration())->whereSiteId($site_id)->first();
//         return $siteConfiguration ?? null;
//     }
// }

if (!function_exists('getAllModels')) {
    function getAllModels($path = null): array
    {
        $Modelpath = ($path ?? app_path()) . "/Models";

        $out = [];
        $results = scandir($Modelpath);
        foreach ($results as $result) {
            //			dd($results);
            if ($result === '.' or $result === '..') continue;
            $filename = $Modelpath . '/' . $result;
            if (is_dir($filename)) {
                $out = array_merge($out, getAllModels($filename));
            } else {
                $out[] = substr($result, 0, -4);
            }
        }
        return $out;
    }
}

if (!function_exists('getTrashedDataCount')) {
    function getTrashedDataCount(): float|int
    {
        $trashed = [];
        foreach (getAllModels() as $model) {
            $models = app("App\Models\\" . $model);
            if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($models))) {
                $trashed[] = $models->onlyTrashed()->count();
            } else {
                $trashed[] = 0;
            }
        }
        return array_sum($trashed);
    }
}

// if (!function_exists('getTreeData')) {
//     function getTreeData(collection $collectionData, $model, $getFromDB = false): array
//     {
//         $typesTmp = [];

//         // $model = "\\App\\Models\\" . $model;
//         $dbTypes = ($getFromDB ? $model::all() : $collectionData);

//         foreach ($collectionData as $key => $row) {
//             $typesTmp[] = $row;
//             $typesTmp[$key]["tree"] = ($getFromDB ? getTypeParentTreeElequent($model, $row, $row->name, $collectionData, $dbTypes) : getTypeParentTreeCollection($row, $row->name, $collectionData));
//         }

//         return $typesTmp;
//         // dd($typesTmp);
//     }
// }

// if (!function_exists('getTypeParentTreeElequent')) {
//     function getTypeParentTreeElequent($model, $row, $name, collection $parent, $dbTypes)
//     {
//         if ($row->parent_id == 0) {
//             return $name;
//         }

//         $nextRow = $model::find($row->parent_id);
//         $name = $nextRow->name . ' > ' . $name;

//         return getTypeParentTreeElequent($model, $nextRow, $name, $parent, $dbTypes);
//     }
// }

// if (!function_exists('getTypeParentTreeCollection')) {
//     function getTypeParentTreeCollection($row, $name, collection $parent): string
//     {
//         if ($row->parent_id == 0) {
//             return $name;
//         }

//         $nextRow = $parent->firstWhere('id', $row->parent_id);
//         $name = (is_null($nextRow) ?? empty($nextRow) ? '' : $nextRow->name) . ' > ' . $name;
//         if (is_null($nextRow) ?? empty($nextRow)) {
//             return $name;
//         }

//         return getTypeParentTreeCollection($nextRow, $name, $parent, $parent);
//     }
// }

// if (!function_exists('getLinkedTreeData')) {
//     function getLinkedTreeData(Model $model, $id = [])
//     {
//         $id = $model::whereIn('parent_id', $id)->get()->toArray();
//         if (count($id) > 0) {
//             return array_merge($id, getLinkedTreeData($model, array_column($id, 'id')));
//         }
//         return $id;
//     }
// }

// if (!function_exists('base64ToImage')) {
//     function base64ToImage($image): string
//     {
//         ini_set('memory_limit', '256M');
//         $filename = 'TelK7BnW63IAN6zuTTwJkqZeuM0YI5aNc7aFqOyz.jpg';
//         if (!empty($image)) {
//             $dir = $_SERVER['DOCUMENT_ROOT'] . config('app.asset_url') . DIRECTORY_SEPARATOR . 'public_assets/admin/sites_images';
//             $image_parts = explode(";base64,", $image);
//             $image_type_aux = explode("image/", $image_parts[0]);
//             $image_type = $image_type_aux[1];
//             $image_base64 = base64_decode($image_parts[1]);
//             $filename = uniqid() . '.' . $image_type;
//             $file = $dir . DIRECTORY_SEPARATOR . $filename;
//             file_put_contents($file, $image_base64);
//         }
//         return $filename;
//     }
// }

// // if (!function_exists('makeImageThumbs')) {
// //     function makeImageThumbs($request, $key = ""): string
// //     {
// //         $publicPath = public_path('public_assets/admin/sites_images') . DIRECTORY_SEPARATOR;
// //         if (!is_string($request)) {
// //             if (is_array($request)) {
// //                 $image = $request[$key];
// //             } else {
// //                 $image = $request->file($key);
// //             }
// //             $imageHashedName = $image->hashName();
// //         } else {
// //             $image = $publicPath . $request;
// //         }

// //         $imgExplodedName = explode(".", $imageHashedName);

// //         $img = Image::make($image)->backup();

// //         $img->resize(1000, null, function ($constraint) {
// //             $constraint->aspectRatio();
// //             $constraint->upsize();
// //         })->save($publicPath . $imgExplodedName[0] . '-thumbs1000.' . $imgExplodedName[1]);
// //         $img->reset();

// //         $img->resize(600, null, function ($constraint) {
// //             $constraint->aspectRatio();
// //             $constraint->upsize();
// //         })->save($publicPath . $imgExplodedName[0] . '.' . $imgExplodedName[1]);
// //         $img->reset();

// //         $img->resize(350, null, function ($constraint) {
// //             $constraint->aspectRatio();
// //             $constraint->upsize();
// //         })->save($publicPath . $imgExplodedName[0] . '-thumbs350.' . $imgExplodedName[1]);
// //         $img->reset();

// //         $img->resize(200, null, function ($constraint) {
// //             $constraint->aspectRatio();
// //             $constraint->upsize();
// //         })->save($publicPath . $imgExplodedName[0] . '-thumbs200.' . $imgExplodedName[1]);
// //         $img->reset();

// //         $img->destroy();

// //         return $imgExplodedName[0] . '.' . $imgExplodedName[1];
// //     }
// // }

// if (!function_exists('deleteImageThumbs')) {
//     function deleteImageThumbs($imgName,  $imageDirectory = 'admin'): string
//     {
//         $publicServerPath = public_path('public_assets/' . $imageDirectory . '/sites_images') . DIRECTORY_SEPARATOR;
//         if (File::exists($publicServerPath . $imgName)) {
//             $imageExplodedName = explode(".", $imgName);
//             // dd($imageExplodedName);
//             File::delete([
//                 $publicServerPath . $imageExplodedName[0] . "." . $imageExplodedName[1],
//                 $publicServerPath . $imageExplodedName[0] . "-thumbs1000." . $imageExplodedName[1],
//                 $publicServerPath . $imageExplodedName[0] . "-thumbs350." . $imageExplodedName[1],
//                 $publicServerPath . $imageExplodedName[0] . "-thumbs200." . $imageExplodedName[1],
//             ]);

//             return true;
//         }
//         return false;
//     }
// }

// if (!function_exists('getImageByName')) {
//     function getImageByName($imgName, $imageDirectory = 'admin'): array
//     {
//         $img = "";
//         $imgThumb = "";
//         $publicServerPath = public_path('public_assets/' . $imageDirectory . '/sites_images') . DIRECTORY_SEPARATOR;
//         $publicLinkPath = asset('public_assets/' . $imageDirectory . '/sites_images') . DIRECTORY_SEPARATOR;

//         $imageExplodedName = explode('.', (!is_null($imgName) && !empty($imgName) ? $imgName : 'TelK7BnW63IAN6zuTTwJkqZeuM0YI5aNc7aFqOyz.jpg'));

//         if (File::exists($publicServerPath . ($imageExplodedName[0] . "-thumbs200." . $imageExplodedName[1]))) {
//             $img = $publicLinkPath . $imageExplodedName[0] . "-thumbs1000." . $imageExplodedName[1];
//             $imgThumb = $publicLinkPath . $imageExplodedName[0] . "-thumbs200." . $imageExplodedName[1];
//         } else if (File::exists($publicServerPath . ($imageExplodedName[0] . "." . $imageExplodedName[1]))) {
//             $img = $publicLinkPath . $imageExplodedName[0] . "." . $imageExplodedName[1];
//             $imgThumb = $publicLinkPath . $imageExplodedName[0] . "." . $imageExplodedName[1];
//         } else {
//             $img = $publicLinkPath . "do_not_delete/do_not_delete.png";
//             $imgThumb = $publicLinkPath . "do_not_delete/do_not_delete.png";
//         }

//         return [$img, $imgThumb];
//     }
// }

// if (!function_exists('editDateColumn')) {
//     function editDateColumn($date)
//     {
//         $date = new Carbon($date);

//         return "<span>" . $date->format('H:i:s') . "</span> <br> <span class='text-primary fw-bold'>" . $date->format('Y-m-d') . "</span>";
//     }
// }

// if (!function_exists('getTypeNameByID')) {
//     function getTypeNameByID($type_id)
//     {
//         $type = (new Type())->where('id', $type_id)->first();
//         if ($type) {
//             return $type->name;
//         }
//         return 'parent';
//     }
// }

// if (!function_exists('editBooleanColumn')) {
//     function editBooleanColumn($boolean)
//     {
//         if ($boolean) {
//             return "<span class='badge rounded-pill badge-light-success me-1'>" . __('lang.commons.yes') . "</span>";
//         } else {
//             return "<span class='badge rounded-pill badge-light-danger me-1'>" . __('lang.commons.no') . "</span>";
//         }
//     }
// }

// if (!function_exists('editBadgeColumn')) {
//     function editBadgeColumn($value)
//     {
//         return "<span class='badge rounded-pill badge-light-primary me-1'>" . $value . "</span>";
//     }
// }

// if (!function_exists('getTypeParentByParentId')) {
//     function getTypeParentByParentId($parent_id)
//     {
//         $type = (new Type())->where('id', $parent_id)->first();
//         if ($type) {
//             return $type->name;
//         }
//         return 'parent';
//     }
// }

// if (!function_exists('getAdditionalCostByParentId')) {
//     function getAdditionalCostByParentId($parent_id)
//     {
//         $additionalCost = (new AdditionalCost())->where('id', $parent_id)->first();
//         if ($additionalCost) {
//             return $additionalCost->name;
//         }
//         return 'parent';
//     }
// }

// if (!function_exists('getAuthentacatedUserInfo')) {
//     function getAuthentacatedUserInfo()
//     {
//         $user = new stdClass();
//         $user->data = auth()->user();
//         $user->roles = implode(', ', auth()->user()->roles->pluck('name')->toArray());
//         return $user;
//     }
// }

// if (!function_exists('getNHeightestNumber')) {
//     function getNHeightestNumber($numberOfDigits = 1)
//     {
//         return (int)str_repeat('9', $numberOfDigits);
//     }
// }

// if (!function_exists('getbatchesByUserID')) {
//     function getbatchesByUserID($user_id, $action_id = 0)
//     {

//         $user_id = decryptParams($user_id);

//         $batches = (new UserBatch())->whereUserId($user_id)->limit(20)->latest();
//         if ($action_id > 0) {
//             $batches = $batches->whereActionId($action_id)->limit(20)->latest();
//         }

//         return $batches->get() ?? null;
//     }
// }

// if (!function_exists('apiErrorResponse')) {
//     function apiErrorResponse($message = 'data not found', $key = 'error')
//     {
//         return response()->json(
//             [
//                 'status' => false,
//                 'message' => [
//                     $key => $message,
//                 ],
//                 'data' => null,
//                 'stauts_code' => '200'
//             ],
//             200
//         );
//     }
// }

// if (!function_exists('apiSuccessResponse')) {
//     function apiSuccessResponse($data = null, $message = 'data found', $key = 'success')
//     {
//         return response()->json(
//             [
//                 'status' => true,
//                 'message' => [
//                     $key => $message,
//                 ],
//                 'data' => $data,
//                 'stauts_code' => '200'
//             ],
//             200
//         );
//     }
// }
