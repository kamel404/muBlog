const API_URL = 'http://localhost:8000/api';

// ============= Auth Section =============
const auth = {
    login: async (email, password) => {
        return apiCall('/login', 'POST', { email, password });
    },
    register: async (userData) => {
        return apiCall('/register', 'POST', userData);
    },
    logout: async () => {
        const response = await apiCall('/logout', 'POST');
        token.remove();
        return response;
    }
};

// ============= Posts Section =============
const posts = {
    fetchAll: async () => {
        return apiCall('/posts', 'GET');
    },
    fetchOne: async (id) => {
        return apiCall(`/posts/${id}`, 'GET');
    },
    create: async (formData) => {
        return apiCall('/posts', 'POST', formData, true);
    },
    update: async (id, formData) => {
        formData.append('_method', 'PUT');
        return apiCall(`/posts/${id}`, 'POST', formData, true);
    },
    delete: async (id) => {
        return apiCall(`/posts/${id}`, 'DELETE');
    }
};

// ============= Comments Section =============
const comments = {
    fetch: async (postId) => {
        return apiCall(`/posts/${postId}/comments`, 'GET');
    },
    create: async (postId, body) => {
        return apiCall(`/posts/${postId}/comments`, 'POST', { body });
    }
};

// ============= Likes Section =============
const likes = {
    toggle: async (postId) => {
        return apiCall(`/posts/${postId}/like`, 'POST');
    }
};

// ============= Profile Section =============
const profile = {
    fetch: async () => {
        return apiCall('/user', 'GET');
    },
    update: async (userData) => {
        return apiCall('/user', 'PUT', userData);
    }
};

// ============= Token Management =============
const token = {
    set: (token) => {
        if (token) localStorage.setItem('token', token);
    },
    get: () => localStorage.getItem('token'),
    remove: () => localStorage.removeItem('token')
};

// ============= Helper Functions =============
async function apiCall(endpoint, method = 'GET', data = null, isFormData = false) {
    const headers = {
        'Accept': 'application/json'
    };

    if (!isFormData) {
        headers['Content-Type'] = 'application/json';
    }

    const authToken = token.get();
    if (authToken) {
        headers['Authorization'] = `Bearer ${authToken}`;
    }

    const config = {
        method,
        headers
    };

    if (data) {
        config.body = isFormData ? data : JSON.stringify(data);
    }

    const response = await fetch(`${API_URL}${endpoint}`, config);
    const responseData = await response.json();

    if (!response.ok) {
        throw new Error(responseData.message || 'API call failed');
    }

    return responseData;
}

// Export all sections
const api = {
    auth,
    posts,
    comments,
    likes,
    profile,
    token
};