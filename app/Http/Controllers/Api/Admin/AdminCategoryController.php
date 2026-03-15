<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::all();

        return response()->json($category);
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
                    Rule::unique('categories', 'name')->whereNull('deleted_at'),
                ],
                'parent_id' => [
                    'nullable',
                    'integer',
                    'exists:categories,id'
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
                    'integer',
                ],
                'order_number' => [
                    'required',
                    'integer',
                    'min:0',
                    Rule::unique('categories', 'order_number')->whereNull('deleted_at')
                ]
            ],
            [
                'required' => ':attribute không được để trống',
                'name.unique' => 'Tên danh mục đã tồn tại',
                'order_number.unique' => 'Số thứ tự đã trùng với số trước',
                'exists' => ':attribute không tồn tại trong hệ thống',
                'integer' => ':attribute phải là số',
                'image' => ':attribute không phải là hình ảnh',
                'mimes' => ':attribute chỉ chấp nhận định dạng: jpeg, png, jpg, gif, svg, webp',
                'max:' => ':attribute chỉ chấp nhận file dưới hoặc 5MB',
            ],
            [
                'name' => 'Tên danh mục',
                'image' => 'Hình ảnh',
                'parent_id' => 'Danh mục cha',
                'description' => 'Mô tả',
                'order_number' => 'Số thứ tự'
            ]
        );

        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');

                $path = $file->store('category', 'public');
                $validated['image'] = $path;
            }

            $category = Category::create($validated);

            $category->image_full_url = Storage::url($category->image);

            return response()->json([
                'status' => true,
                'message' => 'Thêm danh mục thành công',
                'data' => $category
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
        $category = Category::findOrFail($id);

        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate(
            [
                'name' => [
                    'c',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('categories', 'name')->ignore($id)->whereNull('deleted_at'),
                ],
                'parent_id' => [
                    'nullable',
                    'integer',
                    'exists:categories,id'
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
                    'required',
                    'integer',
                ],
                'order_number' => [
                    'sometimes',
                    'required',
                    'integer',
                    'min:0',
                    Rule::unique('categories', 'order_number')->ignore($id)->whereNull('deleted_at')
                ]
            ],
            [
                'required' => ':attribute không được để trống',
                'name.unique' => 'Tên danh mục đã tồn tại',
                'order_number.unique' => 'Số thứ tự đã trùng với số trước',
                'exists' => ':attribute không tồn tại trong hệ thống',
                'integer' => ':attribute phải là số',
                'image' => ':attribute không phải là hình ảnh',
                'mimes' => ':attribute chỉ chấp nhận định dạng: jpeg, png, jpg, gif, svg, webp',
                'max:' => ':attribute chỉ chấp nhận file dưới hoặc 5MB',
            ],
            [
                'name' => 'Tên danh mục',
                'image' => 'Hình ảnh',
                'parent_id' => 'Danh mục cha',
                'description' => 'Mô tả',
                'order_number' => 'Số thứ tự'
            ]
        );

        try {

            if ($request->hasFile('image')) {

                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }

                $path = $request->file('image')->store('category', 'public');
                $validated['image'] = $path;
            }

            $category->update($validated);

            $category->image_full_url = Storage::url($category->image);

            return response()->json([
                'status' => true,
                'message' => 'Cập nhật danh mục thành công',
                'data' => $category
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
        $category = Category::findOrFail($id);

        try {

            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            $category->delete();

            return response()->json([
                'status' => true,
                'message' => 'Xóa danh mục thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
            ], 500);
        }
    } 
}
