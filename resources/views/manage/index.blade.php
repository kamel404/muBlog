@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-gray-800 border-b-2 border-blue-200 pb-4">Management Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Manage Posts -->
        <div class="bg-blue-50 p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-blue-800 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 4h3v12H9V4zm4 0h3v12h-3V4zM5 4h2v12H5V4zM2 2h16v2H2V2zm0 14h16v2H2v-2z" />
                    </svg>
                    Your Posts
                </h2>
                <a href="{{ route('posts.create') }}" class="btn-primary">
                    New Post
                </a>
            </div>
            <ul class="space-y-2">
                @foreach ($posts as $post)
                    <li
                        class="group flex items-center justify-between p-3 bg-white rounded-lg hover:bg-blue-100 transition-colors">
                        <div class="flex-1 truncate">
                            <a href="{{ route('posts.show', $post) }}" class="text-gray-700 hover:text-blue-600">
                                {{ $post->title }}
                            </a>
                            <span class="block text-xs text-gray-400 mt-1">{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center space-x-2 ml-3">
                            <a href="{{ route('posts.edit', $post) }}"
                                class="text-blue-400 hover:text-blue-600 p-1 rounded-full hover:bg-blue-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this post?')"
                                    class="text-red-400 hover:text-red-600 p-1 rounded-full hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        @if(auth()->user()->isAdmin())
            <!-- Manage Categories -->
            <div class="bg-green-50 p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-green-800 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M3 4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H3zm0 2h12v3H3V6zm0 5h6v3H3v-3zm0 5h6v2H3v-2zm8-5h4v2h-4v-2zm0 5h4v2h-4v-2z" />
                        </svg>
                        Categories
                    </h2>
                    <button onclick="openCategoryModal()" class="btn-primary">
                        New Category
                    </button>
                </div>
                <ul class="space-y-2">
                    @foreach ($categories as $category)
                        <li
                            class="flex items-center justify-between p-3 bg-white rounded-lg hover:bg-green-100 transition-colors">
                            <span class="text-gray-700">{{ $category->name }}</span>
                            <div class="flex items-center space-x-2">
                                <form action="{{ route('manage.deleteCategory', $category) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this category?')"
                                        class="text-red-400 hover:text-red-600 p-1 rounded-full hover:bg-red-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Manage Users Table -->
            <div class="bg-purple-50 p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow col-span-full">
                <h2 class="text-xl font-semibold mb-4 text-purple-800 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                    Users Management
                </h2>
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full">
                        <thead class="bg-purple-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-purple-800">Name</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-purple-800">Email</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-purple-800">Role</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-purple-800">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $user->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $user->email }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span
                                            class="px-2 py-1 rounded-full {{ $user->role_id === 1 ? 'bg-purple-200 text-purple-800' : 'bg-gray-200 text-gray-600' }}">
                                            {{ $user->role->name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm space-x-2">
                                        <button onclick="openRoleModal('{{ $user->id }}', '{{ $user->role_id }}')"
                                            class="text-blue-400 hover:text-blue-600 p-1 rounded-full hover:bg-blue-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        <form action="{{ route('manage.deleteUser', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Are you sure you want to delete this user?')"
                                                class="text-red-400 hover:text-red-600 p-1 rounded-full hover:bg-red-50">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modals -->
<div id="roleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Update User Role</h3>
        <form id="roleForm" method="POST">
            @csrf
            @method('PUT')
            <select name="role_id" class="w-full p-2 border rounded-md mb-4">
                <option value="1">Admin</option>
                <option value="2">User</option>
            </select>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeRoleModal()" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Update Role</button>
            </div>
        </form>
    </div>
</div>

<!-- Category Modal -->
<div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4" id="categoryModalTitle">Edit Category</h3>
        <form id="categoryForm" method="POST" enctype="multipart/form-data" action="">
            @csrf
            <input type="hidden" name="_method" id="categoryFormMethod" value="PUT">
            <input type="text" name="name" id="categoryName" class="w-full p-2 border rounded-md mb-4" placeholder="Category Name" required>
            <input type="file" name="image" class="w-full p-2 border rounded-md mb-4">
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeCategoryModal()" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Close modal on back button press
    window.addEventListener('popstate', function () {
        closeCategoryModal();
    });

    // Function to close the category modal
    function closeCategoryModal() {
        const modal = document.getElementById('categoryModal');
        modal.classList.add('hidden');
        modal.style.display = 'none'; // Ensure the modal is hidden
    }

    // Function to open the category modal
    function openCategoryModal() {
        const modal = document.getElementById('categoryModal');
        const form = document.getElementById('categoryForm');
        const title = document.getElementById('categoryModalTitle');

        // Set modal title
        title.textContent = 'Add Category';

        // Set form action for creating
        form.action = "{{ route('manage.createCategory') }}";

        // Ensure method spoofing for POST
        form.querySelector('[name="_method"]').value = 'POST';

        // Clear form values
        form.querySelector('[name="name"]').value = '';
        form.querySelector('[name="image"]').value = '';

        // Display modal
        modal.style.display = 'flex';
        modal.classList.remove('hidden');

        // Push state to history to track modal open
        history.pushState({ modalOpen: true }, '', window.location.href);
    }

    function closeRoleModal() {
        const modal = document.getElementById('roleModal');
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }

    function openRoleModal(userId, roleId) {
        const modal = document.getElementById('roleModal');
        const form = document.getElementById('roleForm');

        // Set form action for editing
        form.action = `/manage/users/${userId}/role`;

        // Set form values
        form.querySelector('[name="role_id"]').value = roleId;

        // Display modal
        modal.style.display = 'flex';
        modal.classList.remove('hidden');

        // Push state to history to track modal open
        history.pushState({ modalOpen: true }, '', window.location.href);
    }

</script>

<style>
    .btn-primary {
        @apply px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm;
    }

    .btn-secondary {
        @apply px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm;
    }
</style>
@endsection
