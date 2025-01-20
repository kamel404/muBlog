const API_URL = 'http://localhost:8000/api';

async function fetchPosts() {
    const token = localStorage.getItem('token');
    if (!token) return [];

    try {
        const response = await fetch(`${API_URL}/posts`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        if (!response.ok) throw new Error('Failed to fetch posts');
        return await response.json();
    } catch (error) {
        console.error('Error fetching posts:', error);
        return [];
    }
}

async function login(email, password) {
    try {
        const response = await fetch(`${API_URL}/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });
        return await response.json();
    } catch (error) {
        console.error('Error logging in:', error);
        throw error;
    }
}

async function register(name, email, password) {
    try {
        const response = await fetch(`${API_URL}/register`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, email, password })
        });
        return await response.json();
    } catch (error) {
        console.error('Error registering:', error);
        throw error;
    }
}

function setAuthToken(token) {
    if (token) {
        localStorage.setItem('token', token);
    }
}

function getAuthToken() {
    return localStorage.getItem('token');
}

function removeAuthToken() {
    localStorage.removeItem('token');
} 