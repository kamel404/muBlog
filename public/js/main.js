document.addEventListener('DOMContentLoaded', async () => {
    const token = localStorage.getItem('token');
    console.log('Token:', token);
    
    if (token) {
        try {
            updateNavigation(true);
            // Load posts only when user is authenticated
            await loadPosts(token);
        } catch (error) {
            console.error('Error:', error);
            localStorage.removeItem('token');
            updateNavigation(false);
            displayLoginMessage();
        }
    } else {
        updateNavigation(false);
        displayLoginMessage();
    }

    // Event listeners for buttons
    document.getElementById('loginBtn').addEventListener('click', showLoginForm);
    document.getElementById('registerBtn').addEventListener('click', showRegisterForm);
});

async function loadPosts(token) {
    try {
        const response = await fetch('http://localhost:8000/api/posts', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch posts');
        }

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

    // Add modal container if it doesn't exist
    if (!document.getElementById('imageModal')) {
        const modal = document.createElement('div');
        modal.id = 'imageModal';
        modal.className = 'image-modal';
        modal.innerHTML = `
            <span class="close-modal">&times;</span>
            <img class="modal-image" id="modalImage">
        `;
        document.body.appendChild(modal);

        // Add click event to close modal
        modal.querySelector('.close-modal').onclick = () => {
            modal.style.display = 'none';
        };
    }

    container.innerHTML = posts.map(post => `
        <div class="post-card">
            <div class="post-header">
                <h2 class="post-title">${post.title}</h2>
            </div>
            ${post.image ? `
                <img src="${post.image}" 
                     alt="Post image" 
                     class="post-image" 
                     onclick="showFullImage('${post.image}')"
                >
            ` : ''}
            <p class="post-body">${post.body}</p>
            <div class="post-footer">
                <div class="post-info">
                    <span class="post-author">Posted by: ${post.user.name} (@${post.user.username})</span>
                    <span class="post-date">${new Date(post.created_at).toLocaleDateString()}</span>
                </div>
                <div class="post-actions">
                    <button onclick="likePost(${post.id})" class="btn">
                        <span id="likeCount-${post.id}">${post.likes_count || 0}</span> Likes
                    </button>
                    <button onclick="showComments(${post.id})" class="btn">
                        <span id="commentCount-${post.id}">${post.comments_count || 0}</span> Comments
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

function displayError(message) {
    const container = document.getElementById('postsContainer');
    container.innerHTML = `
        <div class="error-message">
            <p>${message}</p>
        </div>
    `;
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

// Add these functions
function showLoginForm() {
    document.getElementById('loginModal').style.display = 'block';
}

function showRegisterForm() {
    document.getElementById('registerModal').style.display = 'block';
}

function closeModals() {
    document.getElementById('loginModal').style.display = 'none';
    document.getElementById('registerModal').style.display = 'none';
}

// Add event listeners for forms
document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    
    try {
        const response = await login(email, password);
        if (response.token) {
            setAuthToken(response.token);
            closeModals();
            updateUI(true);
        }
    } catch (error) {
        alert('Login failed. Please try again.');
    }
});

document.getElementById('registerForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const name = document.getElementById('registerName').value;
    const email = document.getElementById('registerEmail').value;
    const password = document.getElementById('registerPassword').value;
    
    try {
        const response = await register(name, email, password);
        if (response.token) {
            setAuthToken(response.token);
            closeModals();
            updateUI(true);
        }
    } catch (error) {
        alert('Registration failed. Please try again.');
    }
});

// Add close button functionality
document.querySelectorAll('.close').forEach(button => {
    button.addEventListener('click', closeModals);
});

// Update UI based on auth state
function updateUI(isLoggedIn) {
    const navLinks = document.querySelector('.nav-links');
    if (isLoggedIn) {
        navLinks.innerHTML = `
            <button onclick="logout()">Logout</button>
        `;
    } else {
        navLinks.innerHTML = `
            <button id="loginBtn">Login</button>
            <button id="registerBtn">Register</button>
        `;
    }
}

function updateNavigation(isAuthenticated) {
    const navLeft = document.querySelector('.nav-left');
    const navRight = document.querySelector('.nav-right');
    
    if (isAuthenticated) {
        navLeft.innerHTML = '<a href="profile.html" class="btn">My Profile</a>';
        navRight.innerHTML = `
            <a href="my-posts.html" class="btn">MY POSTS</a>
            <a href="javascript:void(0)" onclick="logout()" class="btn">LOGOUT</a>
        `;
    } else {
        // When not authenticated, show empty nav sections
        navLeft.innerHTML = '';
        navRight.innerHTML = '';
    }
}

function logout() {
    const token = localStorage.getItem('token');
    if (token) {
        fetch('http://localhost:8000/api/logout', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        }).finally(() => {
            localStorage.removeItem('token');
            window.location.href = '/';
        });
    } else {
        window.location.href = '/';
    }
}

// Add these functions to main.js
async function likePost(postId) {
    const token = localStorage.getItem('token');
    if (!token) {
        alert('Please login to like posts');
        window.location.href = 'login.html';
        return;
    }

    try {
        const response = await fetch(`http://localhost:8000/api/posts/${postId}/like`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        
        // Update like count with the new count from server
        const likeCount = document.getElementById(`likeCount-${postId}`);
        likeCount.textContent = data.likes_count;

        // Update button appearance based on like status
        const likeButton = likeCount.parentElement;
        if (response.status === 201) {
            likeButton.classList.add('liked');
        } else {
            likeButton.classList.remove('liked');
        }
    } catch (error) {
        console.error('Error toggling like:', error);
        alert('Failed to update like. Please try again.');
    }
}

function showComments(postId) {
    const modal = document.getElementById('commentModal');
    if (!modal) {
        createCommentModal();
    }
    loadComments(postId);
    document.getElementById('commentModal').style.display = 'block';
    document.getElementById('currentPostId').value = postId;
}

async function loadComments(postId) {
    const token = localStorage.getItem('token');
    try {
        const response = await fetch(`http://localhost:8000/api/posts/${postId}/comments`, {
            headers: {
                'Accept': 'application/json',
                ...(token && { 'Authorization': `Bearer ${token}` })
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch comments');
        }

        const comments = await response.json();
        displayComments(comments);
    } catch (error) {
        console.error('Error loading comments:', error);
        alert('Failed to load comments. Please try again.');
    }
}

function displayComments(comments) {
    const container = document.getElementById('commentsList');
    if (!comments || comments.length === 0) {
        container.innerHTML = '<div class="no-comments">No comments yet. Be the first to comment!</div>';
        return;
    }

    container.innerHTML = comments.map(comment => `
        <div class="comment">
            <div class="comment-header">
                <span class="comment-author">${comment.user.name}</span>
                <span class="comment-date">${new Date(comment.created_at).toLocaleDateString()}</span>
            </div>
            <p class="comment-body">${comment.body}</p>
        </div>
    `).join('');
}

async function submitComment(event) {
    event.preventDefault();
    const token = localStorage.getItem('token');
    if (!token) {
        alert('Please login to comment');
        window.location.href = 'login.html';
        return;
    }

    const postId = document.getElementById('currentPostId').value;
    const commentBody = document.getElementById('commentInput').value;

    try {
        const response = await fetch(`http://localhost:8000/api/posts/${postId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ body: commentBody })
        });

        if (!response.ok) {
            throw new Error('Failed to post comment');
        }

        document.getElementById('commentInput').value = '';
        await loadComments(postId);
        
        // Update comment count in the post
        const commentCount = document.getElementById(`commentCount-${postId}`);
        commentCount.textContent = parseInt(commentCount.textContent) + 1;
    } catch (error) {
        console.error('Error posting comment:', error);
        alert('Failed to post comment. Please try again.');
    }
}

function createCommentModal() {
    const modal = document.createElement('div');
    modal.id = 'commentModal';
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <span class="close" onclick="closeCommentModal()">&times;</span>
            <h2>Comments</h2>
            <div id="commentsList" class="comments-list"></div>
            <form id="commentForm" class="comment-form">
                <input type="hidden" id="currentPostId">
                <textarea id="commentInput" placeholder="Write a comment..." required></textarea>
                <button type="submit" class="btn">Post Comment</button>
            </form>
        </div>
    `;
    document.body.appendChild(modal);
    document.getElementById('commentForm').addEventListener('submit', submitComment);
}

function closeCommentModal() {
    document.getElementById('commentModal').style.display = 'none';
}

// Add function to show full-size image
function showFullImage(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    modal.style.display = 'block';
    modalImg.src = imageSrc;
}

// Close modal when clicking outside the image
document.addEventListener('click', (e) => {
    const modal = document.getElementById('imageModal');
    if (e.target === modal) {
        modal.style.display = 'none';
    }
}); 