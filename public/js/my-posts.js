document.addEventListener('DOMContentLoaded', async () => {
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = 'login.html';
        return;
    }

    await loadUserPosts(token);
});

async function loadUserPosts(token) {
    try {
        const response = await fetch('http://localhost:8000/api/user/posts', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            },
        });

        if (!response.ok) throw new Error('Failed to fetch posts');
        const posts = await response.json();
        posts.length ? displayUserPosts(posts) : displayNoPosts();
    } catch (error) {
        console.error('Error loading posts:', error);
        displayError('Failed to load posts. Please try again.');
    }
}

function displayUserPosts(posts) {
    const container = document.getElementById('userPosts');
    container.innerHTML = posts
        .map(
            (post) => `
        <div class="post-card">
            <div class="post-header">
                <h2 class="post-title">${post.title}</h2>
                <span class="post-date">${new Date(post.created_at).toLocaleDateString()}</span>
            </div>
            ${post.image ? `<img src="${post.image}" alt="Post image" class="post-image">` : ''}
            <p class="post-body">${post.body}</p>
            <div class="post-footer">
                <div class="post-actions">
                    <button onclick="editPost(${post.id})" class="btn">Edit</button>
                    <button onclick="deletePost(${post.id})" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    `
        )
        .join('');
}

function displayNoPosts() {
    const container = document.getElementById('userPosts');
    container.innerHTML = `
        <div class="no-posts">
            <p>You haven't created any posts yet.</p>
            <a href="create-post.html" class="btn">Create Your First Post</a>
        </div>
    `;
}

function displayError(message) {
    const container = document.getElementById('userPosts');
    container.innerHTML = `<div class="error-message"><p>${message}</p></div>`;
}

async function deletePost(postId) {
    if (!confirm('Are you sure you want to delete this post?')) return;

    const token = localStorage.getItem('token');
    try {
        const response = await fetch(`http://localhost:8000/api/posts/${postId}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            },
        });

        if (!response.ok) throw new Error('Failed to delete post');
        await loadUserPosts(token);
        alert('Post deleted successfully!');
    } catch (error) {
        console.error('Error deleting post:', error);
        alert('Failed to delete post. Please try again.');
    }
}

function editPost(postId) {
    window.location.href = `edit-post.html?id=${postId}`;
}

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