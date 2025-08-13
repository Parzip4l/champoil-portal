<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Version;
use App\Models\Version\AppsVersion;
use App\Models\Version\VersionDetail;

class VersionController extends Controller
{

    public function index()
    {
        $appsVersions = AppsVersion::all();
        if ($appsVersions->isEmpty()) {
            return response()->json(['message' => 'No apps versions found'], 404);
        }

        $result=[];
        foreach ($appsVersions as $appsVersion) {
            $result[] = [
                'app_platform' => $appsVersion->name,
                'versions' => VersionDetail::where('platform_id', $appsVersion->id)->orderBy('id','desc')->get()
            ];
        }

        return response()->json($result);
    }


    public function version()
    {
        $version = [
            "id"=>VersionDetail::where('platform_id', AppsVersion::where('name', 'android')->first()->id ?? null)
            ->where('release_type', 'stable')
            ->orderBy('id', 'desc')
            ->value('id'),
            "android" => VersionDetail::where('platform_id', AppsVersion::where('name', 'android')->first()->id ?? null)
                                      ->where('release_type', 'stable')
                                      ->orderBy('id', 'desc')
                                      ->value('version_name'),
            "android_build" => VersionDetail::where('platform_id', AppsVersion::where('name', 'android')->first()->id ?? null)
                                            ->where('release_type', 'stable')
                                            ->orderBy('id', 'desc')
                                            ->value('version_code'),
            "ios" => VersionDetail::where('platform_id', AppsVersion::where('name', 'ios')->first()->id ?? null)
                                  ->where('release_type', 'stable')
                                  ->orderBy('id', 'desc')
                                  ->value('version_name'),
            "ios_build" => VersionDetail::where('platform_id', AppsVersion::where('name', 'ios')->first()->id ?? null)
                                        ->where('release_type', 'stable')
                                        ->orderBy('id', 'desc')
                                        ->value('version_code'),
            "created_at" => VersionDetail::where('platform_id', AppsVersion::where('name', 'android')->first()->id ?? null)
                                            ->where('release_type', 'stable')
                                            ->orderBy('id', 'desc')
                                            ->value('released_at'),
            "updated_at" => VersionDetail::where('platform_id', AppsVersion::where('name', 'android')->first()->id ?? null)
                                            ->where('release_type', 'beta')
                                            ->orderBy('id', 'desc')
                                            ->value('released_at')
        ];

        return response()->json($version);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'app_name' => 'required|string',
            'platform_id' => 'required|exists:apps_versions,id',
            'version_code' => 'required|integer',
            'version_name' => 'required|string',
            'changelog' => 'nullable|string',
            'release_type' => 'required|in:stable,beta',
            'download_url' => 'url',
            'released_at' => 'required|date',
        ], [], [], function ($validator) {
            // Return JSON response on validation failure
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
        });

        $versionDetail = VersionDetail::create($validatedData);

        return response()->json([
            'message' => 'Version detail created successfully',
            'success'=> true,
            'data' => $versionDetail
        ], 201);
    }
}
