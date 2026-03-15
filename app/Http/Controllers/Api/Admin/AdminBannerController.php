<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class AdminBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banner = Banner::all();

        return response()->json($banner);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'title' => [
                    'required',
                    'string',
                    'max:255'
                ],
                'slug' => [
                    'required',
                    'string',
                    'max:255'
                ],
                'image_banner' => [
                    'required',
                    'image',
                    'mimes:jpeg,png,jpg,gif,svg,webp',
                    'max:5120'
                ],
                'description' => [
                    'required',
                    'string',
                ],
                'status' => [
                    'required',
                    'integer'
                ],
                'order_number' => [
                    'required',
                    'integer',
                    'min:0',
                    Rule::unique('banners', 'order_number')->whereNull('deleted_at')
                ]
            ],
            [
                'required' => ':attribute không được để trống',
                'order_number.unique' => 'Số thứ tự đã trùng với số trước',
                'exists' => ':attribute không tồn tại trong hệ thống',
                'integer' => ':attribute không phải là số',
                'image' => ':attribute không phải là hình ảnh',
                'mimes' => ':attribute chỉ chấp nhận định dạng: jpeg, png, jpg, gif, svg, webp',
                'max:' => ':attribute chỉ chấp nhận file dưới hoặc 5MB',
            ],
            [
                'title' => 'Tiêu đề',
                'image_banner' => 'Banner',
                'description' => 'Mô tả',
                'order_number' => 'Số thứ tự'
            ]
        );

        try {

            if ($request->hasFile('image_banner')) {
                $file = $request->file('image_banner');

                $path = $file->store('banner', 'public');
                $validated['image_banner'] = $path;
            }

            $banner = Banner::create($validated);

            $banner->image_banner_full_url = Storage::url($banner->image_banner);

            return response()->json([
                'status' => true,
                'message' => 'Thêm banner thành công',
                'data' => $banner
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $banner = Banner::findOrFail($id);

        return response()->json($banner);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $banner = Banner::findOrFail($id);

        $validated = $request->validate(
            [
                'title' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255'
                ],
                'slug' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255'
                ],
                'image_banner' => [
                    'sometimes',
                    'image',
                    'mimes:jpeg,png,jpg,gif,svg,webp',
                    'max:5120'
                ],
                'description' => [
                    'sometimes',
                    'required',
                    'string'
                ],
                'status' => [
                    'sometimes',
                    'required',
                    'integer'
                ],
                'order_number' => [
                    'sometimes',
                    'required',
                    'integer',
                    'min:0',
                    Rule::unique('banner', 'order_number')->ignore($id)->whereNull('deleted_at')
                ]
            ],
            [
                'required' => ':attribute không được để trống',
                'order_number.unique' => 'Số thứ tự đã trùng với số trước',
                'exists' => ':attribute không tồn tại trong hệ thống',
                'integer' => ':attribute không phải là số',
                'image' => ':attribute không phải là hình ảnh',
                'mimes' => ':attribute chỉ chấp nhận định dạng: jpeg, png, jpg, gif, svg, webp',
                'max:' => ':attribute chỉ chấp nhận file dưới hoặc 5MB',
            ],
            [
                'title' => 'Tiêu đề',
                'image_banner' => 'Banner',
                'description' => 'Mô tả',
                'order_number' => 'Số thứ tự'
            ]
        );

        try {

            if ($request->hasFile('image_banner')) {
                $file = $request->file('image_banner');

                $path = $file->store('banner', 'public');
                $validated['image_banner'] = $path;
            }

            $banner = Banner::create($validated);

            $banner->image_banner_full_url = Storage::url($banner->image_banner);

            return response()->json([
                'status' => true,
                'message' => 'Sửa banner thành công',
                'data' => $banner
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = Banner::findOrFail($id);

        try {

            if ($banner->image_banner && Storage::disk('public')->exists($banner->image_banner)) {
                Storage::disk('public')->delete($banner->image_banner);
            }

            $banner->delete();

            return response()->json([
                'status' => true,
                'message' => 'Xóa banner thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
            ], 500);
        }
    }
}
