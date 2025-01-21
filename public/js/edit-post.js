let currentImage = null;
let removeImageFlag = false;

document.addEventListener('DOMContentLoaded', async () => {
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = 'login.html';
        return;
    }

    const urlParams = new URLSearchParams(window.location.search);
    const postId = urlParams.get('id');
    if (!postId) {
        alert('No post specified');
        window.location.href = 'my-posts.html';
        return;
    }

    document.getElementById('image').addEventListener('change', handleImagePreview);
    await loadPost(postId, token);
});

async function loadPost(postId, token) {
    try {
        const response = await fetch(`http://localhost:8000/api/posts/${postId}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            },
        });

        if (!response.ok) throw new Error('Failed to fetch post');
        const post = await response.json();

        document.getElementById('title').value = post.title;
        document.getElementById('body').value = post.body;

        if (post.image) {
            currentImage = post.image;
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = `<img src="${post.image}" alt="Post image">`;
            document.getElementById('imageActions').style.display = 'block';
        }
    } catch (error) {
        console.error('Error loading post:', error);
        alert('Failed to load post. Please try again.');
        window.location.href = 'my-posts.html';
    }
}

function handleImagePreview(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            document.getElementById('imageActions').style.display = 'block';
            removeImageFlag = false;
        };
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').innerHTML = '';
    document.getElementById('imageActions').style.display = 'none';
    removeImageFlag = true;
}

document.getElementById('editPostForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const token = localStorage.getItem('token');
    const postId = new URLSearchParams(window.location.search).get('id');
    const formData = new FormData();

    formData.append('title', document.getElementById('title').value);
    formData.append('body', document.getElementById('body').value);
    formData.append('_method', 'PUT');

    const imageFile = document.getElementById('image').files[0];
    if (imageFile) formData.append('image', imageFile);
    if (removeImageFlag) formData.append('remove_image', '1');

    try {
        const response = await fetch(`http://localhost:8000/api/posts/${postId}`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            },
            body: formData,
        });

        if (!response.ok) throw new Error('Failed to update post');
        alert('Post updated successfully!');
        window.location.href = 'my-posts.html';
    } catch (error) {
        console.error('Error updating post:', error);
        alert('Failed to update post. Please try again.');
    }
});

function logout() {
    const token = localStorage.getItem('token');
    fetch('http://localhost:8000/api/logout', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',
        },
    }).finally(() => {
        localStorage.removeItem('token');
        window.location.href = '/';
    });
}