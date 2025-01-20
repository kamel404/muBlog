document.addEventListener('DOMContentLoaded', async () => {
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = 'login.html';
        return;
    }

    try {
        const response = await fetch('http://localhost:8000/api/user', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch profile');
        }

        const user = await response.json();
        
        // Populate form fields
        document.getElementById('profileUsername').value = user.username || '';
        document.getElementById('profileName').value = user.name || '';
        document.getElementById('profileEmail').value = user.email || '';

    } catch (error) {
        console.error('Error details:', error);
        alert('Failed to load profile. Please try again.');
    }
});

// Handle profile update
document.getElementById('profileForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const token = localStorage.getItem('token');
    const formData = {
        name: document.getElementById('profileName').value,
        email: document.getElementById('profileEmail').value
    };

    const password = document.getElementById('profilePassword').value;
    if (password) {
        formData.password = password;
    }

    try {
        const response = await fetch('http://localhost:8000/api/user', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(formData)
        });

        if (!response.ok) {
            throw new Error('Update failed');
        }

        alert('Profile updated successfully!');
        document.getElementById('profilePassword').value = '';
        
    } catch (error) {
        console.error('Error updating profile:', error);
        alert('Failed to update profile. Please try again.');
    }
});

function logout() {
    const token = localStorage.getItem('token');
    
    // Call logout API
    fetch('http://localhost:8000/api/logout', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    }).finally(() => {
        // Always clear token and redirect, even if API call fails
        localStorage.removeItem('token');
        window.location.href = '/';
    });
} 