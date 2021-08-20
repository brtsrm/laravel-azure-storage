<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class AzureStorage extends Controller
{

    protected static function generateSharedAccessSignature($accountName, $storageKey, $signedPermissions, $signedService, $signedResourceType, $signedStart, $signedExpiry, $signedIP, $signedProtocol, $signedVersion)
    {

        if (empty($accountName)) {
            trigger_error("The account name is required.");
            return;
        }

        if (empty($storageKey)) {
            trigger_error("The account key is required.");
            return;
        }

        if (empty($signedPermissions)) {
            trigger_error("The permissions are required.");
            return;
        }

        if (empty($signedService)) {
            trigger_error("The services are required.");
            return;
        }

        if (empty($signedResourceType)) {
            trigger_error("The resource types are required.");
            return;
        }

        if (empty($signedExpiry)) {
            trigger_error("The expiration time is required.");
            return;
        }

        if (empty($signedVersion)) {
            trigger_error("The service version is required.");
            return;
        }

        $_toSign = urldecode($accountName) . "\n" .
            urldecode($signedPermissions) . "\n" .
            urldecode($signedService) . "\n" .
            urldecode($signedResourceType) . "\n" .
            urldecode($signedStart) . "\n" .
            urldecode($signedExpiry) . "\n" .
            urldecode($signedIP) . "\n" .
            urldecode($signedProtocol) . "\n" .
            urldecode($signedVersion) . "\n";
        // sign the string using hmac sha256 and get a base64 encoded version_compare
        $_signature = base64_encode(hash_hmac("sha256", utf8_encode($_toSign), base64_decode($storageKey), true));
        return $_signature;
    }

    protected static function getBlobUrlWithSharedAccessSignature($blobUri, $signedVersion, $signedService, $signedResourceType, $signedPermissions, $signedStart, $signedExpiry, $signedIP, $signedProtocol, $signature)
    {


        /* Create the signed query part */
        $_urlParts = array();
        $_urlParts[] = "sv=" . $signedVersion;
        $_urlParts[] = "ss=" . $signedService;
        $_urlParts[] = "srt=" . $signedResourceType;
        $_urlParts[] = "sp=" . $signedPermissions;
        $_urlParts[] = "st=" . $signedStart;
        $_urlParts[] = "se=" . $signedExpiry;
        $_urlParts[] = "spr=" . $signedProtocol;
        $_urlParts[] = "sig=" . urlencode($signature);

        $_blobUrlWithSAS = $blobUri . "?" . implode("&", $_urlParts);

        return $_blobUrlWithSAS;
    }


    /*
     *  *** signedVersion ***
     * Default Blob - b
     *
     *  - Blob (b)
     *  - Queue (q)
     *  - Table (t)
     *  - File (f)
     */

    protected static function signedStart(): string
    {

        return Carbon::make(Carbon::now())->format("Y-m-d") . "T" . Carbon::make(Carbon::now())->format("H:i:s") . "Z";

    }


    /*
     *  *** signedVersion ***
     * Default Blob - b
     *
     *  - Blob (b)
     *  - Queue (q)
     *  - Table (t)
     *  - File (f)
     */

    protected static function signedExpiry($maxDay = 5): string
    {


        return Carbon::make(Carbon::now())->addDays($maxDay)->format("Y-m-d") . "T" . Carbon::make(Carbon::now())->format("H:i:s") . "Z";

    }


    /*
     *  *** SignedServices  ***
     * Default Blob - b
     *
     *  - Blob (b)
     *  - Queue (q)
     *  - Table (t)
     *  - File (f)
     */
    protected static function signedServices($sv = "b"): string
    {
        $signedServicesAll = ["b", "q", "t", "f"];
        if (in_array($sv, $signedServicesAll)) {
            return $sv;
        }
    }

    /*
     *  *** signedPermissions ***
     *
     *    Default Read = r
     *
     *  - Read               = r
     *  - Write              = w
     *  - Delete             = d
     *  - Permanent Delete   = y
     *  - List               = l
     *  - Add                = a
     *  - Create             = c
     *  - Update             = u
     *  - Process            = p
     */

    protected static function signedPermission($sp = "r"): string
    {
        $signedPermissionAll = ["r", "w", "d", "y", "l", "a", "c", "u", "p"];
        if (in_array($sp, $signedPermissionAll)) {
            return $sp;
        }
    }

    /*
     *   *** SignedResourceTypes ***
     *    Default Service = s
     *
     *    Service : s
     *    Container : c
     *    Object : o
     *
     */

    protected static function signedResourceTypes($srt = "o"): string
    {
        $signedResourceTypes = ["s", "c", "o"];
        if (in_array($srt, $signedResourceTypes)) {
            return $srt;
        }
    }

    protected static function signedIp($sip = "")
    {
        return $sip;
    }

    protected static function signedProtocol($spr = "https"): string
    {
        return $spr;
    }

    protected static function signedVersion(): string
    {
        return date("Y-m-d");
    }

    public static function getBlobAccessFile($url)
    {
        $_storageKey = env("AZURE_STORAGE_KEY");
        $_accountName = env("azure_storage_name");
        $_signedPermissions = self::signedPermission();
        $_signedService = self::signedServices();
        $_signedResourceType = self::signedResourceTypes();
        $_signedStart = self::signedStart();
        $_signedExpiry = self::signedExpiry();
        $_signedIP = self::signedIp();
        $_signedProtocol = self::signedProtocol();
        $_signedVersion = "2015-12-11";
        // generate the signature
        $_signature = self::generateSharedAccessSignature($_accountName,
            $_storageKey,
            $_signedPermissions,
            $_signedService,
            $_signedResourceType,
            $_signedStart,
            $_signedExpiry,
            $_signedIP,
            $_signedProtocol,
            $_signedVersion);
        return self::getBlobUrlWithSharedAccessSignature($url,
            $_signedVersion,
            $_signedService,
            $_signedResourceType,
            $_signedPermissions,
            $_signedStart,
            $_signedExpiry,
            $_signedIP,
            $_signedProtocol,
            $_signature);
    }
}
