@extends('layouts.admin')

@section('title', 'श्रेणियां प्रबंधन')
@section('page_title', 'श्रेणियां (Manage Categories)')

@section('content')

<div style="display: grid; grid-template-columns: 1.1fr 2fr; gap: 24px;" class="cat-admin-grid">
    
    <!-- Left: Add New Category Form -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><i class="fa-solid fa-plus-circle"></i> नई श्रेणी जोड़ें (Add Category)</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label" for="name">श्रेणी का नाम (Category Name) <span style="color: #dc2626;">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="जैसे: राजनीति, खेल, सिनेमा" required value="{{ old('name') }}" />
                </div>

                <div class="form-group">
                    <label class="form-label" for="slug">कस्टम स्लग (Slug - Optional)</label>
                    <input type="text" id="slug" name="slug" class="form-control" placeholder="politics" value="{{ old('slug') }}" />
                </div>

                <div class="form-group">
                    <label class="form-label" for="color">रंग कोड (Accent Color)</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="color" id="colorPicker" value="#dc2626" onchange="document.getElementById('colorInput').value = this.value;" style="height: 38px; width: 44px; padding: 0; border: none; border-radius: 4px; cursor: pointer;" />
                        <input type="text" id="colorInput" name="color" class="form-control" value="{{ old('color', '#dc2626') }}" oninput="document.getElementById('colorPicker').value = this.value;" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="order">क्रम संख्या (Display Order)</label>
                    <input type="number" id="order" name="order" class="form-control" value="{{ old('order', 0) }}" />
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">विवरण (Description)</label>
                    <textarea id="description" name="description" class="form-control" rows="2" placeholder="श्रेणी के बारे में संक्षिप्त विवरण...">{{ old('description') }}</textarea>
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13.5px;">
                        <input type="checkbox" name="is_featured" value="1" checked />
                        <span>होमपेज पर दिखाएं (Feature on Homepage)</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13.5px;">
                        <input type="checkbox" name="is_active" value="1" checked />
                        <span>सक्रिय रखें (Active Status)</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                    <i class="fa-solid fa-plus"></i> श्रेणी सेव करें
                </button>
            </form>
        </div>
    </div>

    <!-- Right: Categories Table -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><i class="fa-solid fa-list"></i> सभी श्रेणियां (Total: {{ $categories->count() }})</h4>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>क्रम</th>
                            <th>नाम</th>
                            <th>स्लग</th>
                            <th>कलर</th>
                            <th>कुल खबरें</th>
                            <th>स्थिति</th>
                            <th style="text-align: right;">कार्रवाई</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->order }}</td>
                                <td>
                                    <strong style="color: #0f172a;">{{ $category->name }}</strong>
                                </td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td>
                                    <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px;">
                                        <span style="width: 14px; height: 14px; border-radius: 3px; background: {{ $category->color }};"></span>
                                        {{ $category->color }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $category->posts_count }} पोस्ट्स</span>
                                </td>
                                <td>
                                    @if($category->is_active)
                                        <span class="badge badge-success">सक्रिय</span>
                                    @else
                                        <span class="badge badge-danger">निष्क्रिय</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                        <button type="button" class="btn btn-primary btn-sm" onclick="openEditModal({{ json_encode($category) }})" title="संपादित करें">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('क्या आप वाकई इस श्रेणी को हटाना चाहते हैं?');" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="हटाएं">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; color: #64748b; padding: 30px;">कोई श्रेणी नहीं मिली।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- ── Edit Category Modal ── -->
<div id="editCategoryModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; width: 90%; max-width: 480px; border-radius: 12px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.3); position: relative;">
        <button onclick="closeEditModal()" style="position: absolute; top: 15px; right: 15px; border: none; background: #f1f5f9; width: 30px; height: 30px; border-radius: 50%; cursor: pointer;">&times;</button>
        <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 18px;">श्रेणी संपादित करें (Edit Category)</h3>

        <form id="editCategoryForm" action="" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="edit_name">नाम (Category Name)</label>
                <input type="text" id="edit_name" name="name" class="form-control" required />
            </div>

            <div class="form-group">
                <label class="form-label" for="edit_slug">स्लग (Slug)</label>
                <input type="text" id="edit_slug" name="slug" class="form-control" />
            </div>

            <div class="form-group">
                <label class="form-label" for="edit_color">रंग कोड (Color)</label>
                <input type="text" id="edit_color" name="color" class="form-control" />
            </div>

            <div class="form-group">
                <label class="form-label" for="edit_order">क्रम संख्या (Order)</label>
                <input type="number" id="edit_order" name="order" class="form-control" />
            </div>

            <div class="form-group">
                <label class="form-label" for="edit_description">विवरण (Description)</label>
                <textarea id="edit_description" name="description" class="form-control" rows="2"></textarea>
            </div>

            <div style="display: flex; gap: 15px; margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 6px; font-size: 13.5px;">
                    <input type="checkbox" id="edit_is_featured" name="is_featured" value="1" />
                    <span>होमपेज पर दिखाएं</span>
                </label>
                <label style="display: flex; align-items: center; gap: 6px; font-size: 13.5px;">
                    <input type="checkbox" id="edit_is_active" name="is_active" value="1" />
                    <span>सक्रिय (Active)</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                <i class="fa-solid fa-floppy-disk"></i> बदलाव सहेजें
            </button>
        </form>
    </div>
</div>

<script>
    function openEditModal(category) {
        const form = document.getElementById('editCategoryForm');
        form.action = `/admin/categories/${category.id}`;

        document.getElementById('edit_name').value = category.name;
        document.getElementById('edit_slug').value = category.slug;
        document.getElementById('edit_color').value = category.color;
        document.getElementById('edit_order').value = category.order;
        document.getElementById('edit_description').value = category.description || '';
        document.getElementById('edit_is_featured').checked = category.is_featured;
        document.getElementById('edit_is_active').checked = category.is_active;

        document.getElementById('editCategoryModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editCategoryModal').style.display = 'none';
    }
</script>

<style>
@media (max-width: 900px) {
    .cat-admin-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
