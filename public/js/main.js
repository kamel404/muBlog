document.addEventListener('DOMContentLoaded', async () => {
    const token = localStorage.getItem('token');
    if (!token) {
        updateNavigation(false);
        displayLoginMessage();
        return;
    }

    try {
        updateNavigation(true);
        await loadPosts(token);
    } catch (error) {
        console.error('Error:', error);
        localStorage.removeItem('token');
        updateNavigation(false);
        displayLoginMessage();
    }
});

async function loadPosts(token) {
    try {
        const response = await fetch('http://localhost:8000/api/posts', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            },
        });

        if (!response.ok) throw new Error('Failed to fetch posts');
        const posts = await response.json();
        displayPosts(posts);
    } catch (error) {
        console.error('Error loading posts:', error);
        displayError('Failed to load posts. Please try again.');
    }
}

function displayPosts(posts) {
    const container = document.getElementById('postsContainer');
    if (!posts.length) {
        container.innerHTML = '<div class="no-posts"><p>No posts available.</p></div>';
        return;
    }

    if (!document.getElementById('imageModal')) createImageModal();

    container.innerHTML = posts
        .map(
            (post) => `
        <div class="post-card">
            <div class="post-header">
                <h2 class="post-title">${post.title}</h2>
            </div>
            ${post.image ? `<img src="${post.image}" alt="Post image" class="post-image" onclick="showFullImage('${post.image}')">` : ''}
            <p class="post-body">${post.body}</p>
            <div class="post-footer">
                <div class="post-info">
                    <span class="post-author">Posted by: ${post.user.name} (@${post.user.username})</span>
                    <span class="post-date">${new Date(post.created_at).toLocaleDateString()}</span>
                </div>
                <div class="post-actions">
                    <button onclick="likePost(${post.id})" class="btn ${post.is_liked ? 'liked' : ''}" id="likeBtn-${post.id}">
                        <span id="likeCount-${post.id}">${post.likes_count || 0}</span> Likes
                    </button>
                    <button onclick="showComments(${post.id})" class="btn">
                        <span id="commentCount-${post.id}">${post.comments_count || 0}</span> Comments
                    </button>
                </div>
            </div>
        </div>
    `
        )
        .join('');
}

function createImageModal() {
    const modal = document.createElement('div');
    modal.id = 'imageModal';
    modal.className = 'image-modal';
    modal.innerHTML = `
        <span class="close-modal">&times;</span>
        <img class="modal-image" id="modalImage">
    `;
    document.body.appendChild(modal);

    modal.querySelector('.close-modal').onclick = () => (modal.style.display = 'none');
}

function displayError(message) {
    const container = document.getElementById('postsContainer');
    container.innerHTML = `<div class="error-message"><p>${message}</p></div>`;
}

function displayLoginMessage() {
    const container = document.getElementById('postsContainer');
    container.innerHTML = `
        <div class="welcome-message">
            <h2>Welcome to MUBLOG</h2>
            <p>Please login or register to view and interact with posts.</p>
            <div class="auth-buttons">
                <a href="login.html" class="btn">Login</a>
                <a href="register.html" class="btn btn-secondary">Register</a>
            </div>
        </div>
    `;
}

function updateNavigation(isAuthenticated) {
    const navLeft = document.querySelector('.nav-left');
    const navRight = document.querySelector('.nav-right');

    navLeft.innerHTML = isAuthenticated ? '<a href="profile.html" class="btn">My Profile</a>' : '';
    navRight.innerHTML = isAuthenticated
        ? `<a href="my-posts.html" class="btn">MY POSTS</a>
           <a href="javascript:void(0)" onclick="logout()" class="btn">LOGOUT</a>`
        : '';
}

function logout() {
    const token = localStorage.getItem('token');
    if (token) {
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
    } else {
        window.location.href = '/';
    }
}

function showFullImage(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    modal.style.display = 'block';
    modalImg.src = imageSrc;
}

document.addEventListener('click', (e) => {
    const modal = document.getElementById('imageModal');
    if (e.target === modal) modal.style.display = 'none';
});

async function likePost(postId) {
    try {
        const response = await api.likes.toggle(postId);
        const likeCount = document.getElementById(`likeCount-${postId}`);
        const likeBtn = document.getElementById(`likeBtn-${postId}`);
        
        if (likeCount) {
            likeCount.textContent = response.likes_count;
        }
        
        // Toggle the liked class
        if (likeBtn) {
            likeBtn.classList.toggle('liked');
        }
    } catch (error) {
        console.error('Error toggling like:', error);
        alert('Failed to like post. Please try again.');
    }
}

async function showComments(postId) {
    try {
        const post = await api.posts.fetchOne(postId);
        const comments = post.comments || [];
        
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.innerHTML = `
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3>Comments</h3>
                <div class="comments-list">
                    ${comments.length ? comments.map(comment => `
                        <div class="comment">
                            <div class="comment-header">
                                <span class="comment-author">${comment.user.name}</span>
                                <span class="comment-date">${new Date(comment.created_at).toLocaleDateString()}</span>
                            </div>
                            <p class="comment-body">${comment.body}</p>
                        </div>
                    `).join('') : '<p class="no-comments">No comments yet.</p>'}
                </div>
                <form class="comment-form" onsubmit="submitComment(event, ${postId})">
                    <textarea required placeholder="Write a comment..."></textarea>
                    <button type="submit" class="btn">Post Comment</button>
                </form>
            </div>
        `;

        document.body.appendChild(modal);
        modal.style.display = 'block';

        modal.querySelector('.close').onclick = () => {
            modal.remove();
        };

        window.onclick = (event) => {
            if (event.target === modal) {
                modal.remove();
            }
        };
    } catch (error) {
        console.error('Error showing comments:', error);
        alert('Failed to load comments. Please try again.');
    }
}

async function submitComment(event, postId) {
    event.preventDefault();
    const form = event.target;
    const textarea = form.querySelector('textarea');
    const comment = textarea.value;

    try {
        await api.comments.create(postId, comment);
        
        // Update comment count
        const commentCount = document.getElementById(`commentCount-${postId}`);
        if (commentCount) {
            const currentCount = parseInt(commentCount.textContent);
            commentCount.textContent = currentCount + 1;
        }
        
        // Refresh comments
        const modal = form.closest('.modal');
        modal.remove();
        showComments(postId);
    } catch (error) {
        console.error('Error posting comment:', error);
        alert('Failed to post comment. Please try again.');
    }
}