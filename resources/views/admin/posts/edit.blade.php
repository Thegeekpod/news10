@extends('layouts.admin')

@section('title', 'Edit News Article')
@section('page_title', 'Edit News Article')

@section('content')

<form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div style="display: grid; grid-template-columns: 2.2fr 1fr; gap: 24px;" class="post-form-grid">
        
        <!-- Left: Main Content Column -->
        <div>
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label" for="title">Headline / Title <span style="color: #dc2626;">*</span></label>
                        <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $post->title) }}" required style="font-size: 16px; font-weight: 600;" />
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="slug">Custom Slug (URL Slug)</label>
                        <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $post->slug) }}" />
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="summary">Short Summary / Lead Paragraph</label>
                        <textarea id="summary" name="summary" class="form-control" rows="3">{{ old('summary', $post->summary) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="content">Article Body <span style="color: #dc2626;">*</span></label>
                        
                        <!-- Mini Formatting Toolbar -->
                        <div style="background: #f1f5f9; padding: 6px 10px; border-radius: 6px 6px 0 0; border: 1px solid var(--border-color); border-bottom: none; display: flex; gap: 6px; flex-wrap: wrap;">
                            <button type="button" onclick="formatDoc('bold')" class="btn btn-outline btn-sm" style="padding: 3px 8px;"><i class="fa-solid fa-bold"></i></button>
                            <button type="button" onclick="formatDoc('italic')" class="btn btn-outline btn-sm" style="padding: 3px 8px;"><i class="fa-solid fa-italic"></i></button>
                            <button type="button" onclick="formatDoc('formatBlock', '<h3>')" class="btn btn-outline btn-sm" style="padding: 3px 8px;">H3</button>
                            <button type="button" onclick="formatDoc('formatBlock', '<h4>')" class="btn btn-outline btn-sm" style="padding: 3px 8px;">H4</button>
                            <button type="button" onclick="formatDoc('insertUnorderedList')" class="btn btn-outline btn-sm" style="padding: 3px 8px;"><i class="fa-solid fa-list-ul"></i></button>
                            <button type="button" onclick="formatDoc('insertOrderedList')" class="btn btn-outline btn-sm" style="padding: 3px 8px;"><i class="fa-solid fa-list-ol"></i></button>
                            <button type="button" onclick="formatDoc('formatBlock', '<blockquote>')" class="btn btn-outline btn-sm" style="padding: 3px 8px;"><i class="fa-solid fa-quote-left"></i></button>
                            <button type="button" onclick="formatLink()" class="btn btn-outline btn-sm" style="padding: 3px 8px;"><i class="fa-solid fa-link"></i></button>
                        </div>

                        <div id="editorContent" contenteditable="true" style="min-height: 280px; padding: 16px; border: 1px solid var(--border-color); border-radius: 0 0 7px 7px; background: #fff; font-size: 15px; line-height: 1.7; outline: none;">{!! old('content', $post->content) !!}</div>
                        <input type="hidden" name="content" id="hiddenContent" value="{{ old('content', $post->content) }}" />
                    </div>
                </div>
            </div>

            <!-- SEO Accordion Card -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="fa-solid fa-magnifying-glass"></i> SEO Metadata</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label" for="meta_title">Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" class="form-control" value="{{ old('meta_title', $post->meta_title) }}" />
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="meta_description">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" class="form-control" rows="2">{{ old('meta_description', $post->meta_description) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="meta_keywords">Keywords</label>
                        <input type="text" id="meta_keywords" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $post->meta_keywords) }}" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Publishing Meta & Image Column -->
        <div>
            <!-- Publish Card -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="fa-solid fa-paper-plane"></i> Publishing Status</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label" for="status">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <div style="margin: 20px 0;">
                        <label style="display: block; font-size: 14px; font-weight: 700; color: #f8fafc; margin-bottom: 12px; background: #1e293b; padding: 8px 12px; border-radius: 6px;">Article Placement Settings (নিবন্ধ স্থাপনের সেটিংস)</label>
                        <div style="display: flex; flex-direction: column; gap: 12px; padding: 14px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13.5px; font-weight: 600; color: #1e293b;">
                                <input type="checkbox" name="is_breaking" value="1" {{ old('is_breaking', $post->is_breaking) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: #6366f1;" />
                                <span>Show in Breaking Ticker (ব্রেকিং নিউজ টিকারে দেখান)</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13.5px; font-weight: 600; color: #1e293b;">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $post->is_featured) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: #6366f1;" />
                                <span>Show as Lead News (হোমপেজে ভিডিওর পাশে প্রধান সংবাদে দেখান)</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13.5px; font-weight: 600; color: #1e293b;">
                                <input type="checkbox" name="is_trending" value="1" {{ old('is_trending', $post->is_trending) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: #6366f1;" />
                                <span>Show in Popular (সাইডবারে সবচেয়ে জনপ্রিয় সংবাদে দেখান)</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13.5px; font-weight: 600; color: #1e293b;">
                                <input type="checkbox" name="is_editor_pick" value="1" {{ old('is_editor_pick', $post->is_editor_pick) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: #6366f1;" />
                                <span>📌 Editor's Pick</span>
                            </label>
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center;" onclick="syncContent()">
                            <i class="fa-solid fa-floppy-disk"></i> Save Changes
                        </button>
                    </div>
                </div>
            </div>

            <!-- Category & Tags Card -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="fa-solid fa-folder-tree"></i> Categories & Tags</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label" for="category_id">Category <span style="color: #dc2626;">*</span></label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $post->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tags</label>
                        <div style="max-height: 140px; overflow-y: auto; border: 1px solid var(--border-color); padding: 10px; border-radius: 6px; background: #fff;">
                            @foreach($tags as $tag)
                                <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; margin-bottom: 6px; cursor: pointer;">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $selectedTags)) ? 'checked' : '' }} />
                                    <span>#{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured Image Card -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="fa-solid fa-image"></i> Featured Image</h4>
                </div>
                <div class="card-body">
                    @if($post->image_url)
                        <div style="margin-bottom: 12px; text-align: center;">
                            <img id="imagePreview" src="{{ $post->image_url }}" alt="Current Image" style="max-width: 100%; height: 140px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border-color);" />
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="form-label">Upload New</label>
                        <input type="file" name="featured_image" class="form-control" accept="image/*" onchange="previewFile(event)" />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Or Image URL</label>
                        <input type="url" name="image_url" class="form-control" placeholder="https://..." value="{{ old('image_url', str_starts_with($post->featured_image ?? '', 'http') ? $post->featured_image : '') }}" oninput="previewUrl(this.value)" />
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="image_caption">Photo Caption</label>
                        <input type="text" id="image_caption" name="image_caption" class="form-control" value="{{ old('image_caption', $post->image_caption) }}" />
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

<script>
    function formatDoc(cmd, value = null) {
        document.execCommand(cmd, false, value);
        document.getElementById('editorContent').focus();
    }

    function formatLink() {
        const url = prompt('Enter link URL:');
        if (url) {
            document.execCommand('createLink', false, url);
        }
    }

    function syncContent() {
        const editor = document.getElementById('editorContent');
        const hidden = document.getElementById('hiddenContent');
        hidden.value = editor.innerHTML;
    }

    function previewFile(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('imagePreview');
            output.src = reader.result;
        };
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    function previewUrl(url) {
        if (url) {
            const output = document.getElementById('imagePreview');
            output.src = url;
        }
    }
</script>

<style>
@media (max-width: 900px) {
    .post-form-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
