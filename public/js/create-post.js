document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = 'login.html';
        return;
    }

    // Image preview
    document.getElementById('image').addEventListener('change', handleImagePreview);
    document.getElementById('createPostForm').addEventListener('submit', createPost);
});

function handleImagePreview(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        }
        reader.readAsDataURL(file);
    }
}

async function createPost(e) {
    e.preventDefault();

    const token = localStorage.getItem('token');
    const formData = new FormData();
    formData.append('title', document.getElementById('title').value);
    formData.append('body', document.getElementById('body').value);
    
    const imageFile = document.getElementById('image').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }

    try {
        const response = await fetch('http://localhost:8000/api/posts', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: formData
        });

        if (!response.ok) {
            throw new Error('Failed to create post');
        }

        alert('Post created successfully!');
        window.location.href = 'my-posts.html';
    } catch (error) {
        console.error('Error creating post:', error);
        alert('Failed to create post. Please try again.');
    }
} 