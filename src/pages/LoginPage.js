import React, { useState } from 'react';
import { Link } from 'react-router-dom';

function LoginPage() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const handleEmailChange = (e) => {
    setEmail(e.target.value);
  };

  const handlePasswordChange = (e) => {
    setPassword(e.target.value);
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    // Handle form submission
  };

  return (
    <div className="login-page">
      {/* ... existing form elements ... */}
      
      <p className="mt-3 text-center">
        Don't have an account? <Link to="/register">Register here</Link>
      </p>
      
    </div>
  );
}

export default LoginPage; 