<?php




if (!function_exists('url2dir')) {
    function url2dir($path)
    {
        if (trim($path) == '') {
            return false;
        }

        $path = str_ireplace(site_url(), MW_ROOTPATH, $path);
        $path = str_replace('\\', '/', $path);
        $path = str_replace('//', '/', $path);

        return normalize_path($path, false);
    }
}

if (!function_exists('dir2url')) {
    function dir2url($path)
    {
        $path = str_ireplace(MW_ROOTPATH, '', $path);
        $path = str_replace('\\', '/', $path);
        $path = str_replace('//', '/', $path);

        //var_dump($path);
        return site_url($path);
    }
}



if (!function_exists('shop_url')) {
    function shop_url($add_string = false)
    {
        $shopPage = app()->content_repository->getFirstShopPage();
        if (!empty($shopPage)) {
            return content_link($shopPage['id']);
        }

        return site_url();
    }
}

