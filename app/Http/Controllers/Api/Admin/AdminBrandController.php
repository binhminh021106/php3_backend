<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class AdminBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brand = Brand::all();

        return response()->json($brand);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('brands', 'name')->whereNull('deleted_at'),
                ],
                'image' => [
                    'required',
                    'image',
                    'mimes:jpeg,png,jpg,gif,svg,webp',
                    'max:5120'
                ],
                'description' => [
                    'required',
                    'string'
                ],
                'status' => [
                    'required',
                    'integer'
                ],
                'order_number' => [
                    'required',
                    'integer',
                    'min:0',
                    Rule::unique('brands', 'order_number')->whereNull('deleted_at')
                ],
            ],
            [
                'required' => ':attribute không được để trống',
                'name.unique' => 'Tên thương hiệu không được để trống',
                'order_number.unique' => 'Số thứ tự đã trùng với số trước',
                'exists' => ':attribute không tồn tại trong hệ thống',
                'integer' => ':attribute không phải là số',
                'image' => ':attribute không phải là hình ảnh',
                'mimes' => ':attribute chỉ chấp nhận định dạng: jpeg, png, jpg, gif, svg, webp',
                'max:' => ':attribute chỉ chấp nhận file dưới hoặc 5MB',
            ],
            [
                'name' => 'Tên thương hiệu',
                'image' => 'Hình ảnh',
                'description' => 'Mô tả',
                'order_number' => 'Số thứ tự'
            ]
        );

        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');

                $path = $file->store('brand', 'public');
                $validated['image'] = $path;
            }

            $brand = Brand::create($validated);

            $brand->image_full_url = Storage::url($brand->image);

            return response()->json([
                'status' => true,
                'message' => 'Thêm thương hiệu thành công',
                'data' => $brand
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
        $brand = Brand::findOrFail($id);

        return response()->json($brand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $brand = Brand::findOrFail($id);

        $validated = $request->validate(
            [
                'name' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('brands', 'name')->ignore($id)->whereNull('deleted_at')
                ],
                'image' => [
                    'sometimes',
                    'required',
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
                    'integer'
                ],
                'order_number' => [
                    'sometimes',
                    'required',
                    'integer',
                    'min:0',
                    Rule::unique('brands', 'order_number')->ignore($id)->whereNull('deleted_at')
                ]
            ],
            [
                'required' => ':attribute không được để trống',
                'name.unique' => 'Tên thương hiệu không được để trống',
                'order_number.unique' => 'Số thứ tự đã trùng với số trước',
                'exists' => ':attribute không tồn tại trong hệ thống',
                'integer' => ':attribute không phải là số',
                'image' => ':attribute không phải là hình ảnh',
                'mimes' => ':attribute chỉ chấp nhận định dạng: jpeg, png, jpg, gif, svg, webp',
                'max:' => ':attribute chỉ chấp nhận file dưới hoặc 5MB',
            ],
            [
                'name' => 'Tên thương hiệu',
                'image' => 'Hình ảnh',
                'description' => 'Mô tả',
                'order_number' => 'Số thứ tự'
            ]
        );

        try {
            if ($request->hasFile('image')) {

                if ($brand->image && Storage::disk('public')->exists($brand->image)) {
                    Storage::disk('public')->delete($brand->image);
                }

                $path = $request->file('image')->store('brand', 'public');
                $validated['image'] = $path;
            }

            $brand->update($validated);

            $brand->image_full_url = Storage::url($brand->image);

            return response()->json([
                'status' => true,
                'message' => 'Cập nhật thương hiệu thành công',
                'data' => $brand
            ]);
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
        $brand = Brand::findOrFail($id);

        try {


            if ($brand->image && Storage::disk('public')->exists($brand->image)) {
                Storage::disk('public')->delete($brand->image);
            }

            $brand->delete();

            return response()->json([
                'status' => true,
                'message' => 'Xóa thương hiệu thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
            ], 500);
        }
    }
}
